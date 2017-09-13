<?php

// Set Database connection (SQLlite)

$file_db = new PDO('sqlite:user_settings.db');
$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Bot token obtained from Botfather

$bot_token = "YOUR-BOTFATHER-TOKEN";

// Using the TelegramBotPHP library by Eleirbag89 - https://github.com/Eleirbag89/TelegramBotPHP

require ("Telegram.php");

$telegram = new Telegram($bot_token);
$text = $telegram->Text();
$data = $telegram->getData();
$query = $data['inline_query']['query'];
$user = $data['inline_query']['from'];
$queryid = $data['inline_query']['id'];
$chat_id = $telegram->ChatID();

// Language array for selecting random language - We enable English and German for now
// $supportedLanguages = ['en'=>'English','zh'=>'Chinese','es'=>'Spanish','hi'=>'Hindi','pt'=>'Portugese','bn'=>'Bengali','ru'=>'Russian','ja'=>'Japanese','jv'=>'Javanese','sw'=>'Swedish','de'=>'German','ko'=>'Korean','fr'=>'French','te'=>'Telugu','mr'=>'Marathi','tr'=>'Turkish','ta'=>'Tamil','vi'=>'Vietnamese','ur'=>'Urdu','el'=>'Greek','it'=>'Italian','cs'=>'Czech','la'=>'Latvian'];

$supportedLanguages = ['en'=>'English','de'=>'German'];

// Prepare SQL statements

$insert = "INSERT INTO users (chatid, language) VALUES (:chatid, :language)";
$select = 'SELECT language FROM users WHERE chatid=:chatid';
$update = "UPDATE users SET language =:language WHERE chatid = :chatid";
$stmtUPDATE = $file_db->prepare($update);
$stmtINSERT = $file_db->prepare($insert);
$stmtSELECT = $file_db->prepare($select);
?>
