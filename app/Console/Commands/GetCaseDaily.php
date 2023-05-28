<?php

namespace App\Console\Commands;

use App\Http\Controllers\TaskController;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetCaseDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'case:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Case list daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // job that runs every minute from 9:00 to 16:59, Monday to Friday:
        // * 9-16 * * 1-5 echo 'Hello World'
        $taskController = new TaskController;
        $taskController->cron();
    }
}
