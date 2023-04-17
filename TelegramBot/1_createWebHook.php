<body class=cl_createWebHook_body>
<link rel="stylesheet" href="/css/css_index.css"> 
<?php
include '../config/config.php';
include '../Bot_class/bot_all_class.php';

define('BOT_TOKEN', "$conf_BOT_TOKEN");
define('WEBHOOK_URL', "$conf_WEBHOOK_URL");

$BOT_ClassAll=new BOT_WorkClassAll();

echo  "<a>УРА!!!!!!! webhook до Telegram підключено</a><br>";
$setWebhook1= ($BOT_ClassAll->setWebhook(WEBHOOK_URL));
echo  "<a>".$setWebhook1."</a>";

?>
</body>