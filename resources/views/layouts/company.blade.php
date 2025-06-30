<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>企業管理画面 - @yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white border-b shadow-sm mb-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('company.dashboard') }}" class="text-xl font-bold text-blue-600">企業管理</a>
            <div>
                @auth('company')
                <span class="mr-4">{{ Auth::guard('company')->user()->name }}</span>
                <form method="POST" action="{{ route('company.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">ログアウト</button>
                </form>
                @endauth
            </div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4">
        @yield('content')
    </main>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>