<?php

namespace Modules\Page\Models;

use App\Enums\Status;
use App\Models\BaseModel;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;
use Modules\Layout\Models\Layout;
use Modules\Menu\Models\MenuItem;
use Modules\Page\Enums\PageType;
use Modules\Page\Policies\PagePolicy;
use Modules\Visits\Traits\Visitable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[UsePolicy(PagePolicy::class)]
class Page extends BaseModel
{
    use BelongsToTenant;
    use HasSlug;
    use SoftDeletes;
    use Visitable;

    #[\Override]
    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'json',
        'data' => 'json',
        'type' => PageType::class,
        'is_frontpage' => 'boolean',
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

        if (! $published) {
            return true;
        }

        $current = $this->only([
            'title',
            'content',
            'data',
            'description',
            'excerpt',
            'featured_image',
        ]);

        $published = $published->only([
            'title',
            'content',
            'data',
            'description',
            'excerpt',
            'featured_image',
        ]);

        return $current != $published;
    }

    public function getPublishedModel(): self
    {
        $published = $this->published_version;

        if (! $published) {
            return $this;
        }

        return new Page([
            ...$this->only([
                'id',
                'category_id',
                'is_frontpage',
                'type',
                'layout_id',
                'slug',
                'published_at',
                'created_at',
                'updated_at',
            ]),
            'title' => $published->title,
            'content' => $published->content,
            'data' => $published->data,
            'description' => $published->description,
            'excerpt' => $published->excerpt,
            'featured_image' => $published->featured_image,
        ]);
    }

    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => filled($this->published_at) && ! is_null($this->published_version),
            set: fn ($value): array => ['published_at' => $value ? now() : null]
        );
    }

    public function togglePublish(): void
    {
        $this->is_published ? $this->unpublish() : $this->publish();
    }

    public function publish(): void
    {
        if ($this->isDifferentFromPublishedVersion()) {
            $this->versions()->create([
                'title' => $this->title,
                'description' => $this->description,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'featured_image' => $this->featured_image,
                'data' => $this->data,
            ]);
        }

        $this->update([
            'published_at' => now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update(['published_at' => null]);
    }

    protected function scopePublished(Builder $builder): void
    {
        $builder->whereNotNull('published_at')
            ->has('published_version');
    }

    protected function scopePages(Builder $builder): void
    {
        $builder->where('type', PageType::Page);
    }

    protected function scopeFrontpage(Builder $builder): void
    {
        $builder->where('type', PageType::Page)
            ->where('is_frontpage', true);
    }

    protected function scopePosts(Builder $builder): void
    {
        $builder->where('type', PageType::Post);
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getUrl(bool $absolute = true): string
    {
        if ($this->is_frontpage) {
            return route('home', absolute: false);
        }

        return route(name: 'pages.show', parameters: [
            'page' => $this->slug,
        ], absolute: $absolute);
    }

    public function getStatus(): Status
    {
        return is_null($this->published_at)
            ? Status::Draft
            : Status::Published;
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

    protected function scopeFilter(Builder $builder, array $filters): void
    {
        $builder->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where('title', 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed): void {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    protected function getPostAuthorizationAttribute(): array
    {
        return [
            'update' => Gate::allows('update_post', $this),
            'delete' => Gate::allows('delete_post', $this),
        ];
    }

    protected function getPageAuthorizationAttribute(): array
    {
        return [
            'update' => Gate::allows('update_page', $this),
            'delete' => Gate::allows('delete_page', $this),
        ];
    }
}
