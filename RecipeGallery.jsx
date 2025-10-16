import React, { useState, useMemo } from 'react';

const RecipeGallery = () => {
  const [selectedCategory, setSelectedCategory] = useState('All');
  const [searchQuery, setSearchQuery] = useState('');

  // Dummy recipe data
  const recipes = [
    {
      id: 1,
      title: "Grandma's Apple Pie",
      category: "Dessert",
      prepTime: "45 min",
      image: "https://images.unsplash.com/photo-1571115764595-644a1f56a55c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 2,
      title: "Cozy Chicken Soup",
      category: "Soups",
      prepTime: "30 min",
      image: "https://images.unsplash.com/photo-1547592166-23ac45744acd?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 3,
      title: "Homemade Pasta",
      category: "Pasta",
      prepTime: "60 min",
      image: "https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: true
    },
    {
      id: 4,
      title: "Chocolate Chip Cookies",
      category: "Dessert",
      prepTime: "25 min",
      image: "https://images.unsplash.com/photo-1499636136210-6f4ee6a87fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 5,
      title: "Mediterranean Salad",
      category: "Appetizers",
      prepTime: "15 min",
      image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 6,
      title: "Beef Stew",
      category: "Soups",
      prepTime: "90 min",
      image: "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 7,
      title: "Turkey Sandwich",
      category: "Sandwiches",
      prepTime: "10 min",
      image: "https://images.unsplash.com/photo-1539252554453-80ab65ce3586?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    },
    {
      id: 8,
      title: "Roasted Turkey",
      category: "Thanksgiving",
      prepTime: "180 min",
      image: "https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: true
    },
    {
      id: 9,
      title: "Spaghetti Carbonara",
      category: "Pasta",
      prepTime: "35 min",
      image: "https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
      isPrivate: false
    }
  ];

  const categories = ['All', 'Appetizers', 'Pasta', 'Soups', 'Sandwiches', 'Dessert', 'Thanksgiving'];

  // Filter recipes based on category and search query
  const filteredRecipes = useMemo(() => {
    return recipes.filter(recipe => {
      const matchesCategory = selectedCategory === 'All' || recipe.category === selectedCategory;
      const matchesSearch = recipe.title.toLowerCase().includes(searchQuery.toLowerCase());
      return matchesCategory && matchesSearch;
    });
  }, [selectedCategory, searchQuery]);

  return (
    <div className="min-h-screen relative overflow-hidden">
      {/* Animated Tablecloth Background */}
      <div className="absolute inset-0 opacity-30">
        <div className="tablecloth-pattern"></div>
      </div>
      
      {/* Main Content */}
      <div className="relative z-10 max-w-6xl mx-auto px-4 py-12">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Recipe Gallery
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Discover delicious recipes from our collection. Filter by category or search for your favorite dishes.
          </p>
        </div>

        {/* Category Filter */}
        <div className="flex flex-wrap justify-center gap-2 mb-8">
          {categories.map((category) => (
            <button
              key={category}
              onClick={() => setSelectedCategory(category)}
              className={`px-6 py-3 rounded-full font-medium transition-all duration-300 transform hover:scale-105 ${
                selectedCategory === category
                  ? 'bg-amber-600 text-white shadow-lg'
                  : 'bg-white text-gray-700 hover:bg-amber-50 border border-gray-200'
              }`}
            >
              {category}
            </button>
          ))}
        </div>

        {/* Search Bar */}
        <div className="max-w-md mx-auto mb-12">
          <div className="relative">
            <input
              type="text"
              placeholder="Search recipes..."
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
            <div
              key={recipe.id}
              className="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden group"
            >
              {/* Recipe Image */}
              <div className="relative h-48 overflow-hidden">
                <img
                  src={recipe.image}
                  alt={recipe.title}
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                {recipe.isPrivate && (
                  <div className="absolute top-4 right-4">
                    <div className="bg-amber-600 text-white p-2 rounded-full shadow-lg">
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
                      </svg>
                    </div>
                  </div>
                )}
              </div>

              {/* Recipe Content */}
              <div className="p-6">
                <h3 className="text-xl font-bold text-gray-800 mb-3 group-hover:text-amber-600 transition-colors duration-300">
                  {recipe.title}
                </h3>
                
                <div className="flex items-center justify-between">
                  <div className="flex items-center text-gray-600">
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span className="text-sm font-medium">{recipe.prepTime}</span>
                  </div>
                  
                  <span className="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded-full">
                    {recipe.category}
                  </span>
                </div>
              </div>
            </div>
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

      {/* Custom CSS for tablecloth pattern */}
      <style jsx>{`
        .tablecloth-pattern {
          background-image: 
            linear-gradient(45deg, #f5f5dc 25%, transparent 25%),
            linear-gradient(-45deg, #f5f5dc 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #e6e6d4 75%),
            linear-gradient(-45deg, transparent 75%, #e6e6d4 75%);
          background-size: 20px 20px;
          background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
          animation: tablecloth-shift 8s ease-in-out infinite;
        }

        @keyframes tablecloth-shift {
          0%, 100% { 
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            opacity: 0.3;
          }
          50% { 
            background-position: 5px 5px, 5px 15px, 15px -5px, -5px 5px;
            opacity: 0.4;
          }
        }

        .tablecloth-pattern:hover {
          animation-duration: 4s;
        }
      `}</style>
    </div>
  );
};

export default RecipeGallery;
