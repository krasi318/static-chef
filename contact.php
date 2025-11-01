<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакт & За мен • Static Chef</title>
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
                    <a href="community.php" class="text-gray-700 hover:text-amber-600 transition-colors">Вашите рецепти</a>
                    <a href="contact.php" class="text-amber-600 font-semibold">Контакт</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Content Card -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-8 md:p-12 mt-8">
                <!-- Header -->
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-8 text-center">
                    Контакт 🍳
                </h1>

                <!-- Contact Section -->
                <div class="pt-4">
                    <div class="space-y-4">
                        <!-- Website -->
                        <a href="https://www.krasetoo.me" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center md:justify-start gap-4 p-4 bg-gray-50 hover:bg-amber-50 rounded-xl transition-all duration-300 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            <span class="text-lg text-gray-700 group-hover:text-amber-700 font-medium">Portfolio</span>
                        </a>

                        <!-- GitHub -->
                        <a href="https://github.com/krasi318" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center md:justify-start gap-4 p-4 bg-gray-50 hover:bg-amber-50 rounded-xl transition-all duration-300 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-amber-600 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-lg text-gray-700 group-hover:text-amber-700 font-medium">GitHub</span>
                        </a>

                        <!-- Email -->
                        <a href="mailto:g.krasetoo@gmail.com" class="flex items-center justify-center md:justify-start gap-4 p-4 bg-gray-50 hover:bg-amber-50 rounded-xl transition-all duration-300 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-lg text-gray-700 group-hover:text-amber-700 font-medium">Имейл: g.krasetoo@gmail.com</span>
                        </a>

                        <!-- Discord -->
                        <a href="https://discord.com/users/313223079616577539" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center md:justify-start gap-4 p-4 bg-gray-50 hover:bg-amber-50 rounded-xl transition-all duration-300 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-amber-600 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                            </svg>
                            <span class="text-lg text-gray-700 group-hover:text-amber-700 font-medium">Discord</span>
                        </a>
                    </div>
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

