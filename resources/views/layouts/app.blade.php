@include('layouts.header')
@php
    $autor = env('APP_AUTHOR');
    $version = env('APP_VERSION'); 
@endphp
<main class="container mx-auto py-4">
    @yield('content')
</main>

@include('layouts.footer')
