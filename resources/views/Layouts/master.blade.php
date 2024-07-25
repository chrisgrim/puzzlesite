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
		<main class="pt-32">
			@yield('content')
		</main>
		@include('Layouts.footer')
	</body>
</html>