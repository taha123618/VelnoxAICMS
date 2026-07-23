<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'group',
    'key',
    'value',
    'type',
])]
class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * Get a setting value by key, optionally providing a default.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($setting->type === 'integer') {
            return (int) $setting->value;
        }

        return $setting->value;
    }
}
