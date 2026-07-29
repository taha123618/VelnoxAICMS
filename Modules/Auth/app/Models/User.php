<?php

namespace Modules\Auth\Models;

// use Modules\Auth\Database\Factories\UserFactory;

use App\Models\Tenant;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Database\Factories\UserFactory;
use Modules\Auth\Policies\UserPolicy;
use Modules\Media\Models\Folder;
use Modules\Visits\Traits\CanVisit;
use Spatie\Permission\Traits\HasRoles;

#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable
{
    use CanVisit;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasUlids;
    use Notifiable;

    // use MustVerifyEmail;
    /**
     * The attributes that are mass assignable.
     */
    #[\Override]
    protected $fillable = [];

    protected function getNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function getMainRole(): ?Model
    {
        return $this->roles->first();
    }

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->whereAny(['first_name', 'last_name', 'email'], 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed): void {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    protected function getAuthorizationAttribute(): array
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this),
        ];
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class)->withTimestamps();
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
