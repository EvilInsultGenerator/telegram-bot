<?php
/**
 * Telegram Bot access token URL.
 */
$access_token = 'YOURACCESSTOKENHERE';
$url = 'https://api.telegram.org/bot' . $access_token;
$output = json_decode(file_get_contents('php://input'), true);
$chat_id = $output['message']['chat']['id'];
$message = $output['message']['text'];
$fp = json_decode(file_get_contents('user.json'), true);

if(isset($output['inline_query'])){
  $id = $output['inline_query']['from']['id'];
  
  $inline_lang = checkLanguageInline($fp,$id);
 
    $input_context = array(
                           "message_text" => "russik is cool"
                          );
//$fuck = file_get_contents("errors.txt");
    $say = file_get_contents("https://evilinsult.com/generate_insult.php?lang=".$inline_lang);
    $gen = array( "type" => "article",
                  "id" => "2",
                  "title" => "Generate",
                  "input_message_content" => array("message_text"=>$say)
                  );
    $home = array( "type" => "article",
                  "id" => "1",
                  "title" => "Home Page",
                  "input_message_content" => array("message_text"=>'<a href="https://evilinsult.com/">Visit our web site</a>',
                                                    "parse_mode" => "HTML"),
                 
                  );

    $drug = json_encode([$gen,$home]);
    
    file_get_contents("https://api.telegram.org/bot".$access_token."/answerInlineQuery?inline_query_id=".$output['inline_query']['id']."&results=".$drug."&cache_time=1"); 
}

function checkLanguageInline($mass,$chat_id){
    $language = 'en';
    foreach ( $mass as $key=> $value) {
        if($key==$chat_id){
            $language = $value;
        }
    }
    return $language;
}

$botanToken = 'YOURBOTANTOKENHERE';
file_get_contents("https://api.botan.io/track?token=".$botanToken."&uid=".$chat_id."&name=search");
file_get_contents("https://api.botan.io/track?token=".$botanToken."&uid=".$chat_id."&name=search%20californication");

function _incomingMessage($output) {
    $messageData = $output['message'];
    $botan = new Botan($this->access_token);
    $botan->track($messageData, 'Start');
}
if(isset($output['callback_query']['data'])){
if (checkUser($fp, $output['callback_query']['message']['chat']['id']) != false) {
            foreach ( $fp as $key=> $value) {
              if($key==$output['callback_query']['message']['chat']['id']){
                 $fp[$key] = $output['callback_query']['data'];
              }
             }
             $arr3 = json_encode($fp);
             file_put_contents('user.json', $arr3);
          }
          else{
           AddUserLanguage($output['callback_query']['message']['chat']['id'],$fp,$output['callback_query']['data']);
          }
file_get_contents("https://api.telegram.org/bot".$access_token."/sendMessage?chat_id=".$output['callback_query']['message']['chat']['id']."&text=Language successfully changed to: ".($output['callback_query']['data'])."&parse_mode=HTML");//exit();
exit();
            
}

$emoji = array(
  'preload' => json_decode('"\ud83d\udc79"')
);
switch ($message) {
    case '/start':
        $message = 'Welcome To The Evil Insult Generator Telegram Bot!'. $emoji['preload'] ;
    sendMessage($chat_id,$message.printKeybord(), $access_token);
        break;
    case 'Language':
         $message = 'Choose language.';
    sendMessage($chat_id,$message.inlineKeybord(), $access_token);
        break;
    case '/language':
         $message = 'Choose language.';
    sendMessage($chat_id,$message.inlineKeybord(), $access_token);
        break;
     case 'Genegate Insult':
        checkLanguage($fp,$chat_id, $access_token);
        break;
    case 'Homepage':
        $message='';
          sendMessage($chat_id,forURL(), $access_token);
        break;
    default:
       checkLanguage($fp, $chat_id, $access_token);
}


function genegateInsult($chat_id,$lang, $asses_token){
    $fuck = file_get_contents("https://evilinsult.com/generate_insult.php?lang=".$lang);
    sendMessage($chat_id, $fuck, $asses_token);
}



