<?php

namespace App\Exports;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersSalary implements WithMultipleSheets
{
    use Exportable;

    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $users = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->whereHas('roles', function (Builder $subQuery) {
            $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
        })->get();

        foreach ($users as $user) {
            $sheets[] = new UserSalary($this->from, $this->to, $user);
        }

        return $sheets;
    }
}
