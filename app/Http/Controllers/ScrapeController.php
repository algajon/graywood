<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class ScrapeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index() {
        $user = Auth::user();
        return view('scrapes.index', compact('user'));
    }

    public function start(Request $request) {
        $payload = $request->validate([
            'base_url' => ['required','url'],
            'max_listings' => ['nullable','integer','min:1','max:1000'],
        ]);

        $resp = Http::post(config('services.scraper.base').'/scrape', $payload);
        abort_unless($resp->ok(), 502, 'Scraper service error');

        // Store max_listings in session for the show view
        session(['max_listings_' . $resp->json('run_id') => $payload['max_listings'] ?? 50]);

        return redirect()->route('scrapes.show', ['runId' => $resp->json('run_id')]);
    }

    public function show(string $runId) {
        $user = Auth::user();
        $base = config('services.scraper.base');
        $status = Http::get("$base/runs/$runId")->json();
        $results = Http::get("$base/runs/$runId/results")->json();
        
        // Get max_listings from session
        $maxListings = session('max_listings_' . $runId, 50);
        
        return view('scrapes.show', compact('runId','status','results','user','maxListings'));
    }
}