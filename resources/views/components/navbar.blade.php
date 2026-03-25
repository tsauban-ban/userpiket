@php
    $user = Auth::user();
    $isAdmin = $user && $user->role == 'admin';
@endphp

<div class="w-full flex justify-center mt-8">
    <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-[15px] shadow-md">
        
        @if($isAdmin)
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

        <!-- Notification -->
        <a href="{{ route('admin.notification.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('admin.notification*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">notifications</span>
            <span>Notification</span>
        </a>
        @else

        <!-- Dashboard untuk user biasa -->
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('dashboard') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">home</span>
            <span>Dashboard</span>
        </a>

        <!-- 🔥 PIKET SAYA -->
        <a href="{{ route('user.picket.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('user.picket*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">assignment</span>
            <span>Piket Saya</span>
        </a>

        <!-- 🔔 NOTIFIKASI (opsional biar lengkap) -->
        <a href="{{ route('user.notifications.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('user.notifications*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
                  text-sm font-medium">
            <span class="material-symbols-outlined text-base">notifications</span>
            <span>Notifikasi</span>
        </a>
        
        @endif

    </div>
</div>