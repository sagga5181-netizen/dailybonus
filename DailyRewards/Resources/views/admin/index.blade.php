@extends('flute::admin.layouts.app')

@section('title', 'Ежедневные награды')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1><i class="fas fa-gift"></i> {{ __('dailyrewards.module_title') }}</h1>
        <p>{{ __('dailyrewards.module_description') }}</p>
    </div>

    @include('flute::components.alerts')

    <!-- Tabs -->
    <div class="admin-tabs">
        <a href="?tab=general" class="tab-item {{ $tab === 'general' ? 'active' : '' }}">
            <i class="fas fa-cog"></i> {{ __('dailyrewards.tabs.general') }}
        </a>
        <a href="?tab=rewards" class="tab-item {{ $tab === 'rewards' ? 'active' : '' }}">
            <i class="fas fa-gift"></i> {{ __('dailyrewards.tabs.rewards') }}
        </a>
        <a href="?tab=visual" class="tab-item {{ $tab === 'visual' ? 'active' : '' }}">
            <i class="fas fa-palette"></i> {{ __('dailyrewards.tabs.visual') }}
        </a>
        <a href="?tab=integration" class="tab-item {{ $tab === 'integration' ? 'active' : '' }}">
            <i class="fas fa-link"></i> {{ __('dailyrewards.tabs.integration') }}
        </a>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        @if($tab === 'general')
            @include('dailyrewards::admin.general')
        @elseif($tab === 'rewards')
            @include('dailyrewards::admin.rewards')
        @elseif($tab === 'visual')
            @include('dailyrewards::admin.visual')
        @elseif($tab === 'integration')
            @include('dailyrewards::admin.integration')
        @endif
    </div>
</div>
@endsection