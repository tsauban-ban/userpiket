
@php
    try {
        $unreadNotifications = Auth::user()->unreadNotifications;
        $unreadCount = $unreadNotifications->count();
    } catch (\Exception $e) {
        $unreadCount = 0;
    }
@endphp

<div class="w-full flex justify-between items-center mt-8 px-8">
    

    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-[#004643]">assignment</span>
        <span class="text-xl font-semibold text-[#004643]">Piket App</span>
        
    </div>

    

    <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-[15px] shadow-md">
        
        <!-- Picket Journal -->
        <a href="{{ route('admin.picketjournal.index') }}"  
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('admin.picketjournal*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">assignment</span>
            <span>Picket Journal</span>
        </a>

        <!-- Manage Users -->
        <a href="{{ route('manageusers.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('manageusers*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">group</span>
            <span>Manage Users</span>
        </a>

        <!-- Manage Division -->
        <a href="{{ route('divisions.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('divisions*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">manage_accounts</span>
            <span>Manage Division</span>
        </a>

        <!-- Notification Admin -->
        @if(Auth::user()->role == 'admin')
        <a href="{{ route('admin.notification.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('admin.notification*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium relative">
            <span class="material-symbols-outlined text-base">notifications</span>
            <span>Notification</span>
            @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
            @endif
        </a>
        @endif

    </div>

    

    <div>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-[10px] hover:bg-red-600 transition duration-200">
                <span class="material-symbols-outlined text-base">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Tambahkan Alpine.js untuk dropdown jika diperlukan -->
<script src="//unpkg.com/alpinejs" defer></script>