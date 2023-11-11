# Discord-Message-Manager
The Discord Message Manager is a PHP script that simplifies the management of your Discord messages. Whether you want to archive, delete, or analyze your chat history, this script offers the tools you need. It lets you save messages in various formats and choose whether to keep or delete them.

# Configuration Settings
Before using the Discord Message Manager, make sure to update the following configuration settings in the index.php file to suit your needs:

- `$token` -> https://stackoverflow.com/questions/71497839/how-discord-store-token
- `$file` -> Define the format in which you want to save messages (e.g., 'txt', 'json', 'csv').
- `$delete` -> Set to 'true' if you want to delete messages; set to 'false' to keep them.
- `$save` -> Set to 'true' to save messages; set to 'false' to skip saving.
- `$saveFile` -> Set to 'true' to save photos, videos, and files; set to 'false' to exclude them.
- `$conversationId` -> Set the ID of the channel/conversation where you want to delete/save messages

# Additional information
Use this script with caution, especially when deleting messages, as the process is irreversible.
Keep your Discord API token private and do not share it publicly.

This script offers a flexible solution for managing your Discord messages, making it easy to tailor the conversation management to your specific requirements.
