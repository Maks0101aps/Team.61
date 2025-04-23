<template>
    <button
        @click="toggleTheme"
        class="p-2 rounded-full transition-colors duration-200 flex items-center justify-center"
        :class="{
            'bg-gray-800 text-yellow-400 hover:bg-gray-700 hover:text-yellow-300': isDark,
            'bg-blue-100 text-blue-800 hover:bg-blue-200 hover:text-blue-900': !isDark
        }"
    >
        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';

const isDark = ref(false);


const forceRefresh = () => {
    // Force a complete page reload instead of just hiding and showing the body
    // This ensures all CSS is properly applied throughout the application
    window.location.reload();
};

const toggleTheme = () => {
    isDark.value = !isDark.value;
    
    
    const htmlEl = document.documentElement; 
    
    if (isDark.value) {
        htmlEl.classList.add('dark');
        htmlEl.classList.remove('bg-gray-100');
        htmlEl.classList.add('bg-gray-900');
        document.body.classList.add('dark-mode');
        
        
        const calendarElements = document.querySelectorAll('.calendar, [data-date], .fc-theme-standard td, .fc-theme-standard th, .fc-col-header, .fc-daygrid-body, .fc-scrollgrid');
        calendarElements.forEach(el => {
            el.style.backgroundColor = '#1F2937';
            el.style.color = '#F3F4F6';
            el.style.borderColor = '#374151';
        });
    } else {
        htmlEl.classList.remove('dark');
        htmlEl.classList.remove('bg-gray-900');
        htmlEl.classList.add('bg-gray-100');
        document.body.classList.remove('dark-mode');
        
        
        const calendarElements = document.querySelectorAll('.calendar, [data-date], .fc-theme-standard td, .fc-theme-standard th, .fc-col-header, .fc-daygrid-body, .fc-scrollgrid');
        calendarElements.forEach(el => {
            el.style.backgroundColor = '#FFFFFF';
            el.style.color = '#374151';
            el.style.borderColor = '#E5E7EB';
        });
    }
    
    
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
    
    
    forceRefresh();
    
    
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: isDark.value ? 'dark' : 'light' }));
    
    
    if (isDark.value) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
    }
};

onMounted(async () => {
    
    const htmlEl = document.documentElement;
    isDark.value = htmlEl.classList.contains('dark');

    
    const savedTheme = localStorage.getItem('theme') || 
                       (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    if ((savedTheme === 'dark' && !isDark.value) || (savedTheme === 'light' && isDark.value)) {
        toggleTheme();
    }
    
    
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            isDark.value = e.matches;
            toggleTheme();
        }
    });
});
</script> 