<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');

        $this->service = new Drive($this->client);
    }

    public function uploadFile($filePath, $fileName)
    {
        $fileMetadata = new DriveFile([
            'name' => $fileName
        ]);

        $content = file_get_contents($filePath);
        $file = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
        ]);

        return $file->getId();
    }
}
