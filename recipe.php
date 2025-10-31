<?php
require_once __DIR__ . '/db.php';

header('Content-Type: text/html; charset=UTF-8');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recipe = null;
$ingredients = [];
$images = [];

if ($id > 0) {
    // Fetch recipe
    $stmt = $conn->prepare('SELECT id, title, description FROM recipes WHERE id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $recipe = $result->fetch_assoc();
        }
        $stmt->close();
    }

    if ($recipe) {
        // Ingredients with quantities
        $stmtIng = $conn->prepare('SELECT i.name, ri.quantity
                                   FROM recipe_ingredients ri
                                   JOIN ingredients i ON i.id = ri.ingredient_id
                                   WHERE ri.recipe_id = ?
                                   ORDER BY i.name ASC');
        if ($stmtIng) {
            $stmtIng->bind_param('i', $id);
            $stmtIng->execute();
            $resIng = $stmtIng->get_result();
            while ($row = $resIng->fetch_assoc()) {
                $ingredients[] = $row;
            }
            $stmtIng->close();
        }

        // Images
        $stmtImg = $conn->prepare('SELECT image_path FROM recipe_images WHERE recipe_id = ? ORDER BY id ASC');
        if ($stmtImg) {
            $stmtImg->bind_param('i', $id);
            $stmtImg->execute();
            $resImg = $stmtImg->get_result();
            while ($row = $resImg->fetch_assoc()) {
                $images[] = $row['image_path'];
            }
            $stmtImg->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe • Static Chef</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-amber-600 transition-colors">&lt; Static Chef /&gt;</a>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php#about" class="text-gray-700 hover:text-amber-600 transition-colors">За мен</a>
                    <a href="menu.php" class="text-amber-600 font-semibold">Моите рецепти</a>
                    <a href="community.php" class="text-gray-700 hover:text-amber-600 transition-colors">Вашите рецепти</a>
                    <a href="contact.php" class="text-gray-700 hover:text-amber-600 transition-colors">Контакт</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10 px-4">
        <div id="recipeContainer" class="max-w-6xl mx-auto">
<?php if (!$recipe): ?>
            <div class="text-center py-24">
                <h1 class="text-3xl font-bold text-gray-700 mb-2">Грешка 404</h1>
                <p class="text-gray-500">Не можах да намеря рецептата :(<?php echo htmlspecialchars((string)$id); ?>.</p>
                <a href="menu.php" class="inline-block mt-6 px-6 py-3 rounded-full bg-amber-600 text-white hover:bg-amber-700">Назад</a>
            </div>
<?php else: ?>
            <div class="max-w-5xl mx-auto">
                <a href="menu.php" class="inline-flex items-center text-amber-700 hover:text-amber-800 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Назад
                </a>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative max-w-4xl mx-auto my-6">
                        <img id="mainRecipeImage" src="<?php echo htmlspecialchars($images[0] ?? 'assets/img/tabletop.png'); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="main-recipe-image rounded-xl w-full max-h-[450px] object-cover shadow-lg cursor-pointer hover:opacity-95 transition-opacity duration-500" style="opacity: 1;" />
<?php if (count($images) > 1): ?>
                        <button id="prevImg" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white text-3xl px-3 py-2 rounded-full transition-all duration-200 opacity-80 hover:opacity-100 md:opacity-60 md:hover:opacity-100 z-10">‹</button>
                        <button id="nextImg" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white text-3xl px-3 py-2 rounded-full transition-all duration-200 opacity-80 hover:opacity-100 md:opacity-60 md:hover:opacity-100 z-10">›</button>
<?php endif; ?>
                    </div>
                    <div class="p-6 md:p-10">
                        <div class="mb-4">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800"><?php echo htmlspecialchars($recipe['title']); ?></h1>
                        </div>
                        <div class="text-gray-700 mb-8"><?php echo nl2br(htmlspecialchars($recipe['description'] ?? '')); ?></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">Съставки</h2>
                                <ul class="list-disc list-inside space-y-2 text-gray-700">
<?php foreach ($ingredients as $ing): ?>
                                    <li>• <?php echo htmlspecialchars($ing['name']); ?> — <?php echo htmlspecialchars($ing['quantity'] ?? ''); ?></li>
<?php endforeach; ?>
                                </ul>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">Стъпки</h2>
                                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                                    <li>Enjoy your meal!</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php endif; ?>
        </div>
    </main>

    <!-- Lightbox Overlay -->
    <div id="lightbox" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 transition-opacity duration-300">
        <button id="prevBtn" class="absolute left-6 text-white text-4xl font-bold cursor-pointer hover:text-amber-400 transition-colors z-10 opacity-80 hover:opacity-100">‹</button>
        <img id="lightboxImage" class="max-h-[90vh] max-w-[90vw] object-contain transition-transform duration-300 cursor-zoom-in" />
        <button id="nextBtn" class="absolute right-6 text-white text-4xl font-bold cursor-pointer hover:text-amber-400 transition-colors z-10 opacity-80 hover:opacity-100">›</button>
        <button id="closeBtn" class="absolute top-4 right-6 text-white text-3xl font-bold cursor-pointer hover:text-amber-400 transition-colors z-10">✖</button>
    </div>

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

        // Recipe Image Viewer and Lightbox functionality
        (function() {
            const recipeImages = <?php echo json_encode($images); ?>;
            if (!recipeImages || recipeImages.length === 0) return;

            const mainRecipeImage = document.getElementById('mainRecipeImage');
            const prevImgBtn = document.getElementById('prevImg');
            const nextImgBtn = document.getElementById('nextImg');
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            const closeBtn = document.getElementById('closeBtn');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentIndex = 0;
            let isZoomed = false;
            let isTransitioning = false;

            // Show/hide navigation buttons based on image count
            function updateNavigation() {
                if (recipeImages.length <= 1) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                } else {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'block';
                }
            }

            // Update main image with fade transition
            function updateMainImage(index, direction = 'next') {
                if (isTransitioning) return;
                isTransitioning = true;
                
                // Fade out
                mainRecipeImage.style.opacity = '0';
                
                setTimeout(() => {
                    currentIndex = index;
                    mainRecipeImage.src = recipeImages[currentIndex];
                    mainRecipeImage.alt = '<?php echo htmlspecialchars($recipe['title'] ?? 'Recipe'); ?> - Image ' + (currentIndex + 1);
                    
                    // Fade in
                    mainRecipeImage.style.opacity = '1';
                    isTransitioning = false;
                }, 250);
            }

            // Navigate to next image in main viewer
            function nextMainImage() {
                if (recipeImages.length <= 1) return;
                const nextIndex = (currentIndex + 1) % recipeImages.length;
                updateMainImage(nextIndex, 'next');
            }

            // Navigate to previous image in main viewer
            function prevMainImage() {
                if (recipeImages.length <= 1) return;
                const prevIndex = (currentIndex - 1 + recipeImages.length) % recipeImages.length;
                updateMainImage(prevIndex, 'prev');
            }

            // Open lightbox - syncs with current main image index
            function openLightbox(index = null) {
                if (index !== null) {
                    currentIndex = index;
                }
                isZoomed = false;
                lightboxImage.style.transform = 'scale(1)';
                lightboxImage.style.cursor = 'zoom-in';
                
                const imageUrl = recipeImages[currentIndex];
                lightboxImage.src = imageUrl;
                lightboxImage.alt = 'Recipe image ' + (currentIndex + 1);
                
                lightbox.classList.remove('hidden');
                lightbox.style.opacity = '0';
                requestAnimationFrame(() => {
                    lightbox.style.opacity = '1';
                });
                
                updateNavigation();
                document.body.style.overflow = 'hidden';
            }

            // Close lightbox
            function closeLightbox() {
                lightbox.style.opacity = '0';
                setTimeout(() => {
                    lightbox.classList.add('hidden');
                    isZoomed = false;
                    document.body.style.overflow = '';
                }, 300);
            }

            // Navigate to next image in lightbox (syncs with main viewer)
            function nextImage() {
                if (recipeImages.length <= 1) return;
                currentIndex = (currentIndex + 1) % recipeImages.length;
                isZoomed = false;
                lightboxImage.style.transform = 'scale(1)';
                lightboxImage.style.cursor = 'zoom-in';
                lightboxImage.src = recipeImages[currentIndex];
                
                // Sync main image if lightbox is open
                if (!lightbox.classList.contains('hidden')) {
                    mainRecipeImage.src = recipeImages[currentIndex];
                }
            }

            // Navigate to previous image in lightbox (syncs with main viewer)
            function prevImage() {
                if (recipeImages.length <= 1) return;
                currentIndex = (currentIndex - 1 + recipeImages.length) % recipeImages.length;
                isZoomed = false;
                lightboxImage.style.transform = 'scale(1)';
                lightboxImage.style.cursor = 'zoom-in';
                lightboxImage.src = recipeImages[currentIndex];
                
                // Sync main image if lightbox is open
                if (!lightbox.classList.contains('hidden')) {
                    mainRecipeImage.src = recipeImages[currentIndex];
                }
            }

            // Toggle zoom
            function toggleZoom() {
                isZoomed = !isZoomed;
                if (isZoomed) {
                    lightboxImage.style.transform = 'scale(1.5)';
                    lightboxImage.style.cursor = 'zoom-out';
                } else {
                    lightboxImage.style.transform = 'scale(1)';
                    lightboxImage.style.cursor = 'zoom-in';
                }
            }

            // Click handler for main recipe image - opens lightbox with current index
            mainRecipeImage.addEventListener('click', (e) => {
                e.preventDefault();
                openLightbox(); // Uses currentIndex
            });

            // Navigation buttons for main image viewer
            if (prevImgBtn && nextImgBtn) {
                prevImgBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    prevMainImage();
                });

                nextImgBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    nextMainImage();
                });
            }

            // Close button
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeLightbox();
            });

            // Close on overlay click
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox || e.target.id === 'lightbox') {
                    closeLightbox();
                }
            });

            // Image click to toggle zoom (don't close)
            lightboxImage.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleZoom();
            });

            // Navigation buttons
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                prevImage();
            });

            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                nextImage();
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (lightbox.classList.contains('hidden')) return;

                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        prevImage();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        nextImage();
                        break;
                }
            });

            // Initialize navigation visibility
            updateNavigation();
        })();
    </script>
</body>
</html>


