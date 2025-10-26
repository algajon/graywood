<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = collect();

        if (Schema::hasTable('notifications')) {
            $notifications = DB::table('notifications')
                ->orderByDesc('created_at')
                ->limit(50)
                ->get();
        }

        return view('admin.notifications.index', compact('notifications'));
    }
}
