<nav x-data="{ open: false }" class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- ロゴ -->
            <a href="/" class="flex items-center space-x-2">
                <span class="text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </span>
                <span class="text-xl font-semibold text-gray-900">SimplyJob</span>
            </a>

            <!-- PCナビゲーション -->
            <div class="hidden sm:flex gap-6 items-center">
                <a href="/jobs" class="text-gray-800 hover:text-red-600 text-sm font-medium">求人情報</a>
                @auth
                    <a href="{{ route('messages.index') }}"
                        class="text-gray-800 hover:text-red-600 text-sm font-medium">メッセージ</a>
                    <div class="relative ml-4" x-data="{ showMenu: false }">
                        <button @click="showMenu = !showMenu" @click.away="showMenu = false"
                            class="flex flex-col items-center focus:outline-none">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 border">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9A3.75 3.75 0 1 1 8.25 9a3.75 3.75 0 0 1 7.5 0zM4.5 19.5a7.5 7.5 0 0 1 15 0v.75A2.25 2.25 0 0 1 17.25 22.5h-10.5A2.25 2.25 0 0 1 4.5 20.25V19.5z" />
                                </svg>
                            </span>
                            <span class="text-xs mt-1 text-gray-600">{{ Auth::user()->name }}</span>
                        </button>
                        <!-- ドロップダウン -->
                        <div
                            x-show="showMenu"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50"
                        >
                            <a href="{{ route('mypage') }}"
                                class="block px-4 py-2 text-sm text-gray-800 hover:bg-red-50">マイページ</a>
                            <a href="{{ route('saved_jobs.index') }}"
                                class="block px-4 py-2 text-sm text-gray-800 hover:bg-red-50">保存済み求人</a>
                            <a href="{{ route('applications.index') }}"
                                class="block px-4 py-2 text-sm text-gray-800 hover:bg-red-50">応募履歴</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-800 hover:bg-red-50">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-800 hover:text-red-600 text-sm font-medium">ログイン</a>
                @endauth
            </div>

            <!-- スマホ用ハンバーガー -->
            <div class="flex sm:hidden">
                <button @click="open = !open" class="text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path x-show="!open" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- スマホ時ドロワー -->
        <div x-show="open" x-transition class="sm:hidden mt-2 bg-white p-4 flex flex-col space-y-3"
            style="z-index: 50;">
            <a href="/jobs"
                class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">求人情報</a>
            @auth
                <a href="{{ route('messages.index') }}"
                    class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">メッセージ</a>
                <a href="{{ route('applications.index') }}"
                    class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">応募履歴</a>
                <a href="{{ route('saved_jobs.index') }}"
                    class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">保存済み求人</a>
                <a href="{{ route('mypage') }}"
                    class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">
                        ログアウト
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="block w-full text-gray-800 hover:bg-red-50 hover:text-red-600 text-base font-medium rounded px-3 py-2 transition">ログイン</a>
            @endauth
        </div>
    </div>
</nav>
