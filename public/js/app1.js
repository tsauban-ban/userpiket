// ========== MANAGE USERS JAVASCRIPT ==========

// DOM Elements
const addModal = document.getElementById('addUserModal');
const editModal = document.getElementById('editUserModal');
const importModal = document.getElementById('importModal');
const deleteModal = document.getElementById('deleteModal');

// ========== MODAL FUNCTIONS ==========

// Open Add Modal
function openAddModal() {
    if (addModal) {
        addModal.classList.remove('hidden');
        addModal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
}

function closeAddModal() {
    if (addModal) {
        addModal.classList.add('hidden');
        addModal.classList.remove('show');
        document.body.style.overflow = ''; // Restore scrolling
        
        // Reset form
        const form = addModal.querySelector('form');
        if (form) form.reset();
    }
}

// Open Edit Modal
function openEditModal(userId, userData) {
    if (!editModal) return;
    
    // Parse userData if it's a string
    if (typeof userData === 'string') {
        try {
            userData = JSON.parse(userData);
        } catch (e) {
            console.error('Error parsing user data:', e);
            return;
        }
    }
    
    const form = document.getElementById('editUserForm');
    const nameInput = document.getElementById('edit_name');
    const emailInput = document.getElementById('edit_email');
    const divisionSelect = document.getElementById('edit_division_id');
    
    if (form) form.action = '/manageusers/' + userId;
    if (nameInput) nameInput.value = userData.name || '';
    if (emailInput) emailInput.value = userData.email || '';
    if (divisionSelect) divisionSelect.value = userData.division_id || '';
    
    editModal.classList.remove('hidden');
    editModal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    if (editModal) {
        editModal.classList.add('hidden');
        editModal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Open Import Modal
function openImportModal() {
    if (importModal) {
        importModal.classList.remove('hidden');
        importModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeImportModal() {
    if (importModal) {
        importModal.classList.add('hidden');
        importModal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Open Delete Modal
function openDeleteModal(userId, userName) {
    if (!deleteModal) return;
    
    const userNameSpan = document.getElementById('deleteUserName');
    const deleteForm = document.getElementById('deleteForm');
    
    if (userNameSpan) userNameSpan.textContent = userName;
    if (deleteForm) deleteForm.action = '/manageusers/' + userId;
    
    deleteModal.classList.remove('hidden');
    deleteModal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    if (deleteModal) {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// ========== SEARCH FUNCTIONALITY ==========

// Clear search
function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        document.getElementById('searchForm').submit();
    }
}

// Debounce search (search after user stops typing)
let searchTimeout;
const searchInput = document.getElementById('searchInput');

if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2 || this.value.length === 0) {
                document.getElementById('searchForm').submit();
            }
        }, 500);
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
}

// ========== CLOSE MODAL WHEN CLICKING OUTSIDE ==========

window.onclick = function(event) {
    if (event.target === addModal) closeAddModal();
    if (event.target === editModal) closeEditModal();
    if (event.target === importModal) closeImportModal();
    if (event.target === deleteModal) closeDeleteModal();
}

// ========== CLOSE MODAL WITH ESCAPE KEY ==========

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddModal();
        closeEditModal();
        closeImportModal();
        closeDeleteModal();
    }
});

// ========== INITIALIZATION ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('Manage Users JS loaded - Poppins font enabled');
    
    // Add material symbols classes to buttons if needed
    const materialButtons = document.querySelectorAll('[data-material-icon]');
    materialButtons.forEach(btn => {
        const iconName = btn.getAttribute('data-material-icon');
        if (iconName) {
            const span = document.createElement('span');
            span.className = 'material-symbols-outlined';
            span.textContent = iconName;
            btn.prepend(span);
        }
    });
});