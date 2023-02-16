<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Styles -->
    {{-- ブラウザ上で調整する際に使用 --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/google_tagmanager.js') }}"></script>
</head>

<body class="text-lg">
<div class="grid grid-cols-1 h-screen">
    <div class="w-full lg:m-0 p-6 lg:px-4 lg:px-0 grid grid-cols-1 justify-items-center bg-gray-100 h-auto">
        <div class="w-full p-6 max-w-2xl lg:p-12 lg:mx-6 bg-white">
            <div class="text-center">
            <img src="https://img.jusnet.co.jp/common/jusnet_logo_long.svg" alt=""
                    width="300" height="69" class="block w-80 mx-auto">
                <h4 class="text-md font-semibold mt-1 mb-12 pb-1">検索フォーム</h4>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
