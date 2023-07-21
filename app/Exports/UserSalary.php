<?php

namespace App\Exports;

use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserSalary implements FromView, WithTitle
{
    protected $from;
    protected $to;
    protected $user;

    public function __construct($from, $to, $user)
    {
        $this->from = $from;
        $this->to = $to;
        $this->user = $user;
    }

    public function view(): View
    {
        list($salaries, $qualities, $deadline) = $this->getUserSalaries($this->user->id, $this->from, $this->to);
        return view('admin.reports.sub_salary', compact('salaries', 'qualities', 'deadline'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->user->email;
    }

    public function getUserSalaries($user_id, $start, $end)
    {
        $user = Admin::find($user_id);
        if (!$user) {
            return [[], [], []];
        }
        $currentRoleName = $user->getRoleNames()[0];
        $salaries = [];
        $qualities = [];
        $on_time = 0;
        $late = 0;

        if ($currentRoleName == 'editor') {
            $conditionAssigner = "editor_id";
        } else {
            $conditionAssigner = 'QA_id';
        }
        $tasks = Tasks::where($conditionAssigner, $user->id)->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->orderBy('created_at', 'desc')->get();

        foreach ($tasks as $task) {
            if (!$task->level) {
                continue;
            }

            $time_spent = 0;
            if ($task->start_at && $task->end_at) {
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->start_at);
                $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->end_at);
                $time_spent = $end_at->diffInMinutes($start_at);
            }

            if ($currentRoleName == 'editor') {
                $estimate = $task->estimate;
            } else {
                $estimate = $task->estimate_QA;
            }

            if ($time_spent - ($estimate * $task->editor_check_num) <= 0) {
                $on_time++;
            } else {
                $late++;
            }

            if (array_key_exists($task->level, $salaries)) {
                $salaries[$task->level] += $task->editor_check_num;
                $qualities[$task->level] += $time_spent;
            } else {
                $salaries[$task->level] = $task->editor_check_num;
                $qualities[$task->level] = $time_spent;
            }
        }

        foreach ($salaries as $ax => $editor_check_num) {
            $costRole = 0; //editor
            if ($currentRoleName == 'QA') {
                $costRole = 1;
            } else if($user->is_ctv == 1) {
                $costRole = 2;
            }
            $cost = Admin::COST[$ax][$costRole];
            $salaries[$ax] = [
                'cost' => $cost*$editor_check_num,
                'editor_check_num' => $editor_check_num,
                'unitCost' => $cost,
            ];
        }

        if ($currentRoleName == "editor") {
            foreach ($qualities as $key => &$quantity) {
                $average = $salaries[$key]['editor_check_num'] ? round($quantity*60/$salaries[$key]['editor_check_num'], 2) : 0;
                $quantity = gmdate("H:i:s", $average);
            }
        } else {
            $qualities = [];
        }

        return [$salaries, $qualities, [$on_time, $late]];
    }
}
