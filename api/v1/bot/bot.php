<?php

class Bot
{
    private $bot;
    private $user;
    private $webhookUrl;

    private $info;
    private $type;
    private $item;
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function setBot($bot)
    {
        $this->bot = $bot;
        $this->getWebhookUrl();
    }

    public function getWebhookUrl()
    {
        $webhook = "";
        $sql = "SELECT `webhook` FROM `bots` WHERE `bot_id` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->bot);
        $stmt->execute();
        $stmt->bind_result($webhook);
        $stmt->fetch();
        $stmt->close();

        $this->webhookUrl = $webhook;
    }


    public function updateInfo()
    {
        $this->info = [
            'status' => 'ok',
            'code' => 0,
            'data' => [
                'type' => $this->type,
                'user' => $this->user,
                'item_id' => $this->item
            ],
        ];
    }

    // notify webhook by ping
    public function send($type, $user, $itemId)
    {
        $this->type = $type;
        $this->user = $user;
        $this->item = $itemId;
        $this->updateInfo();
        $this->sendWebhook();
    }

    // notify webhook (sendWebhook)
    public function sendWebhook()
    {
        if ($this->isDisabled($this->bot)) {
            return;
        }
        $payload = json_encode($this->info);

        $ch = curl_init($this->webhookUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    // Check if a bot belongs to a user
    public function botBelongsToUser($botId, $userId)
    {
        $count = '';
        $sql = "SELECT COUNT(*) AS count FROM bots WHERE bot_id = ? AND userid = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $botId, $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
    
    public function disableBot($botId, $set = false)
    {
        if ($this->botBelongsToUser($botId, $_SESSION['userId'])) {
            if ($set) {
                $status = 'active';
            } else {
                $status = 'blocked';
            }
            $sql = "UPDATE users SET status = ? WHERE idusers = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $status, $botId);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isDisabled($botId)
    {
        $status = '';
        $sql = 'SELECT status FROM users WHERE idusers = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $botId);

        if ($stmt->execute()) {
            $stmt->bind_result($status);
            $stmt->fetch();
            $stmt->close();

            return $status === 'blocked';
        }

        return false;
    }



    public function getUserBots($userId, $botId = false)
    {

        if ($botId) {
            if (!$this->botBelongsToUser($botId, $userId)) {
                return [];
            }

            $sql = "SELECT b.*, u.uidusers AS username, CONCAT(u.usersFirstname, ' ', u.usersSecondname) AS name, 
            u.profile_picture, u.status, u.bio, s.session_id, ak.chat_auth as chat_token, ak.token as bot_token
            FROM bots AS b
            JOIN users AS u ON u.idusers = b.bot_id
            JOIN auth_key AS ak ON ak.user = u.idusers
            JOIN session AS s ON s.user_id = u.idusers
            WHERE b.userid = ? AND b.bot_id = ?
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $botId);
        } else {
            $sql = "SELECT b.*, u.uidusers AS username, CONCAT(u.usersFirstname, ' ', u.usersSecondname) AS name, 
            u.profile_picture, u.status, u.bio, s.session_id, ak.chat_auth as chat_token, ak.token as bot_token
            FROM bots AS b
            JOIN users AS u ON u.idusers = b.bot_id
            JOIN auth_key AS ak ON ak.user = u.idusers
            JOIN session AS s ON s.user_id = u.idusers
            WHERE b.userid = ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $bots = array();
        while ($row = $result->fetch_assoc()) {
            $bot = array(
                'id' => $row['id'],
                'bot_id' => $row['bot_id'],
                'name' => $row['name'],
                'username' => $row['username'],
                'description' => $row['bio'],
                'icon' => $row['profile_picture'],
                'status' => $row['status'],
                'userid' => $row['userid'],
                'webhook' => $row['webhook'],
                'auth_token' => $row['session_id'],
                'bot_token' => $row['bot_token'],
                'chat_token' => $row['chat_token']
            );
            $bots[] = $bot;
        }

        $stmt->close();

        return $bots;
    }
}

$bot = new Bot();
