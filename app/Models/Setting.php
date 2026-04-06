<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Cache key prefix for settings
     */
    private const CACHE_KEY = 'app_settings';
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Get a setting value by key.
     * Returns from cache first, falls back to DB, then default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     * Updates DB and invalidates cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        static::clearCache();
    }

    /**
     * Bulk update multiple settings at once.
     * More efficient than calling set() individually.
     */
    public static function setMany(array $settings, ?string $group = null): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            return;
        }

        foreach ($settings as $key => $value) {
            $data = ['value' => $value];
            if ($group) {
                $data['group'] = $group;
            }

            static::updateOrCreate(['key' => $key], $data);
        }

        static::clearCache();
    }

    /**
     * Get all settings as a key => value array (cached).
     */
    public static function getAllCached(): array
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return [];
            }

            return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
                return static::pluck('value', 'key')->toArray();
            });
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all settings for a specific group.
     */
    public static function getGroup(string $group): array
    {
        $all = static::getAllCached();
        $grouped = static::where('group', $group)->pluck('key')->toArray();

        return array_intersect_key($all, array_flip($grouped));
    }

    /**
     * Check if a boolean setting is enabled.
     */
    public static function isEnabled(string $key): bool
    {
        return (bool) static::get($key, false);
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
