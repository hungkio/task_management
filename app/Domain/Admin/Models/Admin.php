<?php

declare(strict_types=1);

namespace App\Domain\Admin\Models;

use App\Domain\Admin\Presenters\AdminPresenter;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements HasMedia
{
    use Notifiable;
    use AdminPresenter;
    use InteractsWithMedia;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'is_online', 'level', 'is_ctv', 'lock_task'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const LEVEL = [
        'SE_PR', 'SE', 'SE_DE', 'SE_FL', 'SE_TW', 'DTD', 'AV', 'DBAV', 'PRT', 'Basic_rt', 'FL', 'FLB', 'FLP',
        'VT', 'VTP', 'VTP360', 'HEN', 'INV', 'Floor', 'EXT', 'PANO', 'HI_END'
    ];

    const ESTIMATE = [
        'INV' => 1,
        'SE' => 3,
        'SE_DE' => 3,
        'SE_PR' => 5,
        'SE_FL' => 6,
        'SE_TW' => 10,
        'DTD' => 20,
        'DBAV' => 60,
        'AV' => 30,
        'PRT' => 10,
        'EXT' => 120,
        'PANO' => 10,
        'HEN' => 30,
        'HI_END' => 15,
        'VT' => 180,
        'VTP' => 300,
        'VTP360' => 300,
        'FL' => 180,
        'FLB' => 180,
        'FLP' => 300,
        'Floor' => 60,
        'Basic_rt' => 10
    ];

    const ESTIMATE_QA = [
        'INV' => 0.5,
        'SE' => 2,
        'SE_DE' => 2,
        'SE_PR' => 3,
        'SE_FL' => 3,
        'SE_TW' => 5,
        'DTD' => 10,
        'DBAV' => 30,
        'AV' => 15,
        'PRT' => 5,
        'EXT' => 60,
        'PANO' => 5,
        'HEN' => 15,
        'HI_END' => 8,
        'VT' => 90,
        'VTP' => 150,
        'VTP360' => 150,
        'FL' => 90,
        'FLB' => 90,
        'FLP' => 150,
        'Floor' => 30,
        'Basic_rt' => 5
    ];

    const BAD = [
        'BAD_QUY TRÌNH','BAD_MÀU SẮC', 'BAD_ÁNH SÁNG','BAD_THÁI ĐỘ','BAD_KH','BAD_STYLES','BAD_SKY,TV','EXCELLENT!','JOB KO ĐẠT'
    ];

    const BAD_FEE = [
        'BAD_QUY TRÌNH' => -50000,
        'BAD_MÀU SẮC' => -50000,
        'BAD_ÁNH SÁNG' => -50000,
        'BAD_THÁI ĐỘ' => -50000,
        'BAD_KH' => -100000,
        'BAD_STYLES' => -100000,
        'BAD_SKY,TV' => -100000,
        'EXCELLENT!' => 100
    ];

    const CUSTOMER_LEVEL = [
        '01. Tonika' => 'SE_DE',
        '02. DCL' => 'SE_FL',
        '03. CBA' => 'SE',
        '04. NK' => 'SE_FL',
        '05. ES' => 'SE',
        '06. JD' => 'SE',
        '07. RT' => 'INV',
        '08. AL' => 'SE_PR',
        '09. CL' => 'SE',
        '11. CH' => 'SE_FL',
        '13. KS' => 'SE_PR',
        '14. JG' => 'SE',
        '15. RK' => 'SE',
        'MG' => 'SE',
        'TJ' => 'SE',
        'BR-A1' => 'SE_FL',
    ];


    // Editor, QA, CTV
    const COST = [
        'INV' => [2000,400,0],
        'SE' => [3500,700,8000],
        'SE_DE' => [3500,700,8000],
        'SE_PR' => [4500,900,10000],
        'SE_FL' => [4500,900,10000],
        'SE_TW' => [10000,2000,10000],
        'DTD' => [60000,12000,60000],
        'DBAV' => [110000,5000,110000],
        'AV' => [50000,2000,50000],
        'PRT' => [10000,2000,10000],
        'EXT' => [200000,0,0],
        'PANO' => [10000,1700,10000],
        'HEN' => [10000,800,10000],
        'HI_END' => [5000,400,5000],
        'VT' => [0,0,250000],
        'VTP' => [0,0,400000],
        'VTP360' => [0,0,1100000],
        'FL' => [0,0,300000],
        'FLB' => [0,0,400000],
        'FLP' => [0,0,500000],
        'Floor' => [0,0,0],
        'Basic_rt' => [10000,1000,0],
    ];

    const PRIORITY = [
        'SE_FL' => 5,
        'SE_TW' => 5,
        'SE_DE' => 4,
        'SE' => 3,
        'SE_PR' => 3,
        'INV' => 2,
        'DTD' => 2,
        'AV' => 2,
        'DBAV' => 2,
        'Basic_rt' => 2,
        'VT' => 1,
        'VTP' => 1,
        'VTP360' => 1,
        'FL' => 1,
        'FLB' => 1,
        'FLP' => 1,
        'HEN' => 0,
        'PRT' => 0,
        'EXT' => 0,
    ];

    // double check people
    const DBC_PEOPLE = [
        'Luongtd', 'Longpt', 'room', 'hunghv'
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/backend/global_assets/images/placeholders/placeholder.jpg');
    }

    public function QATasks()
    {
        return $this->hasMany(Tasks::class, 'QA_id')->whereDate('created_at', Carbon::today())->whereIn('status', [Tasks::TESTING, Tasks::REJECTED]);
    }

    public function QAMonthTasks()
    {
        return $this->hasMany(Tasks::class, 'QA_id')->whereMonth('created_at', Carbon::today());
    }

    public function EditorMonthTasks()
    {
        return $this->hasMany(Tasks::class, 'editor_id')->whereMonth('created_at', Carbon::today());
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
