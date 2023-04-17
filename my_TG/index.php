<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Create a new Telegram Bot</title>
</head>
<body>
<div>
    <h1 style="text-align: center">API для створення</h1>
</div>
<div class="alert alert-primary" role="alert">
    <a href="#" >Webhook от Trello </a>
</div>


<?php
include 'db/db.php';

//Задаёт, какие ошибки PHP попадут в отчёт
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_setup_errors', 1);


// дод даних про користувача
function addDataAboutUser($db, $id_user,  $user_name,  $message){
    $sql = "INSERT INTO `TG_BOT` (`id_user`, `user_name`, `message`)
    VALUES (:id_user, :user_name, :message);";
    $addData = $db->prepare($sql);
    $addData->execute([
        'id_user' => $id_user,
        'user_name' => $user_name,
        'message' => $message
    ]);
}


// лог
$data = json_decode(file_get_contents('php://input'), TRUE); // весь ввод перенаправляем в $data
file_put_contents('log.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

// дані про користувача для запису
$name = $data['message']['from']['first_name']. ' ' .$data['message']['from']['last_name'];
$idUser = $data['message']['chat']['id'];
$mess = $data['message']['text'];


// обробка ручного написання, або натискання кнопок
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

//повідомлення користувачам нижній регістр
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

switch($message)
{
    case '/start':
        addDataAboutUser($db, $idUser, $name, $mess);
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Hello,'. ' ' .$name
        ];

        break;
    default:
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Something is wrong :('
        ];
}

//дод даних користувачу
$send_data['chat_id']  = $data['chat']['id'];
$res = sendTelegram($method, $send_data);


function sendTelegram($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot' . TG_TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}
?>
</body>
</html>


