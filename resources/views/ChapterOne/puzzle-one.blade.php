@extends('Layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
@endsection 

@section('nav')
    <nav class="border-b h-24">
        <nav-bar :user="{{ auth()->user() ? auth()->user()->toJson() : 'null' }}"></nav-bar>
    </nav>
@endsection

@section('content')
    <main class="min-h-[30rem]">
        <puzzle-one></puzzle-one>
    </main>
@endsection

@section('footer')

<footer class="flex justify-center p-4 border-t">
    <!-- Footer content -->
    <p>&copy; 2024 Your Site Name. All rights reserved.</p>
</footer>

@endsection 
