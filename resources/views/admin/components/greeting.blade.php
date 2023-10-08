@php
    $greeting = Cache::get('greeting');
@endphp
@if ($greeting && $greeting['greeting_text'])
    <div class="d-flex flex-column mb-2 p-2 bg-white shadow-sm">
    <div class="greeting">
        <h2 class="mb-0" style="color: {{ $greeting['text_color'] }}">{{ $greeting['greeting_text'] }}</h2>
    </div>
    </div>
@endif
