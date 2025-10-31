<?php
require_once __DIR__ . '/db.php';

header('Content-Type: text/html; charset=UTF-8');

$key = isset($_GET['key']) ? $_GET['key'] : null;
if ($key !== 'krasi123') {
    echo 'Access denied.';
    exit;
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $ingredientsRaw = isset($_POST['ingredients']) ? $_POST['ingredients'] : '';
    $quantitiesMap = isset($_POST['quantities']) && is_array($_POST['quantities']) ? $_POST['quantities'] : [];

    if ($title === '') {
        $errorMessage = 'Title is required';
    } else {
        // Normalize ingredients: split, trim, deduplicate, remove empties
        $ingredientsList = array_filter(array_unique(array_map(function ($v) {
            return trim($v);
        }, explode(',', $ingredientsRaw))), function ($v) {
            return $v !== '';
        });

        // Use transaction for atomicity
        $conn->begin_transaction();
        try {
            // Insert recipe
            $stmtInsertRecipe = $conn->prepare('INSERT INTO recipes (title, description) VALUES (?, ?)');
            if (!$stmtInsertRecipe) { throw new Exception('Failed to prepare recipe insert'); }
            $stmtInsertRecipe->bind_param('ss', $title, $description);
            if (!$stmtInsertRecipe->execute()) { throw new Exception('Failed to execute recipe insert'); }
            $recipeId = $conn->insert_id;
            $stmtInsertRecipe->close();

            // Prepare statements for ingredient operations
            $stmtFindIngredient = $conn->prepare('SELECT id FROM ingredients WHERE name = ?');
            if (!$stmtFindIngredient) { throw new Exception('Failed to prepare ingredient select'); }

            $stmtInsertIngredient = $conn->prepare('INSERT INTO ingredients (name) VALUES (?)');
            if (!$stmtInsertIngredient) { throw new Exception('Failed to prepare ingredient insert'); }

            $stmtLink = $conn->prepare('INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity) VALUES (?, ?, ?)');
            if (!$stmtLink) { throw new Exception('Failed to prepare recipe_ingredients insert'); }

            foreach ($ingredientsList as $ingredientName) {
                // Lookup ingredient
                $stmtFindIngredient->bind_param('s', $ingredientName);
                if (!$stmtFindIngredient->execute()) { throw new Exception('Failed to execute ingredient select'); }
                $result = $stmtFindIngredient->get_result();

                if ($result && $row = $result->fetch_assoc()) {
                    $ingredientId = (int)$row['id'];
                } else {
                    // Insert ingredient
                    $stmtInsertIngredient->bind_param('s', $ingredientName);
                    if (!$stmtInsertIngredient->execute()) { throw new Exception('Failed to execute ingredient insert'); }
                    $ingredientId = $conn->insert_id;
                }

                // Link recipe to ingredient with quantity
                $quantityValue = '';
                if (isset($quantitiesMap[$ingredientName])) {
                    $quantityValue = trim((string)$quantitiesMap[$ingredientName]);
                }
                $stmtLink->bind_param('iis', $recipeId, $ingredientId, $quantityValue);
                if (!$stmtLink->execute()) { throw new Exception('Failed to execute recipe_ingredients insert'); }
            }

            $stmtFindIngredient->close();
            $stmtInsertIngredient->close();
            $stmtLink->close();

            // Handle image uploads
            if (!empty($_FILES['images']) && isset($_FILES['images']['tmp_name']) && is_array($_FILES['images']['tmp_name'])) {
                $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }
                $stmtInsertImage = $conn->prepare('INSERT INTO recipe_images (recipe_id, image_path) VALUES (?, ?)');
                if (!$stmtInsertImage) { throw new Exception('Failed to prepare recipe_images insert'); }
                $count = count($_FILES['images']['tmp_name']);
                for ($i = 0; $i < $count; $i++) {
                    if (!isset($_FILES['images']['error'][$i]) || $_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;
                    }
                    $tmpPath = $_FILES['images']['tmp_name'][$i];
                    if (!is_uploaded_file($tmpPath)) {
                        continue;
                    }
                    $origName = isset($_FILES['images']['name'][$i]) ? $_FILES['images']['name'][$i] : 'image';
                    $ext = pathinfo($origName, PATHINFO_EXTENSION);
                    $safeExt = preg_replace('/[^a-zA-Z0-9]/', '', $ext);
                    $basename = bin2hex(random_bytes(8));
                    $filename = $basename . ($safeExt !== '' ? ('.' . $safeExt) : '');
                    $destPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                    if (move_uploaded_file($tmpPath, $destPath)) {
                        $relativePath = 'uploads/' . $filename;
                        $stmtInsertImage->bind_param('is', $recipeId, $relativePath);
                        if (!$stmtInsertImage->execute()) { throw new Exception('Failed to execute recipe_images insert'); }
                    }
                }
                $stmtInsertImage->close();
            }

            $conn->commit();
            $successMessage = '✅ Recipe added successfully!';
        } catch (Throwable $e) {
            $conn->rollback();
            $errorMessage = 'An error occurred while saving the recipe.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-wrapper { max-width: 600px; margin: 40px auto; }
        .card { border-radius: 12px; }
        .form-control { border-radius: 10px; }
    </style>
</head>
<body>
    <div class="form-wrapper">
    <h1 class="text-center mb-4">Add New Recipe</h1>

    <?php if ($successMessage !== ''): ?>
        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Recipe title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="ingredients" class="form-label">Ingredients (comma-separated)</label>
                    <input type="text" class="form-control" id="ingredients" name="ingredients" placeholder="e.g., beans,corn,beef">
                    <div id="quantitiesContainer" class="mt-3"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="images">Images</label>
                    <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                    <div class="form-text">You can select multiple images.</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
    <script>
    (function() {
        const ingredientsInput = document.getElementById('ingredients');
        const quantitiesContainer = document.getElementById('quantitiesContainer');

        function renderQuantityFields() {
            const raw = ingredientsInput.value || '';
            const parts = raw.split(',').map(s => s.trim()).filter(Boolean);
            const unique = Array.from(new Set(parts));

            const existingValues = {};
            Array.from(quantitiesContainer.querySelectorAll('input[data-ingredient]')).forEach(inp => {
                existingValues[inp.getAttribute('data-ingredient')] = inp.value;
            });

            quantitiesContainer.innerHTML = '';
            unique.forEach(name => {
                const group = document.createElement('div');
                group.className = 'mb-2';
                const label = document.createElement('label');
                label.className = 'form-label';
                label.textContent = `How much ${name}?`;
                label.setAttribute('for', `qty_${name}`);
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.name = `quantities[${name}]`;
                input.id = `qty_${name}`;
                input.setAttribute('data-ingredient', name);
                if (Object.prototype.hasOwnProperty.call(existingValues, name)) {
                    input.value = existingValues[name];
                }
                group.appendChild(label);
                group.appendChild(input);
                quantitiesContainer.appendChild(group);
            });
        }

        ingredientsInput.addEventListener('input', renderQuantityFields);
        renderQuantityFields();
    })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


