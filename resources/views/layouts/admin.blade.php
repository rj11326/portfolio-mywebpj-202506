<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理画面 - @yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white border-b shadow-sm mb-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">管理画面</a>
            <div>
                @auth('admin')
                    <span class="mr-4">{{ Auth::guard('admin')->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:underline">ログアウト</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>
    <main class="max-w-5xl mx-auto px-4">
        @yield('content')
    </main>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
