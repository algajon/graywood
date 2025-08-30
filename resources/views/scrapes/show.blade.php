@extends('layouts.app')

@section('title', 'Lead Generation Results - Agent Bookr')

@section('content')
    <!-- Results Section -->
    <section class="min-h-screen bg-[#2F3E46]" style="padding-top: 120px;">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Lead Generation Results</h1>
                <p class="text-xl text-gray-300">Monitor your scraping progress and download your leads</p>
            </div>

            <!-- Status and Export -->
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-[#2F3E46]">Run ID: {{ $runId }}</h2>
                            <p class="text-gray-600">Track your lead generation progress</p>
                        </div>
                        <div class="text-right">
                            <a 
                                href="{{ config('services.scraper.base') }}/runs/{{ $runId }}/export.csv" 
                                class="inline-flex items-center bg-[#FFB703] text-[#2F3E46] px-6 py-3 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-all duration-200"
                            >
                                <i class="fas fa-download mr-2"></i>
                                Export CSV
                            </a>
                        </div>
                    </div>
                    
                    <!-- Status Display -->
                    <div id="status" class="bg-[#CAD2C5] rounded-lg p-4 text-center">
                        <div class="text-[#2F3E46] text-sm text-blue-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Refresh this page in a minute to see your results
                        </div>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-[#52796F]">
                        <h3 class="text-xl font-semibold text-white">Generated Leads</h3>
                        <p class="text-gray-200 text-sm">Your scraped data will appear below</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if(!empty($results['results']))
                                        @foreach(array_keys($results['results'][0]) as $col)
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">{{ $col }}</th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="rows" class="bg-white divide-y divide-gray-200">
                                @foreach($results['results'] ?? [] as $row)
                                    <tr class="hover:bg-gray-50">
                                        @foreach($row as $v)
                                            <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $v ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Progress Info -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-[#2F3E46] mb-4">What's Happening?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="p-3 bg-[#FFB703] rounded-lg inline-block mb-2">
                                <i class="fas fa-search text-[#2F3E46] text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-[#2F3E46]">Searching</h4>
                            <p class="text-sm text-gray-600">Scanning listings for relevant properties</p>
                        </div>
                        <div class="text-center">
                            <div class="p-3 bg-[#52796F] rounded-lg inline-block mb-2">
                                <i class="fas fa-database text-white text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-[#2F3E46]">Processing</h4>
                            <p class="text-sm text-gray-600">Extracting contact information and details</p>
                        </div>
                        <div class="text-center">
                            <div class="p-3 bg-[#2F3E46] rounded-lg inline-block mb-2">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-[#2F3E46]">Complete</h4>
                            <p class="text-sm text-gray-600">Ready for download and use</p>
                        </div>
                    </div>
                </div>

                <!-- Success Celebration (Hidden by default) -->
                <div id="success-celebration" class="mt-6 bg-gradient-to-r from-green-400 to-green-600 rounded-lg shadow-lg p-8 text-center hidden">
                    <div class="text-white">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-2xl font-bold mb-2">Lead Generation Complete!</h3>
                        <p class="text-lg mb-4">Your leads are ready for download and use in your CRM system.</p>
                        <div class="flex justify-center space-x-4">
                            <a 
                                href="{{ config('services.scraper.base') }}/runs/{{ $runId }}/export.csv" 
                                class="inline-flex items-center bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200"
                            >
                                <i class="fas fa-download mr-2"></i>
                                Download CSV
                            </a>
                            <a 
                                href="{{ route('scrapes.index') }}" 
                                class="inline-flex items-center bg-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-800 transition-all duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Start New Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    const base = "{{ config('services.scraper.base') }}";
    const runId = "{{ $runId }}";
    async function checkResults() {
        try {
            const s = await fetch(`${base}/runs/${runId}`).then(r => r.json());
            const statusElement = document.getElementById('status');
            
            // Check if scraping is complete (has results)
            const r = await fetch(`${base}/runs/${runId}/results`).then(r => r.json());
            
            if (r.results && r.results.length > 0) {
                // Show success tick
                statusElement.innerHTML = `
                    <div class="flex items-center justify-center">
                        <div class="p-3 bg-green-500 rounded-full mr-4">
                            <i class="fas fa-check text-white text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-[#2F3E46] font-semibold text-lg text-green-700">Scraping Complete!</div>
                            <div class="text-[#2F3E46] text-sm mt-1">Found ${r.results.length} leads</div>
                        </div>
                    </div>
                `;
                statusElement.className = 'bg-green-100 border-2 border-green-300 rounded-lg p-4 text-center';
                
                // Show success celebration
                const celebration = document.getElementById('success-celebration');
                if (celebration) {
                    celebration.classList.remove('hidden');
                    celebration.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Update results table
                const rows = document.getElementById('rows');
                rows.innerHTML = '';
                r.results.forEach(obj => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    
                    Object.values(obj).forEach(v => {
                        const td = document.createElement('td');
                        td.className = 'px-4 py-3 text-sm text-gray-900 border-b';
                        td.textContent = v ?? '';
                        tr.appendChild(td);
                    });
                    rows.appendChild(tr);
                });
                
                return; // Stop checking
            }
            
            // Continue checking every 30 seconds
            setTimeout(checkResults, 30000);
            
        } catch (error) {
            console.error('Error checking results:', error);
            // Just continue checking on error
            setTimeout(checkResults, 30000);
        }
    }

    // Start checking for results
    checkResults();
    </script>
@endsection
