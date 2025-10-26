<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function edit()
    {
        // Get all settings as key-value pairs
        $settings = [];
        foreach ($this->getKnownKeys() as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        return view('admin.marketing', [
            'settings' => $settings,
            'faqsJson' => json_encode(Setting::get('faqs', []), JSON_PRETTY_PRINT)
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cta.navbar' => 'nullable|string|max:100',
            'cta.footer' => 'nullable|string|max:100',
            'cta.hero' => 'nullable|string|max:100',
            'cta.tenant' => 'nullable|string|max:100',
            'cta.pricing_part_time' => 'nullable|string|max:100',
            'cta.pricing_full_time' => 'nullable|string|max:100',
            'cta.pricing_enterprise' => 'nullable|string|max:100',
            'headlines.hero_h1' => 'nullable|string|max:120',
            'headlines.hero_h2' => 'nullable|string|max:120',
            'pricing.blurb' => 'nullable|string|max:300',
            'faqs_json' => 'nullable|string',
        ]);

        // Save each setting
        foreach ($validated as $key => $value) {
            if ($key === 'faqs_json') continue;
            Setting::set($key, $value);
        }

        // Handle FAQs separately as JSON array
        if (isset($validated['faqs_json']) && $validated['faqs_json'] !== null) {
            $faqs = json_decode($validated['faqs_json'], true);
            if (is_array($faqs)) {
                $faqs = array_values(array_filter($faqs, function ($item) {
                    return is_array($item) && isset($item['question']) && isset($item['answer']);
                }));
                Setting::set('faqs', $faqs);
            }
        }

        return redirect()->back()->with('status', 'Settings saved.');
    }

    /**
     * Get all known settings keys used in the application
     */
    private function getKnownKeys()
    {
        return [
            'cta.navbar',
            'cta.footer',
            'cta.hero',
            'cta.tenant',
            'cta.pricing_part_time',
            'cta.pricing_full_time',
            'cta.pricing_enterprise',
            'headlines.hero_h1',
            'headlines.hero_h2',
            'pricing.blurb'
        ];
    }
}



