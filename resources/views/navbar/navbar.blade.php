<!-- resources/views/components/navbar.blade.php -->
<div class="w-full flex justify-center mt-8">
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
        <a href="{{ route('admin.division.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-[10px] transition duration-200 
                  {{ request()->routeIs('admin.division*') ? 'bg-[#F9BC60] text-[#004643]' : 'hover:bg-[#F9BC60] text-[#004643]' }} 
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

    </div>
</div>