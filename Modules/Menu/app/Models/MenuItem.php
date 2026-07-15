<?php

namespace Modules\Menu\Models;

use App\Models\BaseModel;
use Modules\Menu\Enums\MenuItemType;
use Modules\Page\Models\Page;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class MenuItem extends BaseModel
{

    use HasRecursiveRelationships;

    protected $casts = [
        'type' => MenuItemType::class,
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'path');
    }

    public function getUrl()
    {
        if ($this->type == MenuItemType::Page || $this->type == MenuItemType::Post) {
            
            $this->load('page');

            if (!$this->page) {
                return '#';
            }
            return $this->page->getUrl(false);
        }
        return $this->path;
    }
}
