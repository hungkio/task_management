<?php

namespace App\Console\Commands;

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
        $client = getDropboxClient();
        $parentPath = '1.Working';
        $currentMonthText = Carbon::now()->format('F');
        $currentMonthNumber = Carbon::now()->format('m');
        $currentDay = Carbon::now()->format('d');

        $currentMonthText = 'July';
        $currentMonthNumber = '07';
        $currentDay = '21';

        $list = @$client->listFolder($parentPath)['entries'];
        foreach ($list as $sub1) {
            try {
                $customer = $sub1['name']; //
                $tasks = $client->listFolder("$parentPath/$customer/NEW JOB/$currentMonthText/$currentMonthNumber $currentDay")['entries'];
                foreach ($tasks as $task) {
                    $taskName = $task['name'];
                    $taskPath = $task['path_display'];
                    $taskRecord = $client->listFolder("$parentPath/$customer/NEW JOB/$currentMonthText/$currentMonthNumber $currentDay/$taskName")['entries'];

                    $caseName = "$customer/$currentMonthNumber $currentDay/$taskName";
                    $casePath = str_replace(' ', '%20', $taskPath);
                    $countRecord = count($taskRecord);
                    Tasks::updateOrCreate([
                        'name' => $caseName,
                    ], [
                        'path' => $casePath,
                        'countRecord' => $countRecord,
                        'date' => "$currentMonthNumber $currentDay",
                        'month' => $currentMonthText,
                        'case' => $taskName,
                        'customer' => $customer,
                    ]);
                }
            } catch (\Exception $exception) {
                Log::notice($exception->getMessage());
                continue;
            }
        }
    }
}
