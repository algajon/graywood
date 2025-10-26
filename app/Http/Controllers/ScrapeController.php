<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ScrapeRun;

class ScrapeController extends Controller
{
    public function index() {
        $user = Auth::user();
        return view('scrapes.index', compact('user'));
    }

    public function start(Request $request) {
        $payload = $request->validate([
            'base_url'     => ['required','url'],
            'max_listings' => ['nullable','integer','min:1','max:1000'],
        ]);

        $resp = Http::post(config('services.scraper.base').'/scrape', $payload);
        abort_unless($resp->ok(), 502, 'Scraper service error');

        $runId = $resp->json('run_id');

        // persist run for history/activity
        ScrapeRun::create([
            'id'           => $runId,
            'user_id'      => Auth::id(),
            'base_url'     => $payload['base_url'],
            'max_listings' => $payload['max_listings'] ?? null,
            'status'       => 'queued',
            'count'        => 0,
            'started_at'   => now(),
        ]);

        // remember cap for progress bar in show()
        session(['max_listings_' . $runId => $payload['max_listings'] ?? 50]);

        return redirect()->route('scrapes.show', ['runId' => $runId]);
    }

public function show(string $runId)
{
    $user = \Auth::user();
    $base = config('services.scraper.base');

    $status   = \Http::get("$base/runs/$runId")->json();
    $resultsJ = \Http::get("$base/runs/$runId/results")->json();

    $results  = is_array($resultsJ['results'] ?? null) ? $resultsJ['results'] : [];
    $count    = (int)($status['count'] ?? count($results)); // fall back to results length

    // prefer the session cap, else DB cap, else 50
    $maxListings = session('max_listings_'.$runId)
        ?? optional(ScrapeRun::whereKey($runId)->where('user_id', $user->id)->first())->max_listings
        ?? 50;

    $done = false;
    if (($status['status'] ?? null) === 'succeeded') {
        $done = true;
    } elseif ($maxListings && $count >= $maxListings) {
        // we reached the requested amount — treat as done locally
        $done = true;
        $status['status'] = 'succeeded';
    }

    // sync local record for history/activity
    if ($run = ScrapeRun::whereKey($runId)->where('user_id', $user->id)->first()) {
        $run->count           = $count;
        $run->status          = $done ? 'succeeded' : ($status['status'] ?? $run->status);
        $run->last_checked_at = now();
        if ($done && !$run->finished_at) {
            $run->finished_at = now();
        }
        $run->save();
    }

    return view('scrapes.show', compact('runId','status','results','user','maxListings','done','count'));
}

    // NEW: list user’s runs (for "View Recent Runs" & "Download Lead Lists")
public function history()
{
    $user = \Auth::user();

    $runs = ScrapeRun::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->paginate(20); // or ->take(50)->get()

    return view('scrapes.history', compact('runs','user'));
}
public function downloads()
{
    $user = \Auth::user();
    $base = config('services.scraper.base');

    $runs = ScrapeRun::where('user_id', $user->id)
        ->where('status', 'succeeded')
        ->orderByDesc('finished_at')
        ->paginate(20);

    return view('scrapes.downloads', compact('runs','base','user'));
}


    // NEW: proxy/record download timestamp, then redirect to scraper CSV
    public function export(string $runId) {
        $run = ScrapeRun::whereKey($runId)->where('user_id', Auth::id())->firstOrFail();
        if (!$run->downloaded_at) {
            $run->downloaded_at = now();
            $run->save();
        }
        $base = config('services.scraper.base');
        return redirect()->away("$base/runs/$runId/export.csv");
    }
}
