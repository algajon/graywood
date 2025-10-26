<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    
    /**
     * Get a setting value by key with optional default
     */
    public static function get($key, $default = null)
    {
        $record = static::where('key', $key)->first();
        if (!$record) {
            return $default;
        }
        
        // Try to decode JSON, fall back to plain value
        $value = $record->value;
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }
    
    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        // JSON encode arrays/objects
        $store = is_array($value) || is_object($value) ? json_encode($value) : $value;
        
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $store]
        );
    }
}