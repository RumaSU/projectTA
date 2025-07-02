<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @stack('default-layout-head-meta')
    <title>@yield('titlePage', 'Digital Signature')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@3.0.4/index.min.js"></script> --}}
    <script src="{{ asset('vendor/vanillaCalendarPro/index.js') }}"></script>
    <script src="{{ asset('vendor/dayjs/dayjs.min.js') }}"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    
    {{-- <link href="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@3.0.4/styles/index.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/vanillaCalendarPro/styles/index.css') }}"></link>
    
    <link rel="stylesheet" href="{{ asset('main/css/s.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('main/css/font/poppins.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('main/css/font/system-ui sans.css') }}" type="text/css">
    
    {{-- <link rel="stylesheet" href="{{ asset('vendor/flasher/flasher.min.css') }}" data-navigate-once> --}}
    
    @yield('default-layout-head-field')
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    @once
        {{-- <script src="{{ asset('main/js/jqueryNoConflict.js') }}"></script> --}}
        <script>
            const jq = jQuery.noConflict();
            window.$jq = jq;
        </script>
        <script>
            const { Calendar } = window.VanillaCalendarPro;
        </script>
        <script>
            console.log(dayjs())
        </script>
    @endonce
</head>
<body @stack('default-aditional-prop-body')>
    @yield('default-layout-body-content')
    
    {{-- <script src="{{ asset('vendor/flasher/flasher.min.js') }}" data-navigate-once></script> --}}
    {{-- {!! \Flasher\Laravel\Facade\Flasher::render('html') !!} --}}
</body>
</html>