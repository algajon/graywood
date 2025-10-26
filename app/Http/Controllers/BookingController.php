<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function show(Request $request)
    {
        // Require users to be authenticated before booking
        if (!Auth::check()) {
            // redirect guest to login and preserve intended URL
            return redirect()->guest(route('login'));
        }

        // Use your config/services or hardcode the base URL
        $base = config('services.ghl.calendar_url')
            ?? 'https://api.leadconnectorhq.com/widget/booking/ToUBYQOsru57Q83FhIc0';

        // collect any attribution utm params from session
        $utms = array_filter((array) session('attribution', []));

        // Prefill fields for authenticated user using authoritative stored user fields
        $user = Auth::user();
        $params = $utms;
        if ($user) {
            $fullName = trim($user->name ?? '');
            $nameParts = preg_split('/\s+/', $fullName, 2);
            $first = $nameParts[0] ?? '';
            $last = $nameParts[1] ?? '';

            if ($first) $params['first_name'] = $first;
            if ($last)  $params['last_name'] = $last;
            if (!empty($user->email)) $params['email'] = $user->email;
            if (!empty($user->phone)) $params['phone'] = $user->phone;
        }

        // Build final src with only the authoritative prefill keys
        $qs = http_build_query(array_filter($params, function($v){ return $v !== null && $v !== ''; }));
        $src = $base . (Str::contains($base, '?') ? '&' : '?') . $qs;

        return view('booking', compact('src'));
    }
}
