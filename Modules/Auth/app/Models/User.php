<?php

namespace Modules\Auth\Models;


// use Modules\Auth\Database\Factories\UserFactory;

use Modules\Media\Models\Folder;
use Modules\Visits\Traits\CanVisit;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Gate;
use Modules\Auth\Policies\UserPolicy;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable
{
    use HasFactory;
    use HasUlids;
    use Notifiable;
    use HasRoles;
    use CanVisit;

    // use MustVerifyEmail;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];


    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function getMainRole(): ?Model
    {
        return $this->roles->first();
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny(['first_name', 'last_name', 'email'], 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this)
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
