<?php
include '../config/config.php';
include '../Bot_class/bot_all_class.php';

/*Параметри */
define('BOT_TOKEN', "$conf_BOT_TOKEN");
/*підключаємо клас у якому усі функції*/
$BOT_ClassAll=new BOT_WorkClassAll();
/*викликаємо функцію підключення до бази даних */
$BOT_base_conn = $BOT_ClassAll->Bot_conn_base($BOT_host,$BOT_port,$BOT_base_name,$BOT_base_user,$BOT_base_pass,$BOT_table_name);

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['message'])) {
    /*опрацьовуємо повідомлнення з чату , записуємо до бази даних, та надаємо відповідь користувачу */
    $BOT_ClassAll->processMessage1($update['message'],$BOT_base_conn);
}

?>

