<?php
// Future: Community page functionality will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community • Static Chef</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-amber-600 transition-colors">&lt; Static Chef /&gt;</a>
                <div class="hidden md:flex space-x-8">
                    <a href="menu.php" class="text-gray-700 hover:text-amber-600 transition-colors">Моите рецепти</a>
                    <a href="community.php" class="text-amber-600 font-semibold">Вашите рецепти</a>
                    <a href="contact.php" class="text-gray-700 hover:text-amber-600 transition-colors">Контакт</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex items-center justify-center min-h-[calc(100vh-80px)] px-4 py-10">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
                <div class="mb-6">
                    <svg class="mx-auto h-16 w-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">Общност - очаквайте скоро!</h1>
                <p class="text-lg md:text-xl text-gray-700 leading-relaxed">
                Тази страница съвсем скоро ще се превърне в място, където потребителите ще могат да си създават акаунти и да публикуват свои рецепти!                </p>
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                    Очаквайте вълнуващи функции за общността, чрез които ще можете да споделяте своите кулинарни творения с останалите.                     </p>
                </div>
            </div>
        </div>
    </main>

    <canvas id="bg"></canvas>
    <script type="module">
        import { initTableclothBackground } from './assets/js/tablecloth-bg.js';
        initTableclothBackground({
            colorLight: "#FFE2BF",
            colorDark: "#FED5A8",
            gridSize: 40,
            mouseRadius: 218,
            waveSpeed: 9,
            opacity: 0.88
        });
    </script>
</body>
</html>

