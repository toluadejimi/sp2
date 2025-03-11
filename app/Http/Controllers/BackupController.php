<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class BackupController extends Controller
{
    public function runDatabaseBackup()
    {
        Artisan::call('backup:run', [
            '--only-db' => true,
        ]);

        $directoryPath = '/var/www/html/enkpayapp.enkwave.com/storage/app/ENKPAY';
        $files = collect(glob($directoryPath . '/*.zip'));
        if ($files->isEmpty()) {
            return response()->json([
                'message' => 'No backup files found to send.',
            ], 404);
        }

        try {
            $fileLinks = [];

            // Generate downloadable links for each file
            foreach ($files as $file) {
                $fileName = basename($file);
                $fileLink = url('storage/ENKPAY/' . $fileName); // Create a public URL
                $fileLinks[] = $fileLink;
            }

            $linksText = implode("\n", $fileLinks);

            Mail::raw("Please find the backup files below:\n\n$linksText", function ($message) use ($fileLinks) {
                $message->to(['toluadejimi@gmail.com', 'ebukamiracle35@gmail.com'])
                    ->subject('ENKPAY Backup Database');
            });

            return response()->json([
                'message' => 'Backup files sent successfully.',
                'fileLinks' => $fileLinks, // Optional: return the generated links in the response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send backup files.',
                'error' => $e->getMessage(),
            ], 500);
        }


        $output = Artisan::output();
        return response()->json([
            'message' => 'Database backup executed successfully.',
            'output' => $output,
        ]);
    }


    public function sendBackupFiles()
    {
        $directoryPath = '/var/www/html/enkpayapp.enkwave.com/storage/app/ENKPAY';
        $files = collect(glob($directoryPath . '/*.zip'));
        if ($files->isEmpty()) {
            return response()->json([
                'message' => 'No backup files found to send.',
            ], 404);
        }

        try {
            $fileLinks = [];

            // Generate downloadable links for each file
            foreach ($files as $file) {
                $fileName = basename($file);
                $fileLink = url('storage/ENKPAY/' . $fileName); // Create a public URL
                $fileLinks[] = $fileLink;
            }

            $linksText = implode("\n", $fileLinks);

            Mail::raw("Please find the backup files below:\n\n$linksText", function ($message) use ($fileLinks) {
                $message->to(['toluadejimi@gmail.com', 'ebukamiracle35@gmail.com'])
                    ->subject('ENKPAY Backup Database');
            });

            return response()->json([
                'message' => 'Backup files sent successfully.',
                'fileLinks' => $fileLinks, // Optional: return the generated links in the response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send backup files.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}





