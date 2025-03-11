<?php

namespace App\Console\Commands;

use App\Models\PendingTransaction;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class PushTransfer extends Command
{
    protected $signature = 'send:pushtransfer';  // Command name
    protected $description = 'Transfer data from source to destination database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {




    }
}
