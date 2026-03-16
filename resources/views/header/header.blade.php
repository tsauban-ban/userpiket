<header class="w-full bg-white shadow-sm">
    <div class="flex items-center justify-between px-8 py-4">
        <div class="flex items-center gap-3">
           <div class="w-12 h-12 flex items-center justify-center">
            <span class="material-symbols-outlined text-[36px]">
            person_shield
            </span>
            </div>


            <div>
                <h1 class="font-semibold text-[#004643] text-[22px]">
                    Admin Dashboard
                </h1>
                <p class="text-sm text-gray-500 text-[14px] ">
                    Piket App
                </p>
            </div>
        </div>

         <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf            <button type="submit" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

        </form>
    </div>

     
</header>
