<?php

namespace Modules\Media\Models;

use App\Models\BaseModel;
use Modules\Auth\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Gate;
use Modules\Media\Policies\FolderPolicy;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
// use Modules\Media\Database\Factories\FolderFactory;
#[UsePolicy(FolderPolicy::class)]
class Folder extends BaseModel implements HasMedia
{
    use HasRecursiveRelationships;
    use SoftDeletes;
    use InteractsWithMedia;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreateRoot()
    {
        $folder = Folder::isRoot()->first();

        if (!$folder) {
            $folder = Folder::create([
                "name" => "Home"
            ]);
        }

        return $folder;
    }

    public function getBreadcrumbs()
    {
        return $this->ancestorsAndSelf()->get()
            ->sortBy('depth')
            ->values()
            ->map(fn($item) => [
                'id' => $item->id,
                'icon' => is_null($item->parent_id) ? 'ph:house' : null,
                'label' => $item->name,
                'type' => 'breadcrumb',
                'isLast' => $item->is($this)
            ]);
    }

    public function getTotalChildren()
    {
        return count($this->media) + count($this->children);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useDisk('media')
            ->useFallbackUrl('https://placehold.co/368x207');
        // ->useFallbackUrl(url('/storage/no-product-image.png'));
    }

    public function registerAllMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        ini_set('memory_limit', '4048M');
        $this
            ->addMediaConversion('thumbnail')
            ->keepOriginalImageFormat()
            ->width(368)
            ->height(207)
            ->sharpen(10);
    }

    public function getAuthorizationAttribute()
    {
        return [
            'update' => Gate::allows('update', $this),
            'delete' => Gate::allows('delete', $this)
        ];
    }


}
