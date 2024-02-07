<?php

namespace App\Imports;

use App\AX;
use App\Customers;
use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class TasksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        unset($rows[0]);

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row)
            {
                $caseName = $row[1];
                // check exist
                $isExist = Tasks::where('name', $caseName)->first();
                throw_if($isExist, \Exception::class, 'Tồn tại case trùng lặp!');

                $taskName = $row[2];
                $level = $row[4];
                $customer = Customers::where('name', $row[3])->first();
                throw_if(!$customer, \Exception::class, 'Không tồn tại khách hàng!');

                $ax = AX::where('name', $level)->first();
                throw_if(!$ax, \Exception::class, "Không tồn tại AX này: $level!");
                $level = $ax->name;

                $deadline = '';
                if ($customer->deadline) {
                    $time = date_create_from_format('H', str_replace('h', '', $customer->deadline));
                    if ($time) {
                        $deadline = $time->format('Y-m-d H:i');
                    }
                }

                $customer = $customer->name;

                $estimate = $ax->estimate_editor ?? 0;
                $estimateQA = $ax->estimate_QA ?? 0;
                $priority = $ax->priority?? 0;
                $status = 0;
                if (in_array($row[5], Tasks::STATUS)) {
                    $status = array_search($row[5], Tasks::STATUS);
                    if ($status == false) {
                        $status = 0;
                    }
                }
                $editorName = $row[6];
                $QAName = $row[7];
                $editor = Admin::whereHas('roles', function (Builder $subQuery) {
                    $subQuery->where(config('permission.table_names.roles').'.name', 'editor');
                })->where("email", $editorName)->first();

                $QA = Admin::whereHas('roles', function (Builder $subQuery) {
                    $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
                })->where("email", $QAName)->first();
                $countRecord = $row[8];
                $casePath = $row[9];
                $instruction = @$row[11];

                $start_at = null;
                if ($editor) {
                    $start_at = date("Y-m-d H:i");
                }

                $QA_start = null;
                if ($QA) {
                    $data['QA_start'] = date("Y-m-d H:i");
                }

                $caseName = str_replace(['/', '///'], ['|', '|'], $caseName);

                Tasks::create([
                    'name' => $caseName,
                    'path' => $casePath,
                    'estimate' => $estimate,
                    'estimate_QA' => $estimateQA,
                    'level' => $level,
                    'status' => $status,
                    'countRecord' => $countRecord,
                    'case' => $taskName,
                    'customer' => $customer,
                    'editor_id' => $editor->id ?? '',
                    'QA_id' => $QA->id ?? '',
                    'priority' => $priority,
                    'start_at' => $start_at,
                    'QA_start' => $QA_start,
                    'instruction' => $instruction,
                    'deadline' => $deadline,
                ]);
            }
        }, 1);


    }
}
