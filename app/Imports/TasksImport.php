<?php

namespace App\Imports;

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
                $customer = $row[3];
                $level = $row[4];
                $estimate = Admin::ESTIMATE[$level] ?? null;
                $priority = @Admin::PRIORITY[$level] ?? 0;
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
                $instruction = $row[11];

                $start_at = null;
                if ($editor) {
                    $start_at = date("Y-m-d H:i");
                }

                $QA_start = null;
                if ($QA) {
                    $data['QA_start'] = date("Y-m-d H:i");
                }

                Tasks::create([
                    'name' => $caseName,
                    'path' => $casePath,
                    'estimate' => $estimate,
                    'estimate_QA' => $estimate/2,
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
                ]);
            }
        }, 1);


    }
}
