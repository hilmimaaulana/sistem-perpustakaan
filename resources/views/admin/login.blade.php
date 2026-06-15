<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="bg-slate-950 px-6 py-5 text-center">
        <span class="text-3xl">🔐</span>
        <h2 class="text-xl font-bold text-white mt-2">Gerbang Admin</h2>
        <p class="text-xs text-slate-400 mt-1">Silakan masuk untuk mengelola sirkulasi perpustakaan</p>
    </div>

    <div class="p-6 sm:p-8">
        @if($errors->has('error'))
            <div class="mb-4 p-3.5 text-xs font-medium text-rose-800 rounded-xl bg-rose-50 border border-rose-200">
                ⚠️ {{ $errors->first('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username Admin</label>
                <input type="text" name="username" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="Masukkan username..." required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 active:scale-[0.99] transition-all shadow-sm">
                Masuk Ke Dashboard 
            </button>
        </form>
    </div>
</div>

</body>
</html>