<?php

namespace Modules\Menu\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Modules\Menu\Policies\MenuPolicy;

// use Modules\Menu\Database\Factories\MenuFactory;

#[UsePolicy(MenuPolicy::class)]
class Menu extends BaseModel
{
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('sort_order', 'asc');
    }

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where('name', 'like', "%$search%");
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

    // public static function extractMenuIdsFromContent($data): array
    // {
    //     $menuIds = [];

    //     if (is_array($data)) {
    //         foreach ($data as $key => $value) {
    //             if ($key === 'menuId') {
    //                 $menuIds[] = $value;
    //             }
    //             if (is_array($value) || is_object($value)) {
    //                 $menuIds = array_merge($menuIds, static::extractMenuIdsFromContent((array)$value));
    //             }
    //         }
    //     } elseif (is_object($data)) {
    //         return static::extractMenuIdsFromContent((array)$data);
    //     }

    //     return $menuIds;
    // }
}
