<?php

namespace App\Http\Controllers;

use App\DataTables\TaskDataTable;
use App\Http\Requests\Admin\TaskRequest;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

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
        return view('admin.tasks.create');
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Tasks::class);
        $data = $request->all();
        $task = Tasks::create($data);

        flash()->success(__('Case ":model" đã được tạo thành công !', ['model' => $task->title]));

        return intended($request, route('admin.tasks.index'));
    }

    public function edit(Tasks $task): View
    {
        $this->authorize('update', $task);

        return view('admin.tasks.edit', compact('task'));
    }

    public function update(Tasks $task, TaskRequest $request)
    {
        $this->authorize('update', $task);

        $task->update($request->all());

        flash()->success(__('Case ":model" đã được cập nhật !', ['model' => $task->name]));

        return intended($request, route('admin.tasks.index'));
    }


    public function updateStatus(Request $request)
    {
        $taskId = $request->input('id');
        $processStatus = $request->input('status');

        $task = Tasks::findOrFail($taskId);
        $task->status = $processStatus;
        $task->save();

        // Phản hồi JSON với thông tin cập nhật thành công
        return response()->json(['message' => 'Cập nhật trạng thái thành công'], 200);
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
                    $casePath = 'https://www.dropbox.com/home'.str_replace(' ', '%20', $taskPath);
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
