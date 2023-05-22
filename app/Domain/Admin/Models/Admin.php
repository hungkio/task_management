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
        'first_name', 'last_name', 'email', 'password',
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
