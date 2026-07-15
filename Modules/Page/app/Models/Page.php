<?php

namespace Modules\Page\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use Spatie\Sluggable\HasSlug;
use Modules\Page\Enums\PageType;
use Modules\Layout\Models\Layout;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Gate;
use Modules\Visits\Traits\Visitable;
use Modules\Category\Models\Category;
use Modules\Page\Policies\PagePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Menu\Models\MenuItem;

#[UsePolicy(PagePolicy::class)]
class Page extends BaseModel
{
    use HasSlug;
    use Visitable;
    use SoftDeletes;

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'json',
        'data' => 'json',
        'type' => PageType::class,
        'is_frontpage' => 'boolean'
    ];

    public function versions()
    {
        return $this->hasMany(PublishedPage::class, 'page_id');
    }

    public function menu_items()
    {
        return $this->hasMany(MenuItem::class, 'path');
    }

    public function published_version()
    {
        return $this->hasOne(PublishedPage::class, 'page_id')
            ->latestOfMany();
    }

    public function isDifferentFromPublishedVersion()
    {
        $published = $this->published_version;

        if (!$published) {
            return true;
        }

        $current = $this->only([
            'title',
            'content',
            'data',
            'description',
            'excerpt',
            'featured_image'
        ]);

        $published = $published->only([
            'title',
            'content',
            'data',
            'description',
            'excerpt',
            'featured_image'
        ]);

        return $current != $published;
    }

    public function getPublishedModel()
    {
        $published = $this->published_version;

        if (!$published) {
            return $this;
        }

        return Page::make([
            ...$this->only([
                'id',
                'category_id',
                'is_frontpage',
                'type',
                'layout_id',
                'slug',
                'published_at',
                'created_at',
                'updated_at'
            ]),
            'title' => $published->title,
            'content' => $published->content,
            'data' => $published->data,
            'description' => $published->description,
            'excerpt' => $published->excerpt,
            'featured_image' => $published->featured_image
        ]);
    }

    public function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn() => filled($this->published_at) && !is_null($this->published_version),
            set: fn($value) => ['published_at' => $value ? now() : null]
        );
    }

    public function togglePublish()
    {
        $this->is_published ? $this->unpublish() : $this->publish();
    }

    public function publish()
    {
        if ($this->isDifferentFromPublishedVersion()) {
            $this->versions()->create([
                'title' => $this->title,
                'description' => $this->description,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'featured_image' => $this->featured_image,
                'data' => $this->data
            ]);
        }

        $this->update([
            'published_at' => now()
        ]);
    }

    public function unpublish()
    {
        $this->update(['published_at' => null]);
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->has('published_version');
    }

    public function scopePages(Builder $query): void
    {
        $query->where('type', PageType::Page);
    }

    public function scopeFrontpage(Builder $query): void
    {
        $query->where('type', PageType::Page)
            ->where('is_frontpage', true);
    }

    public function scopePosts(Builder $query): void
    {
        $query->where('type', PageType::Post);
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getUrl(bool $absolute = true)
    {
        if ($this->is_frontpage) {
            return route('home', absolute: false);
        }

        return route(name: 'pages.show', parameters: [
            'page' => $this->slug
        ], absolute: $absolute);
    }

    public function getStatus(): Status
    {
        return !is_null($this->published_at)
            ? Status::Published
            : Status::Draft;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
            ->doNotGenerateSlugsOnUpdate()
            ->usingSeparator('-');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getPostAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update_post', $this),
            'delete' => Gate::allows('delete_post', $this)
        ];
    }

    public function getPageAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update_page', $this),
            'delete' => Gate::allows('delete_page', $this)
        ];
    }
}
