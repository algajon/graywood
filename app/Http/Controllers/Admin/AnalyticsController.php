<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $total = User::count();
        $paid  = User::where('tier', 'paid')->count();
        $free  = User::where('tier', 'user')->count();
        $mrr   = $paid * 199;

        $start = Carbon::now()->subDays(29)->startOfDay();
        $signups = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $start)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.analytics.index', compact('total', 'paid', 'free', 'mrr', 'signups'));
    }
}
