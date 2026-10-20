
@extends('layouts.rider')

@section('title', 'Active Deliveries')
@section('page', 'Active Deliveries')

@section('content')
<section class="mb-8">
    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#f1cf8b]">
        Delivery queue
    </p>

    <h1 class="serif mt-3 text-4xl tracking-tight sm:text-5xl">
        Active deliveries
    </h1>

    <p class="mt-3 max-w-xl text-sm leading-7 text-stone-400">
        Track accepted orders and manage each delivery step.
    </p>
</section>

@if ($orders->isEmpty())
    @include('rider.partials.empty', [
        'icon' => 'bike',
        'title' => 'No active deliveries',
        'text' => 'Orders you accept will appear here.'
    ])
@else
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($orders as $o)
            @include('rider.partials.active-card', [
                'o' => $o,
                'i' => $loop->index
            ])
        @endforeach
    </div>

    @if (method_exists($orders, 'hasPages') && $orders->hasPages())
        <div class="pager mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endif
@endsection
