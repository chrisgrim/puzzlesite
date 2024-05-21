@extends('Layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
@endsection 

@section('nav')
    @include('Layouts.nav')
@endsection

@section('content')
    <main class="min-h-screen">
        <side-bar :chapters="{{ json_encode($chapters) }}"></side-bar>

        @foreach ($chapters as $chapter)
            <component :is="'chapter-' + {{ $chapter->id }}" :chapter="{{ json_encode($chapter) }}"></component>
        @endforeach
    </main>
@endsection


@section('footer')

<footer class="flex justify-center p-4 border-t">
    <!-- Footer content -->
    <p>&copy; 2024 Your Site Name. All rights reserved.</p>
</footer>

@endsection 

