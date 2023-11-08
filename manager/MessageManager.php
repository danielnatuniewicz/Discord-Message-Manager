<?php 
class MessageManager {
    private $tokenManager;
    private $requestManager;

    public function __construct(RequestManager $requestManager) {
        $this->requestManager = $requestManager;
    }

    public function getMessages($id, $lastMessage = false) {
        $url = "https://discord.com/api/v9/channels/{$id}/messages?limit=100";

        if($lastMessage){
            $url .= "&before={$lastMessage}";
        }

        $messages = $this->requestManager->sendRequest($url);

        return $messages;
    }

    public function deleteMessages($id, $messages) {
        print("Starting delete messages" . PHP_EOL);
    
        foreach($messages as $message){
            $url = "https://discord.com/api/v9/channels/{$id}/messages/{$message}";

            $this->requestManager->sendRequest($url, "DELETE");

            usleep(300000);
        }

        print("Messages was deleted" . PHP_EOL);
    }

    public function parseArray($allMessages, $user){
        $parsedMessages = [];

        foreach ($allMessages as $messages) {
            foreach($messages as $message){
                if($message['type'] != 0){
                    continue;
                }

                if($message['author']['id'] == $user->id){
                    $parsedMessages['user'][] = $message['id'];
                }
    
                if (!empty($message['attachments'])) {
                    foreach ($message['attachments'] as $attachment) {
                        $parsedMessages['attachments'][] = [
                            'url' => $attachment['url'],
                            'username' => $message['author']['username'],
                            'userId' => $message['author']['id'],
                            'filename' => $attachment['filename']
                        ];
                    }
                }
    
                $parsedMessages['messages'][] = [
                    'username' => $message['author']['username'],
                    'userId' => $message['author']['id'],
                    'timestamp' => date('Y-m-d H:i:s', strtotime($message['timestamp'])),
                    'message' => $message['content']
                ];
            }
        }

        return $parsedMessages;
    }
}