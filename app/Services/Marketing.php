<?php

namespace App\Services;

use App\Models\Setting;

class Marketing
{
    /**
     * Get a marketing setting by key with optional default
     */
    public function get(string $key, $default = '')
    {
        return Setting::get($key, $default);
    }
}