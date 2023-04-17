<?php
//echo 'Перевірка клас підключено';


class BOT_WorkClassAll {
//класс звідки будемо брати всі функції

    public function setWebhook($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.BOT_TOKEN.'/setWebhook');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'url' => $url,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response;
    }

    //під'єднуємося до бази даних за допомогую PDO
    public function Bot_conn_base($BOT_host,$BOT_port,$BOT_base_name,$BOT_base_user,$BOT_base_pass,$BOT_table_name){
        try {
                $dsn = "mysql:host={$BOT_host};dbname={$BOT_base_name};charset=utf8";
                if ($BOT_port) {
                    $dsn .= ";port={$BOT_port}";
                }        
                $conn = new PDO($dsn, $BOT_base_user , $BOT_base_pass ); 
                return $conn;       
            }catch (PDOException $e) {
                echo "Could not connect to database: " . $e->getMessage();  
                return;      
            }
        }



// формуємо повідомлення відповідь користувачу і передаємо в функцію надсилання в CHAT BOT та записуємо повідомлення користувача до бази даних
public function processMessage($message,$connect) {
    if (isset($message)) {
        $text = implode(', ', array_filter($message, function ($key) {
            return $key !== 'update_id';
        }, ARRAY_FILTER_USE_KEY));
    } else {
        $text = '';
    }

    //$text = implode(", ", array_keys($message));

    $chat_id = $message['chat']['id'];
    $user_name = $message['from']['first_name'] . ' ' . $message['from']['last_name'];

    // Вставляємо запис про повідомлення користувача в базу даних
    $query_insert_user = "INSERT INTO Bot_user_conn (Bot_user_Name, Bot_user_text,Bot_user_chat_id) VALUES ('$user_name', '$text','$chat_id')";
    $stmt = $connect->prepare($query_insert_user);
    $stmt->execute(); 

    // Відправляємо відповідь користувачу з іменем користувача
    $this->sendMessage($chat_id, "Доброго дня, " . $user_name . "! Ваше повідомлення-->>: " . $text);
}



public function processMessage1($message, $connect)
{
    //$TESTTTTTTT = implode(", ", array_keys($message));

    if (isset($message['text'])) {
        $text = $message['text'];
    } else {
        // Якщо повідомлення не містить текст, ігноруємо його
        return;
    }

    // Перевіряємо, чи повідомлення було надіслане в груповий чат
    if (isset($message['chat']['type']) && $message['chat']['type'] === 'group') {
        // Перевіряємо, чи повідомлення було надіслане від користувача групи
        if (isset($message['from']['id'])) {
            $chat_id = $message['chat']['id'];
            $user_id = $message['from']['id'];
            $user_name = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
            
            // Вставляємо запис про повідомлення користувача в базу даних
            $query_insert_user = "INSERT INTO Bot_user_conn (Bot_user_Name, Bot_user_text, Bot_user_chat_id) VALUES (?, ?, ?)";
            $stmt = $connect->prepare($query_insert_user);
            $stmt->bindParam(1, $user_name);
            
            /*$TESt1=$TESTTTTTTT.'+'.$text;
            $stmt->bindParam(2, $TESt1);*/
            $stmt->bindParam(2, $text);

            $stmt->bindParam(3, $chat_id);
            $stmt->execute();
            
            // Отримуємо ID повідомлення, щоб відповісти тільки користувачеві, який надіслав повідомлення
            $message_id = $message['message_id'];
            
            // Відправляємо відповідь користувачу з іменем користувача та ID повідомлення
            $this->sendMessage($chat_id, "Доброго дня, " . $user_name . "! Ваше повідомлення (ID: " . $message_id . "): " . $text, $message_id);
        }
    } else {
        // Якщо повідомлення було надіслане не з групового чату, оброблюємо його як звичайне повідомлення
        $chat_id = $message['chat']['id'];
        $user_name = $message['from']['first_name'] . ' ' . $message['from']['last_name'];

        // Вставляємо запис про повідомлення користувача в базу даних
        $query_insert_user = "INSERT INTO Bot_user_conn (Bot_user_Name, Bot_user_text, Bot_user_chat_id) VALUES (?, ?, ?)";
        $stmt = $connect->prepare($query_insert_user);
        $stmt->bindParam(1, $user_name);

         /*$TESt1=$TESTTTTTTT.'+'.$text;
            $stmt->bindParam(2, $TESt1);*/
        $stmt->bindParam(2, $text);

        $stmt->bindParam(3, $chat_id);
        $stmt->execute();
        
        // Отрімуємо ID повідомлення, щоб відповісти користувачеві
    $message_id = $message['message_id'];
    // Відправляємо відповідь користувачу з іменем користувача та ID повідомлення
    $this->sendMessage($chat_id, "Доброго дня, " . $user_name . "! Ваше повідомлення (ID: " . $message_id . "): " . $text, $message_id);
}
}



//функцію надсилання в CHAT BOT повідомлень за унікальним chat_id користувача
     public function sendMessage($chat_id, $text) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'chat_id' => $chat_id,
            'text' => $text,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response;
    }
        

}






?>