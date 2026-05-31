<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Gestion de Cabinet</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Google Fonts & Lucide Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- 1. SIDEBAR -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between h-full hidden md:flex">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-8">
                <div class="h-9 w-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md shadow-blue-200">M</div>
                <span class="font-bold text-xl tracking-tight text-slate-900">MediConnect</span>
            </div>
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 font-medium rounded-xl transition-all">
                    <span>📅</span> {{ __('Dashboard') }}
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium rounded-xl transition-all">
                    <span>👥</span> {{ __('Patients') }}
                </a>
            </nav>
        </div>
        <!-- Lang Switcher inside Sidebar Footer -->
        <div class="p-6 border-t border-slate-100 flex gap-2">
            <a href="?lang=fr" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 {{ app()->getLocale() == 'fr' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">FR</a>
            <a href="?lang=ar" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 {{ app()->getLocale() == 'ar' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">AR</a>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-full overflow-y-auto">
        <!-- HEADER -->
        <header class="bg-white border-b border-slate-200 h-16 min-h-16 px-8 flex items-center justify-between sticky top-0 z-10">
            <h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Tableau de bord')</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600 font-medium">{{ Auth::user()->name ?? 'Dr. El Alami' }}</span>
                <div class="h-9 w-9 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-700">A</div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="p-8 flex-1">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-100 py-4 px-8 text-center text-xs text-slate-400 font-medium">
            &copy; 2026 MediConnect. Tous droits réservés (OFPPT CC2).
        </footer>
    </div>

    <!-- Scripts (Axios etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>