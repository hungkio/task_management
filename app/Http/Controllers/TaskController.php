<?php

namespace App\Http\Controllers;

use App\DataTables\TaskDataTable;
use App\Domain\Admin\Models\Admin;
use App\Http\Requests\Admin\TaskRequest;
use App\Imports\TasksImport;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TaskController
{
    use AuthorizesRequests;

    public function index(TaskDataTable $dataTable)
    {
        $this->authorize('view', Tasks::class);

        return $dataTable->render('admin.tasks.index');
    }

    public function create(): View
    {
        $this->authorize('create', Tasks::class);
        $QAs = Admin::whereHas('roles', function (Builder $subQuery) {
            $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
        })->get();

        $editors = Admin::whereHas('roles', function (Builder $subQuery) {
            $subQuery->where(config('permission.table_names.roles').'.name', 'editor');
        })->get();
        return view('admin.tasks.create', compact('QAs', 'editors'));
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Tasks::class);
        $data = $request->all();
        $data['estimate'] = Admin::ESTIMATE[$data['level']];
        $data['estimate_QA'] = Admin::ESTIMATE_QA[$data['level']];
        $task = Tasks::create($data);

        flash()->success(__('Case ":model" đã được tạo thành công !', ['model' => $task->title]));

        return intended($request, route('admin.tasks.index'));
    }

    public function edit(Tasks $task): View
    {
        $this->authorize('update', $task);
        $QAs = Admin::whereHas('roles', function (Builder $subQuery) {
            $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
        })->get();

        $editors = Admin::whereHas('roles', function (Builder $subQuery) {
            $subQuery->where(config('permission.table_names.roles').'.name', 'editor');
        })->get();
        return view('admin.tasks.edit', compact('task', 'QAs', 'editors'));
    }

    public function update(Tasks $task, TaskRequest $request)
    {
        $this->authorize('update', $task);

        $data = $request->all();
        $data['estimate'] = Admin::ESTIMATE[$data['level']];
        $data['estimate_QA'] = Admin::ESTIMATE_QA[$data['level']];

        if (@$data['redo']) {
            $data['redo'] = $task->redo ?? json_encode([]);
        } else {
            $data['redo'] = null;
        }

        $task->update($data);

        flash()->success(__('Case ":model" đã được cập nhật !', ['model' => $task->name]));

        return intended($request, route('admin.tasks.index'));
    }

    public function destroy(Tasks $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => __('Case đã xóa thành công !'),
        ]);
    }


    public function cron()
    {
        $client = getDropboxClient();
        $parentPath = '1.Working';
        $today = Carbon::now()->subDay(1);
        $currentMonthText = $today->format('F');
        $currentMonthNumber = $today->format('m');
        $currentDay = $today->format('d');

//        $currentMonthText = 'June';
//        $currentMonthNumber = '06';
//        $currentDay = '06';

        $mapCustomer = [
            '06. JD' => [
                'Originals',
                $currentMonthText,
                "$currentMonthNumber $currentDay"
            ],
            '01. Tonika' => [
                'Originals',
                $currentMonthText,
                ""
            ],
            '02. DCL' => [
                'NEW JOB',
                $currentMonthText,
                "$currentMonthNumber $currentDay"
            ],
            '03. CBA' => [
                'Originals',
                $currentMonthText,
                "$currentMonthNumber $currentDay"
            ],
            '04. NK' => [
                'Originals',
                $currentMonthText,
                "$currentMonthNumber $currentDay"
            ],
            '05. ES' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay"
            ],
            '08. AL' => [
                'Originals',
                $currentMonthText,
                "$currentMonthNumber $currentDay"
            ],
            '09. CL' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay"
            ],
            '10.MCC' => [
                'NEW JOB',
                $currentMonthText,
                "$currentMonthNumber $currentDay",
            ],
            '11. CH' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay",
            ],
            '12. BRAUS(PM)' => [
                'Originals',
                $currentMonthText,
                "$currentMonthNumber $currentDay",
            ],
            '13.KS' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay",
            ],
            '14.JG' => [
                'New Job Judy',
                "",
                "$currentMonthNumber $currentDay",
            ],
            '15.RK' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay",
            ],
            '18. DRJ' => [
                'NEW JOB',
                "",
                "$currentMonthNumber $currentDay",
            ],
            '19.MX' => [
                'Originals',
                "",
                "$currentMonthNumber $currentDay",
            ],

        ];

        $list = @$client->listFolder($parentPath)['entries'];
        foreach ($list as $sub1) {
            try {
                $customer = $sub1['name']; // customer
                $newjob = $mapCustomer[$customer][0] ?? '';
                $monthText = $mapCustomer[$customer][1] ? $mapCustomer[$customer][1] . '/' : '/';
                $date = $mapCustomer[$customer][2] ?? '';

                if (!$newjob) {
                    continue;
                }
                if ($date) {
                    $tasks = $client->listFolder("$parentPath/$customer/$newjob/$monthText$date")['entries'];
                } else {
                    $tasks = $client->listFolder("$parentPath/$customer/$newjob")['entries'];
                }

                foreach ($tasks as $task) {
                    $tag = $task['.tag'];
                    if ($tag == 'file') {
                        continue;
                    }
                    $taskName = $task['name'];
                    $taskPath = $task['path_display'];
                    if ($date) {
                        $taskRecord = $client->listFolder("$parentPath/$customer/$newjob/$monthText$date/$taskName")['entries'];
                    } else {
                        $taskRecord = $client->listFolder("$parentPath/$customer/$newjob/$taskName")['entries'];
                    }

                    if ($taskRecord && $taskRecord[0]['.tag'] == 'folder') {
                        foreach ($taskRecord as $record) {
                            $recordName = $record['name'];
                            $recordPath = $record['path_display'];
                            $casePath = str_replace('/1.Working', '', $recordPath);
                            $caseName = "$customer/$date/$taskName/$recordName";
                            if ($date) {
                                $record_entries = $client->listFolder("$parentPath/$customer/$newjob/$monthText$date/$taskName/$recordName")['entries'];
                            } else {
                                $record_entries = $client->listFolder("$parentPath/$customer/$newjob/$taskName/$recordName")['entries'];
                            }
                            if ($record_entries && $record_entries[0]['.tag'] == 'folder') {
                                foreach ($record_entries as $entry) {
                                    $child_folder = $client->listFolder($entry['path_display'])['entries'];
                                    $childName = $entry['name'];
                                    $childPath = $entry['path_display'];
                                    $casePath = str_replace('/1.Working', '', $childPath);
                                    $caseName = "$customer/$date/$taskName/$recordName/$childName";
                                    $countRecord = count($child_folder);
                                    if ($customer == '02. DCL') { //change case tách thành tên thư mục bên trong
                                        $taskName = $recordName;
                                    }
                                    $this->createNewTask($customer, $caseName, $casePath, $countRecord, $taskName);
                                }
                            } else {
                                $countRecord = count($record_entries);
                                if ($customer == '09. CL') { //change case tách thành tên thư mục bên trong
                                    $taskName = $recordName;
                                }
                                $this->createNewTask($customer, $caseName, $casePath, $countRecord, $taskName);
                            }
                        }
                    } else {
                        $caseName = "$customer/$date/$taskName";
                        $casePath = str_replace('/1.Working', '', $taskPath);
                        $countRecord = count($taskRecord);
                        $this->createNewTask($customer, $caseName, $casePath, $countRecord, $taskName);
                    }

                }
            } catch (\Exception $exception) {
                Log::notice($exception->getMessage());
                continue;
            }
        }
    }

    public function createNewTask($customer, $caseName, $casePath, $countRecord, $taskName)
    {
        // set level
        $level = null;
        $estimate = null;
        $estimate_QA = null;
        foreach (Admin::CUSTOMER_LEVEL as $key => $value) {
            if (stripos($customer, $key) !== false) {
                $level = $value;
                $estimate = Admin::ESTIMATE[$value];
                $estimate_QA = Admin::ESTIMATE_QA[$value];
                break;
            }
        }

        $task = Tasks::where('name', $caseName)->whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->first();
        if ($task) {
            $task->update([
                'path' => $casePath,
                'countRecord' => $countRecord,
                'case' => $taskName,
                'customer' => $customer,
                'level' => $level,
                'estimate' => $estimate,
                'estimate_QA' => $estimate_QA,
            ]);
        } else {
            Tasks::create([
                'name' => $caseName,
                'path' => $casePath,
                'countRecord' => $countRecord,
                'case' => $taskName,
                'customer' => $customer,
                'level' => $level,
                'estimate' => $estimate,
                'estimate_QA' => $estimate_QA,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new TasksImport, $request->file);
            flash()->success(__('Đã import danh sách case!'));
        } catch (\Exception $exception) {
            flash()->success($exception->getMessage());
        }

        return back();
    }
}
