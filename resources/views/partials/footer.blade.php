<footer class="bg-white border-t mt-12 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center mb-2">
            <span class="text-red-600 mr-2">
                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                </svg>
            </span>
            <span class="text-lg font-semibold text-gray-900 mr-2">SimplyJob</span>
            <span class="text-xs text-gray-500">シンプルな求人サイトです。</span>
        </div>
        <div class="flex flex-wrap items-center space-x-4 mb-2">
            <a href="{{ url('company/login') }}" class="hover:text-red-600 transition">企業ログイン</a>
            <a href="{{ url('admin/login') }}" class="hover:text-red-600 transition">管理者ログイン</a>
        </div>
        <div class="text-gray-400 mt-2">&copy; {{ date('Y') }} SimplyJob. All Rights Reserved.</div>
    </div>
</footer>