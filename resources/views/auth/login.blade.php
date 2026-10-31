<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Pelaporan Karhutla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    </style>
</head>
<body class="bg-white text-neutral-900 min-h-screen flex flex-col justify-center items-center p-4">

    <div class="w-full max-w-sm border border-neutral-200 rounded-lg p-8 bg-white shadow-sm">
        
        <div class="mb-8">
            <h1 class="text-xl font-bold tracking-tight text-neutral-900">Sistem Karhutla</h1>
            <p class="text-xs text-neutral-500 mt-1">Pemantauan & Pelaporan Kebakaran Hutan</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3 border border-neutral-300 bg-neutral-50 text-neutral-800 text-xs rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="username" class="block text-xs font-medium text-neutral-700 mb-1">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required 
                    autofocus
                    class="w-full px-3 py-2 text-sm border border-neutral-300 rounded focus:outline-none focus:border-neutral-900"
                    placeholder="Masukkan username"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-neutral-700 mb-1">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full px-3 py-2 text-sm border border-neutral-300 rounded focus:outline-none focus:border-neutral-900"
                    placeholder="••••••••"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-neutral-900 text-white text-sm font-medium py-2.5 rounded hover:bg-neutral-800 transition"
            >
                Masuk
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-neutral-100">
            <p class="text-[11px] font-medium text-neutral-500 mb-2">Akun Demo Cepat (Klik untuk isi):</p>
            <div class="flex flex-col gap-1.5 text-xs">
                <button type="button" onclick="fillDemo('Salman', 'password123')" class="text-left px-2 py-1.5 border border-neutral-200 rounded hover:bg-neutral-50">
                    <span class="font-medium">Salman</span> <span class="text-neutral-400">· Pelapor</span>
                </button>
                <button type="button" onclick="fillDemo('Kevin', 'password123')" class="text-left px-2 py-1.5 border border-neutral-200 rounded hover:bg-neutral-50">
                    <span class="font-medium">Kevin</span> <span class="text-neutral-400">· Relawan</span>
                </button>
                <button type="button" onclick="fillDemo('Raditya', 'password123')" class="text-left px-2 py-1.5 border border-neutral-200 rounded hover:bg-neutral-50">
                    <span class="font-medium">Raditya</span> <span class="text-neutral-400">· Koordinator</span>
                </button>
            </div>
        </div>

    </div>

    <script>
        function fillDemo(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>