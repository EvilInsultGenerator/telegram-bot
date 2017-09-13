<?php
require ("config.inc.php");

// Inline Query handling
// When no or an unknown string (= language code) is given, generate a completely random (language + insult) one

if ($query != null && $query != '' || $query == '')
{
	if( array_key_exists($query, $supportedLanguages) )
	{
		$generatedInsult = generateInsult($query);
		$results = json_encode([['type' => 'article', 'id' => '1', 'title' => $supportedLanguages[$query].' Insult', 'message_text' => $generatedInsult]]);
		$content = ['inline_query_id' => $queryid, 'results' => $results, 'cache_time' => 1, 'is_personal' => 'true', 'next_offset' => '', ];
		$reply = $telegram->answerInlineQuery($content);
	}
	else
	{
		$generatedInsult = generateInsult('en');
		switch ($query)
		{		
			case '':
				$rand_lang = random_key($supportedLanguages);
				$generatedInsult = generateInsult($rand_lang);
				$results = json_encode([['type' => 'article', 'id' => '1', 'title' => 'Random Insult', 'message_text' => $generatedInsult], ['type' => 'article', 'id' => '2', 'title' => 'Send Homepage', 'message_text' => forURL() , 'parse_mode' => 'HTML']]);
				$content = ['inline_query_id' => $queryid, 'results' => $results, 'cache_time' => 1, 'is_personal' => 'true', 'next_offset' => '', ];
				$reply = $telegram->answerInlineQuery($content);
				break;

			default:
				$rand_lang = random_key($supportedLanguages);
				$generatedInsult = generateInsult($rand_lang);
				$results = json_encode([['type' => 'article', 'id' => '1', 'title' => $generatedInsult, 'message_text' => $generatedInsult]]);
				$content = ['inline_query_id' => $queryid, 'results' => $results, 'cache_time' => 1, 'is_personal' => 'true', 'next_offset' => '', ];
				$reply = $telegram->answerInlineQuery($content);
				break;
		}
	}
}

if ($text == '/start')
	{

	// Create a permanent custom keyboard

	$option = array(

		// First row

		array(
			$telegram->buildKeyboardButton("Generate Insult")
		) ,

		// Second row

		array(
			$telegram->buildKeyboardButton("Change Language") ,
			$telegram->buildKeyboardButton("Website") ,
			$telegram->buildKeyboardButton("Help")
		)
	);
	$keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
	
// First response after receiving "/start" from userLang

	$content = array(
		'chat_id' => $chat_id,
		'reply_markup' => $keyb,
		'text' => "
Hi, this is the official Evil Insult Generator Telegram Bot! \xf0\x9f\x98\x88 \n
Evil Insult Generator's goal is to offer the most evil insults. Please help us to reach this honorable purpose by submitting insults via mail. \n
The button Generate Insult sends you a random insult in the language you selected with the Change Language button. Default is English. \n
Clicking on Homepage gives you the link to our awesome homepage. \n
The bot supports Telegram groups and inline messages. \n
@EvilInsultGeneratorBot without any additional info shows the menu where you can send a random insult or the link to the homepage.\n
If you add a language code (e.g. @EvilInsultGeneratorBot la) it shows you a preview in that language and you can click on it to submit it.\n
In case you write an unsupported language code (e.g. @EvilInsultGeneratorBot xyz) it sends one completely random insult. \n
Questions and feedback are very welcome. \n
Take care!
"
	);
	$telegram->sendMessage($content);
	}

switch ($text)
	{
		
// Website Button

case 'Website':
	$content = ['chat_id' => $chat_id, 'text' => forURL() , 'parse_mode' => 'HTML'];
	$telegram->sendMessage($content);
	break;

// Help Button

case 'Help':
	$content = ['chat_id' => $chat_id, 'text' => getHelp() ];
	$telegram->sendMessage($content);
	break;

// Generate Insult Button

case 'Generate Insult':
	$stmtSELECT->execute(array(
		'chatid' => $chat_id
	));
	$result = $stmtSELECT->fetchAll();
	$userLang = $result[0]['language'];
	$generatedInsult = generateInsult($userLang);
	$content = ['chat_id' => $chat_id, 'text' => $generatedInsult];
	$telegram->sendMessage($content);
	break;

// Change Language Button, only German and English are enabled for now
// $telegram->buildInlineKeyBoardButton('Spanish', $url = '', $callback_data = 'es') ,


case 'Change Language':
	$option = [[$telegram->buildInlineKeyBoardButton('English', $url = '', $callback_data = 'en') , $telegram->buildInlineKeyBoardButton('German', $url = '', $callback_data = 'de') , ], ];
	$keyb = $telegram->buildInlineKeyBoard($option);
	$content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Please select a language'];
	$telegram->sendMessage($content);
	break;

default:
	break;
	}

// Get language Callback

$callback_query = $telegram->Callback_Query();

if ($callback_query !== null && $callback_query != '')
	{
	$stmtSELECT->execute(array(
		'chatid' => $chat_id
	));
	$result = $stmtSELECT->fetchAll();
	if (count($result) > 0)
		{

		// User already exists

		$newlanguage = $telegram->Callback_Data();
		$reply = 'Language switched to: ' . $newlanguage;
		$content = ['chat_id' => $telegram->Callback_ChatID() , 'text' => $reply];
		$telegram->sendMessage($content);
		$stmtUPDATE->execute(array(
			'chatid' => $chat_id,
			'language' => $newlanguage
		));
		}
	  else
		{

		// User is not known

		$newlanguage = $telegram->Callback_Data();
		$content = ['chat_id' => $chat_id, 'text' => 'Language has been set!'];
		$telegram->sendMessage($content);
		$stmtINSERT->execute(array(
			'chatid' => $chat_id,
			'language' => $newlanguage
		));
		}
	}
	
// Backend call to generate insult

function generateInsult($lang)
	{
	$insult = file_get_contents("https://evilinsult.com/generate_insult.php?lang=" . $lang);
	$insult = htmlspecialchars_decode($insult);
	return $insult;
	}

// Get random key from an array
function random_key($array){
    $keys=array_keys($array);
    return $keys[mt_rand(0, count($keys) - 1)];
} 

// Format Website URL as HTML message

function forURL()
	{
	$url = ' <b>Visit us @ </b><a href="https://evilinsult.com/">EvilInsult.com</a>';
	return $url;
	}

// Help Message

function getHelp()
	{
	$message = "
Hi, this is the official Evil Insult Generator Telegram Bot! \xf0\x9f\x98\x88 \n
Evil Insult Generator's goal is to offer the most evil insults. Please help us to reach this honorable purpose by submitting insults via mail. \n
The button Generate Insult sends you a random insult in the language you selected with the Change Language button. Default is English. \n
Clicking on Homepage gives you the link to our awesome homepage. \n
The bot supports Telegram groups and inline messages. \n
@EvilInsultGeneratorBot without any additional info shows the menu where you can send a random insult or the link to the homepage.\n
If you add a language code (e.g. @EvilInsultGeneratorBot la) it shows you a preview in that language and you can click on it to submit it.\n
In case you write an unsupported language code (e.g. @EvilInsultGeneratorBot xyz) it sends one completely random insult. \n
Questions and feedback are very welcome. \n
Take care!";
	return $message;
	}
?>