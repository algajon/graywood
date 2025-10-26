@extends('layouts.app')
@section('title','Cold Calling Scripts — Agent Bookr')
@section('content')
<div class="container mx-auto px-6 max-w-4xl pt-[120px]">
  <h1 class="text-3xl font-extrabold mb-4">Cold Calling Scripts</h1>
  <div class="card p-6 space-y-4">
    <div>
      <h2 class="font-bold">Basic Intro (Starter)</h2>
      <pre class="bg-slate-50 p-4 rounded-lg text-sm">Hi, is this {{ '{name}' }}? ...</pre>
    </div>
    <div>
      <h2 class="font-bold">Qualification (Pro)</h2>
      <pre class="bg-slate-50 p-4 rounded-lg text-sm">Great, a couple quick questions to see if this is a fit...</pre>
    </div>
    <div>
      <h2 class="font-bold">Objection Handling (Pro)</h2>
      <pre class="bg-slate-50 p-4 rounded-lg text-sm">Totally understand. Agents I work with said the same until...</pre>
    </div>
  </div>
</div>
@endsection
