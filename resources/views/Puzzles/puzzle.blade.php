@extends('Layouts.master')

@section('meta')
    <title>{{ $puzzle->title }} - {{ config('app.name') }}</title>
@endsection 

@section('nav')
    @include('Layouts.nav')
@endsection

@section('content')
    <main class="min-h-[30rem]">
        <component 
		    :is="'puzzle-{{ $puzzle->slug }}'"
		    :puzzle="{{ $puzzle }}"
		    :chapter="{{ $chapter }}"
		    :solution="{{ $solution ?? 'null' }}"
		    :user="{{ $user }}"
		></component>
    </main>
@endsection

@section('footer')
    <footer class="flex justify-center p-4 border-t">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>
@endsection