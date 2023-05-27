<?php

namespace App\Imports;

use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class TasksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        unset($rows[0]);
        foreach ($rows as $row)
        {
            $caseName = $row[1];
            $taskName = $row[2];
            $customer = $row[3];
            $level = $row[4];
            $estimate = Admin::ESTIMATE[$level] ?? null;
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
            })->whereRaw("concat(first_name, ' ', last_name) = '$editorName'")->first();

            $QA = Admin::whereHas('roles', function (Builder $subQuery) {
                $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
            })->whereRaw("concat(first_name, ' ', last_name) = '$QAName'")->first();
            $countRecord = $row[8];
            $casePath = $row[9];
            Tasks::updateOrCreate([
                'name' => $caseName,
                'created_at' => Tasks::whereDate('created_at', Carbon::today())->first()->created_at ?? null
            ], [
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
            ]);
        }
    }
}
