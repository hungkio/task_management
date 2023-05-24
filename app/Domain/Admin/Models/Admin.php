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
        'first_name', 'last_name', 'email', 'password', 'is_online', 'level', 'is_ctv'
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
        'SE_PR', 'Se_DE', 'SE', 'SE_DE', 'SE_FL', 'SE_TW', 'DTD', 'AV', 'DBAV', 'PRT', 'Basic_rt', 'FL', 'FLB', 'FLP',
        'VT', 'VTP', 'VTP360', 'HEN', 'INV', 'Floor', 'EXT', 'PANO', 'HI_END'
    ];

    const ESTIMATE = [
        'INV' => 1,
        'SE' => 3,
        'Se_DE' => 3,
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

    const BAD = [
        'BAD_QUY TRÌNH','BAD_MÀU SẮC', 'BAD_ÁNH SÁNG','BAD_THÁI ĐỘ','BAD_KH','BAD_STYLES','BAD_SKY,TV','EXCELLENT!'
    ];

    const BAD_FEE = [
        'BAD_QUY TRÌNH' => -50,
        'BAD_MÀU SẮC' => -50,
        'BAD_ÁNH SÁNG' => -50,
        'BAD_THÁI ĐỘ' => -50,
        'BAD_KH' => -100,
        'BAD_STYLES' => -100,
        'BAD_SKY,TV' => -100,
        'EXCELLENT!' => 100
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
        return $this->hasMany(Tasks::class, 'QA_id')->whereDate('created_at', Carbon::today());
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
