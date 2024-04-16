<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
        @yield('meta')
		@include('Layouts.meta')
	</head>
	<body id="app" class="bg-stone-200">
        <header id="header">
            @yield('nav')
        </header>
		<main>
			@yield('content')
		</main>
		@include('layouts.footer')
	</body>
</html>