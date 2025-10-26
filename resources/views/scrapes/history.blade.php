@extends('layouts.app')
@section('title','Lead Generation Results - Agent Bookr')
@section('content')
<div class="container mx-auto px-6 max-w-6xl pt-28">
  <h1 class="text-2xl font-bold mb-4">Run history</h1>

  @if($runs->isEmpty())
    <div class="text-slate-500">No runs yet. Start a scrape to see it here.</div>
  @else
    <div class="overflow-auto card p-4">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left p-2">Run ID</th>
            <th class="text-left p-2">Status</th>
            <th class="text-left p-2">Count</th>
            <th class="text-left p-2">Requested</th>
            <th class="text-left p-2">Started</th>
            <th class="text-left p-2">Finished</th>
            <th class="text-left p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($runs as $r)
            <tr class="border-t">
              <td class="p-2 font-mono">{{ $r->id }}</td>
              <td class="p-2">{{ ucfirst($r->status) }}</td>
              <td class="p-2">{{ $r->count ?? 0 }}</td>
              <td class="p-2">{{ $r->max_listings ?? '—' }}</td>
              <td class="p-2">{{ optional($r->started_at)->diffForHumans() }}</td>
              <td class="p-2">{{ optional($r->finished_at)->diffForHumans() ?? '—' }}</td>
              <td class="p-2">
                <a href="{{ route('scrapes.show',$r->id) }}" class="text-emerald-700 underline">View</a>
                @if($r->status === 'succeeded')
                  <span class="mx-1">·</span>
                  <a href="{{ route('scrapes.export',$r->id) }}" class="underline">Export CSV</a>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="mt-4">{{ method_exists($runs,'links') ? $runs->links() : '' }}</div>
    </div>
  @endif
</div>
@endsection