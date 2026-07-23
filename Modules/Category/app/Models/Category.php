<?php

namespace Modules\Category\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Policies\CategoryPolicy;
use Modules\Page\Models\Page;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

// use Modules\Category\Database\Factories\CategoryFactory;

#[UsePolicy(CategoryPolicy::class)]
class Category extends BaseModel
{
    use HasRecursiveRelationships;
    use HasSlug;

    public function posts()
    {
        return $this->hasMany(Page::class)
            ->posts();
    }

    public function getUrl(bool $absolute = true)
    {

        return route(name: 'categories.show', parameters: [
            'category' => $this->slug,
        ], absolute: $absolute);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
            ->usingSeparator('-');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', "%$search%");
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
            'delete' => Gate::allows('delete', $this),
        ];
    }
}
