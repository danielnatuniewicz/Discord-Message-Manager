<?php
require_once 'manager/TokenManager.php';
require_once 'manager/MessageManager.php';
require_once 'manager/RequestManager.php';
require_once 'manager/SaveManager.php';

/**
    * Configuration Settings
    *
    * Please update these values as needed.
    *
    * - $token: Your Discord API token
    * - $conversationId: Set the ID of the channel where you want to delete/save messages
    * - $file: Set the format in which you want to save messages (e.g., 'txt', 'json', 'csv')
    * - $delete: Set to 'true' if you want to delete messages; 'false' to keep them
    * - $save: Set to 'true' to save messages; 'false' to not save
    * - $saveFile: Set to 'true' to save photos, videos, and files; 'false' to not save them
    *
    * Please configure the settings below.
*/

$token = "your token"; 
$conversationId = "conversation id";
$file = "csv"; 
$save = true;
$saveFile = true; 
$delete = false; 

/**
    * End of Configuration Settings
*/

$tokenManager = new TokenManager($token);
$requestManager = new RequestManager($tokenManager);
$messageManager = new MessageManager($requestManager);
$saveManager = new SaveManager($file);

$user = $requestManager->getUser();
$user = json_decode($user);

function run()
{
    global $conversationId;
    global $messageManager;
    global $save;
    global $saveFile;
    global $saveManager;
    global $delete;
    global $user;

    $allMessages = [];
    $lastMessage = false;

    $continue = true;

    while (true) {
        try {
            $messages = $messageManager->getMessages($conversationId, $lastMessage);
            $messages = json_decode($messages, true);
            $countMessages = count($messages);
            $lastMessage = $messages[$countMessages - 1]['id'];
            
            if(empty($messages)){
                break;
            }

            if (is_array($allMessages)) {
                array_push($allMessages, $messages);
            } else {
                $allMessages = $messages;
            }

            if ($countMessages < 100) {
                break;
            }
        } catch (Exception $e) {
            print('Something went wrong, it may not have loaded all messages. Do you want to continue? (yes/no)');

            $userResponse = readline();

            if(strtolower($userResponse) === "no"){
                $continue = false;
            }

            break;
        }
    }

    if(!$continue){
        print('Script has been stopped, sorry for our fault, check your settings, and try again :D');
        return;
    }

    if(empty($allMessages)){
        print('No messages were loaded. Please check your settings and try again');
        return;
    }

    $messages = $messageManager->parseArray($allMessages, $user);

    if($save){
        $saveManager->save($messages, $saveFile);
    }

    if($delete){
        $messageManager->deleteMessages($conversationId, $messages['user']);
    }
}

run();
