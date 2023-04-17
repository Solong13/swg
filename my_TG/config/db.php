<?php
include 'core/init.php';

static $db;
$db = new PDO("mysql:host=$db_host; dbname=$db_Name", "$db_Name", $db_password);
return $db;


//webhook correct url
//https://api.telegram.org/bot5628796296:AAGu5HwepHC2HCtn3GypxI0bCjMplhqAllw/setWebhook?url=https://test-oleksandr-kuzmenko.dev.yeducoders.com