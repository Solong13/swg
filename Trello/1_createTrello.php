<body class=cl_createTrello.php>
<link rel="stylesheet" href="/css/css_index.css"> 

<?php
//include '../config/config.php';
//include '../Bot_class/bot_all_class.php';


$BOT_Trello_Token_API = "ATATT3xFfGF0m3pHtqO4ur4aNabsmHgEvyNqP3q6X6cak978yu63WicaWoBNFU-pXMcC9NVIKA2_3tdc7g-8jNzrqsh4Ytgih5GNT-teIUuhwK8wtVxO4-il2PaKoMHZZ0hKfzQTvlT0ZPvNHUMH8XPo4CfSWZwj0Pb83GTAqLF9X2O-WRHWCKg=14EDB774";
$listName = 'InProgress11';
$BOT_Trello_boardId = 'Ep1HoTt6';

$url = "https://api.trello.com/1/boards/{$BOT_Trello_boardId}/lists?key={$BOT_Trello_Token_API}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$lists = json_decode($response, true);

foreach ($lists as $list) {
    echo $list['name'] . "\n";
    echo "================\n";
}

?>


</body>