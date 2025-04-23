<!DOCTYPE html>
<html class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=2.0">
    
    <!-- Theme initialization script - runs before page load -->
    <script>
        (function() {
            // Проверка сохраненной темы или системных предпочтений
            const savedTheme = localStorage.getItem('theme') || 
                             (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            // Быстрое применение темы к HTML элементу
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('bg-gray-100');
                document.documentElement.classList.add('bg-gray-900');
                document.documentElement.setAttribute('data-theme', 'dark');
                
                // Добавляем встроенные стили для мгновенного применения темной темы
                const style = document.createElement('style');
                style.textContent = `
                    body, html { background-color: #111827 !important; color: #F3F4F6 !important; }
                    .bg-white, .bg-gray-100, .bg-gray-50 { background-color: #1F2937 !important; }
                    .text-gray-700, .text-gray-800, .text-gray-900 { color: #F3F4F6 !important; }
                    .calendar, table, [data-date] { background-color: #1F2937 !important; color: #F3F4F6 !important; border-color: #374151 !important; }
                `;
                document.head.appendChild(style);
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.classList.add('bg-gray-100');
                
                // Добавляем встроенные стили для мгновенного применения светлой темы
                const style = document.createElement('style');
                style.textContent = `
                    body, html { background-color: #F3F4F6 !important; color: #374151 !important; }
                    .calendar, [data-date], .fc-theme-standard td, .fc-theme-standard th { 
                        background-color: #FFFFFF !important; 
                        color: #374151 !important; 
                        border-color: #E5E7EB !important; 
                    }
                `;
                document.head.appendChild(style);
            }
        })();
    </script>

    {{-- Inertia --}}
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>

    {{-- School Calendar --}}
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="font-sans leading-none text-gray-700 antialiased dark:text-gray-200 dark:bg-gray-900">
    @inertia
</body>
</html>
