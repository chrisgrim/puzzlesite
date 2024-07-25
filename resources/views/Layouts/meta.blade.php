<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="apple-touch-icon" sizes="180x180" href="/storage/website-files/favicons/apple-touch-icon.png">
<link rel="mask-icon" href="/storage/website-files/favicons/safari-pinned-tab.svg" color="#f7653b">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#f7653b">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cutive+Mono&family=Source+Code+Pro:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Fahkwang:ital,wght@0,100..900;1,100..900&family=Tenor+Sans&display=swap" rel="stylesheet">

<script>
    window.Laravel = {
        user: {!! Auth::check() ? json_encode([
            'id' => Auth::user()->id,
            'email' => Auth::user()->email,
        ]) : 'null' !!}
    };
</script>
@yield('head')



@vite(['resources/js/app.js'])




