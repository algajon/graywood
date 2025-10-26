<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function general()
    {
        return view('admin.settings.general', [
            'appName'    => config('app.name'),
            'appEnv'     => config('app.env'),
            'appDebug'   => (bool) config('app.debug'),
            'timezone'   => config('app.timezone'),
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function security()
    {
        return view('admin.settings.security', [
            'sessionDriver'   => config('session.driver'),
            'sessionLifetime' => config('session.lifetime'),
            'cipher'          => config('app.cipher'),
            'appKeySet'       => !empty(config('app.key')),
            'passwords'       => config('auth.passwords'),
        ]);
    }

    public function database()
    {
        $default = config('database.default');

        return view('admin.settings.database', [
            'default'    => $default,
            'connection' => config("database.connections.{$default}"),
        ]);
    }
}
