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

// Функция для принудительного обновления стилей страницы
const forceRefresh = () => {
    // Создаем стиль и добавляем его, затем удаляем, чтобы заставить браузер перерисовать страницу
    const style = document.createElement('style');
    document.head.appendChild(style);
    document.head.removeChild(style);
    
    // Установим display: none и сразу вернем, чтобы избежать мигания
    document.body.style.display = 'none';
    setTimeout(() => {
        document.body.style.display = '';
    }, 5);
};

const toggleTheme = () => {
    isDark.value = !isDark.value;
    
    // Применяем тему сразу ко всем ключевым элементам
    const htmlEl = document.documentElement; // html tag
    
    if (isDark.value) {
        htmlEl.classList.add('dark');
        htmlEl.classList.remove('bg-gray-100');
        htmlEl.classList.add('bg-gray-900');
        document.body.classList.add('dark-mode');
        
        // Специфические фиксы для календаря в темной теме
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
        
        // Специфические фиксы для календаря в светлой теме
        const calendarElements = document.querySelectorAll('.calendar, [data-date], .fc-theme-standard td, .fc-theme-standard th, .fc-col-header, .fc-daygrid-body, .fc-scrollgrid');
        calendarElements.forEach(el => {
            el.style.backgroundColor = '#FFFFFF';
            el.style.color = '#374151';
            el.style.borderColor = '#E5E7EB';
        });
    }
    
    // Сохраняем выбор пользователя
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
    
    // Принудительно обновляем стили
    forceRefresh();
    
    // Отправляем событие изменения темы для других компонентов
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: isDark.value ? 'dark' : 'light' }));
    
    // Также установим или удалим атрибут data-theme для компонентов, которые могут его использовать
    if (isDark.value) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
    }
};

onMounted(async () => {
    // Проверяем текущее состояние темы
    const htmlEl = document.documentElement;
    isDark.value = htmlEl.classList.contains('dark');

    // Загружаем сохраненную тему при монтировании компонента
    const savedTheme = localStorage.getItem('theme') || 
                       (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    if ((savedTheme === 'dark' && !isDark.value) || (savedTheme === 'light' && isDark.value)) {
        toggleTheme();
    }
    
    // Слушаем события системных предпочтений
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            isDark.value = e.matches;
            toggleTheme();
        }
    });
});
</script> 