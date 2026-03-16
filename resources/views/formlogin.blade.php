<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Login - Piket App</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Poppins']">
    <div class="flex justify-center items-center h-screen bg-[#ABD1C6]">
        <div class="w-96 p-8 shadow-lg bg-white rounded-[10px]">
            <h1 class="text-3xl block text-center font-bold text-[#004643]">Piket App</h1>
            <p class="text-center block text-[#078080] font-medium">Login To Your Account</p>
            
            <!-- Menampilkan error jika login gagal -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mt-4 mb-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Menampilkan success message (misalnya setelah logout) -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mt-4 mb-4">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-5">
                @csrf <!-- Token keamanan Laravel -->
                
                <div class="mb-4">
                    <label for="email" class="block text-base mb-1 font-medium text-[#004643]">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email" 
                        class="border w-full border-[#D9D9D9] text-base px-3 py-2 rounded-xl focus:outline-none focus:ring-0 focus:border-[#0D3130] placeholder-[#0D3130] @error('email') border-red-500 @enderror" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-base mb-1 font-medium text-[#004643]">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="Enter your password" 
                        class="border w-full border-[#D9D9D9] text-base px-3 py-2 rounded-xl focus:outline-none focus:ring-0 focus:border-[#0D3130] placeholder-[#0D3130] @error('password') border-red-500 @enderror" 
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center mb-4">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="h-4 w-4 text-[#F9BC60] focus:ring-[#F9BC60] border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-[#004643]">
                        Remember Me
                    </label>
                </div>

                <div class="mt-6">
                    <button 
                        type="submit" 
                        class="border-2 border-[#F9BC60] bg-[#F9BC60] text-[#0D3130] py-2 w-full rounded-xl hover:bg-transparent hover:bg-[#F9BC60] font-semibold transition duration-200 ease-in-out transform hover:scale-105"
                    >
                        Login
                    </button>
                </div>

                <!-- Link ke Landing Page -->
                <div class="mt-4 text-center">
                    <a href="{{ route('landing') }}" class="text-sm text-[#078080] hover:text-[#004643] transition">
                        ← Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>