function sendMessage($chat_id, $message, $token) {
    file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$message.printKeybord()."&parse_mode=HTML");
}




function checkUser($mass,$chat_id){
    $is = false;
    foreach ( $mass as $key=> $value) {
        if($key==$chat_id){
            $is = true;
        }
    }
    return $is;
}




function AddUserLanguage($chat_id,$mass,$message){
    $mass[$chat_id] = $message;
    $arr3 = json_encode($mass);
    file_put_contents('user.json', $arr3);
}





function AddUser($chat_id,$mass,$message, $token){
    $mass[$chat_id] = $message;
    $arr3 = json_encode($mass);
    file_put_contents('user.json', $arr3);
    genegateInsult($chat_id,$message, $token);
}






function checkLanguage($mass,$chat_id, $token){
    $language = 'en';
    foreach ( $mass as $key=> $value) {
        if($key==$chat_id){
            $language = $value;
        }
    }
    genegateInsult($chat_id,$language, $token);
}





function printKeybord(){
        $reply_markup = '';
    $buttons = [['Generate Insult'],['Language','Homepage']];
    $keyboard = json_encode($keyboard = [
        'keyboard' => $buttons /*[$buttons]*/,
        'resize_keyboard' => true,
        'one_time_keyboard' => false,
        'parse_mode' => 'HTML',
        'selective' => true
    ]);
    $reply_markup = '&reply_markup=' . $keyboard . '';
    
    return $reply_markup;
}
function inlineKeybord(){ //create a text description that will be passed to the server
$reply_markup = '';
$x1 = array('text'=>'en','callback_data'=>"en");
$x2 = array('text'=>'zh','callback_data'=>"zh");
$x3 = array('text'=>'es','callback_data'=>"es");
$x4 = array('text'=>'hi','callback_data'=>"hi");
$x5 = array('text'=>'ar','callback_data'=>"ar");
$x6 = array('text'=>'pt','callback_data'=>"pt");
$x7 = array('text'=>'bn','callback_data'=>"bn");
$x8 = array('text'=>'ru','callback_data'=>"ru");
$x9 = array('text'=>'ja','callback_data'=>"ja");
$x10 = array('text'=>'jv','callback_data'=>"jv");
$x11 = array('text'=>'sw','callback_data'=>"sw");
$x12 = array('text'=>'de','callback_data'=>"de");
$x13 = array('text'=>'ko','callback_data'=>"ko");
$x14 = array('text'=>'fr','callback_data'=>"fr");
$x15 = array('text'=>'te','callback_data'=>"te");
$x16 = array('text'=>'mr','callback_data'=>"mr");
$x17 = array('text'=>'tr','callback_data'=>"tr");
$x18 = array('text'=>'ta','callback_data'=>"ta");
$x19 = array('text'=>'vi','callback_data'=>"vi");
$x20 = array('text'=>'ur','callback_data'=>"ur");
$x21 = array('text'=>'el','callback_data'=>"el");
$x22 = array('text'=>'it','callback_data'=>"it");
$x23 = array('text'=>'cs','callback_data'=>"cs");
$x24 = array('text'=>'la','callback_data'=>"la");
//You should create a new variable $xn(next6 number), and you should describe about it in the field "text" and add "callback_data", 
//which will return to the server
///Displays only message
$opz = [[$x1,$x2,$x3,$x4],[$x5,$x6,$x7,$x8],[$x9,$x10,$x11,$x12],[$x13,$x14,$x15,$x16],[$x17,$x18,$x19,$x20],[$x21,$x22,$x23,$x24]];
$keyboard=array("inline_keyboard"=>$opz);
$keyboard = json_encode($keyboard,true);
     $reply_markup = '&reply_markup=' . $keyboard;
    return $reply_markup;
}
function forURL(){
    $HTML='<a href="https://evilinsult.com/">http://evilinsult.com/</a>';
    return $HTML;
}

