<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Gallery - Static Chef</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- React and ReactDOM CDN -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <!-- Babel Standalone for JSX transformation -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    
    <style>
        /* Custom hover effects */
        .recipe-card {
            transition: all 0.3s ease;
        }

        .recipe-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .recipe-image {
            transition: transform 0.5s ease;
        }

        .recipe-card:hover .recipe-image {
            transform: scale(1.1);
        }

        .category-btn {
            transition: all 0.3s ease;
        }

        .category-btn:hover {
            transform: scale(1.05);
        }

        .category-btn.active {
            background-color: #d97706;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
<?php /* allow PHP if needed later */ ?>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-amber-600 transition-colors">
                    &lt; Static Chef /&gt;
                </a>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php#about" class="text-gray-700 hover:text-amber-600 transition-colors">За мен</a>
                    <a href="menu.php" class="text-amber-600 font-semibold">Моите рецепти</a>
                    <a href="community.php" class="text-gray-700 hover:text-amber-600 transition-colors">Вашите рецепти</a>
                    <a href="contact.php" class="text-gray-700 hover:text-amber-600 transition-colors">Контакт</a>
                </div>
                <!-- Mobile menu button -->
                <button class="md:hidden text-gray-700 hover:text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>


    <!-- Main Content with padding for fixed nav -->
    <div class="pt-20">
        <!-- Recipe Gallery Component will be rendered here -->
        <div id="recipe-gallery-root"></div>
        </div>

    <!-- RecipeGallery JSX Component -->
    <script type="text/babel">
        const { useState, useMemo, useEffect, useRef } = React;

        const RecipeGallery = () => {
            const [protein, setProtein] = useState('all'); // all|chicken|beef|pork|vegetarian
            const [searchQuery, setSearchQuery] = useState('');
            const [recipes, setRecipes] = useState([]);

            const fetchRecipes = async (proteinFilter) => {
                const params = new URLSearchParams();
                params.set('limit', 'all');
                if (proteinFilter && proteinFilter !== 'all') params.set('protein', proteinFilter);
                try {
                    const res = await fetch(`get_recipes.php?${params.toString()}`, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    const normalized = (Array.isArray(data) ? data : []).map(item => ({
                        id: item.id,
                        title: item.title,
                        category: proteinFilter === 'all' ? 'Recipe' : proteinFilter,
                        prepTime: item.prepTime || '—',
                        image_path: item.image_path,
                        isPrivate: false
                    }));
                    setRecipes(normalized);
                } catch (e) {
                    setRecipes([]);
                }
            };

            useEffect(() => {
                fetchRecipes(protein);
            }, [protein]);

            const categories = useMemo(() => ['Всички', 'Пиле', 'Телешко', 'Свинско', 'Зеленчуци'], []);

            // Filter recipes based on search query (server handles protein)
            const filteredRecipes = useMemo(() => {
                return recipes.filter(recipe => {
                    const matchesSearch = (recipe.title || '').toLowerCase().includes(searchQuery.toLowerCase());
                    return matchesSearch;
                });
            }, [recipes, searchQuery]);

            return (
                <div className="min-h-screen relative overflow-hidden">
                    {/* Main Content */}
                    <div className="relative z-10 max-w-6xl mx-auto px-4 py-12">
                        {/* Header */}
                        <div className="text-center mb-12">
                            <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                                Рецептите:
                            </h1>
                            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                                Тук ще откриеш рецепти, които наистина си заслужават — лесни, вкусни и изпитани в моята кухня.
                            </p>
                        </div>

                        {/* Protein Filter */}
                        <div className="flex flex-wrap justify-center gap-2 mb-8">
                            {categories.map((cat) => (
                                <button
                                    key={cat}
                                    onClick={() => setProtein(cat)}
                                    className={`px-6 py-3 rounded-full font-medium transition-all duration-300 transform hover:scale-105 ${
                                        protein === cat
                                            ? 'bg-amber-600 text-white shadow-lg'
                                            : 'bg-white text-gray-700 hover:bg-amber-50 border border-gray-200'
                                    }`}
                                >
                                    {cat.charAt(0).toUpperCase() + cat.slice(1)}
                                </button>
                            ))}
                        </div>

                        {/* Search Bar */}
                        <div className="max-w-md mx-auto mb-12">
                            <div className="relative">
                                <input
                                    type="text"
                                    placeholder="Потърси рецепта..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full px-6 py-4 pl-12 pr-4 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-lg"
                                />
                                <div className="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {/* Recipe Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {filteredRecipes.map((recipe) => (
                                <a
                                    key={recipe.id}
                                    href={`recipe.php?id=${recipe.id}`}
                                    className="block"
                                >
                                    <div
                                        className="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden group"
                                    >
                                    {/* Recipe Image */}
                                    <div className="relative h-48 overflow-hidden">
                                        <img
                                            src={recipe.image_path || 'assets/img/tabletop.png'}
                                            alt={recipe.title}
                                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        />
                                    </div>

                                    {/* Recipe Content */}
                                    <div className="p-6">
                                        <h3 className="text-xl font-bold text-gray-800 mb-3 group-hover:text-amber-600 transition-colors duration-300">
                                            {recipe.title}
                                        </h3>
                                        
                                        <div className="flex items-center justify-between">
                                            
                                            
                                           
                        </div>
                    </div>
                                    </div>
                                </a>
                            ))}
                        </div>

                        {/* No Results Message */}
                        {filteredRecipes.length === 0 && (
                            <div className="text-center py-12">
                                <div className="text-6xl mb-4">🔍</div>
                                <h3 className="text-2xl font-bold text-gray-600 mb-2">No recipes found</h3>
                                <p className="text-gray-500">Try adjusting your search or filter criteria</p>
                            </div>
                        )}
                    </div>
                </div>
            );
        };

        // Main App Component that wraps everything
        const App = () => {
            return (
                <div className="relative">
                    <RecipeGallery />
                </div>
            );
        };

        // Render the component
        const root = ReactDOM.createRoot(document.getElementById('recipe-gallery-root'));
        root.render(<App />);
    </script>

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


