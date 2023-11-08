<?php 
class SaveManager {
    private $file;
    private $randomName;

    public function __construct($file) {
        $this->file = $file;
        $this->randomName = $this->randomNameGenerator();
    }

    public function save($messages, $saveFile = false)
    {  
        $this->saveMessages($messages['messages']);
    
        if ($saveFile) {
            $this->saveAttachments($messages);
        }
    }
    
    public function saveMessages($messages)
    {
        switch($this->file){
            case 'json':
                $this->saveJson($messages);
                break;
            case 'txt':
                $this->saveTxt($messages);
                break;
            default:
                $this->saveCsv($messages);
                break;
        }
    }
    
    public function saveAttachments($attachments)
    {
        if (empty($attachments['attachments'])) {
            print("No attachments to save" . PHP_EOL);
            return;
        }
        $this->saveFile($attachments);
    }

    public function saveJson($messages){
        $messages = json_encode($messages);

        file_put_contents("messages/{$this->randomName}.json", $messages);

        if(!file_exists("messages/{$this->randomName}.json")){
            print("Something went wrong with saving the file. Messages will not be deleted" . PHP_EOL);
            exit();
        }

        print("JSON file was saved. Check it out in messages/{$this->randomName}.json" . PHP_EOL);
    }

    public function saveCsv($messages){
        $csvFile = fopen("messages/{$this->randomName}.csv", "w");

        foreach ($messages as $row) {
            fputcsv($csvFile, $row);
        }

        fclose($csvFile);

        if(!file_exists("messages/{$this->randomName}.csv")){
            print("Something went wrong with saving the file. Messages will not be deleted" . PHP_EOL);
            exit();
        }

        print("Csv file was saved. Check it out in messages/{$this->randomName}.csv" . PHP_EOL);
    }

    public function saveTxt($messages){
        $file = fopen("messages/{$this->randomName}.txt", "w");

        if(!$file){
            echo "Something went wrong with saving the file. Messages will not be deleted" . PHP_EOL;
            exit();            
        }

        foreach($messages as $message){
            $line = "{$message['username']} | {$message['message']} | {$message['timestamp']}" . PHP_EOL;
            fwrite($file, $line);
        }

        print("Txt file was saved. Check it out in messages/{$this->randomName}.txt" . PHP_EOL);

        fclose($file);
    }

    public function saveFile($attachments){
        print("Starting save attachments" . PHP_EOL);

        $baseDir = "messages/file/{$this->randomName}";

        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        foreach($attachments as $attachment){
            $userDir = "{$baseDir}/{$attachment['username']}";

            if (!is_dir($userDir)) {
                mkdir($userDir, 0777, true);
            }
        
            $filePath = "{$userDir}/{$this->randomNameGenerator()}{$attachment['filename']}";
            $fileContent = file_get_contents($attachment['url']);

            if (file_put_contents($filePath, $fileContent) === false) {
                print("Something went wrong while saving the file" . PHP_EOL);
            }
        }
        print("Files saved in {$baseDir}" . PHP_EOL);
    }

    public function randomNameGenerator(){
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, 12);
    }
}