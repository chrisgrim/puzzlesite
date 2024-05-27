@extends('Layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
@endsection 

@section('nav')
    @include('Layouts.nav')
@endsection

@section('content')
    <main class="min-h-[30rem]">
        <email-verify></email-verify>
    </main>
@endsection

@section('footer')

<footer class="flex justify-center p-4 border-t">
    <!-- Footer content -->
    <p>&copy; 2024 Your Site Name. All rights reserved.</p>
</footer>

@endsection 