<?php
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

try {
    // Determine limit: default 6; support limit=all or numeric via query param
    $limitParam = isset($_GET['limit']) ? $_GET['limit'] : null;
    $limitSql = 'LIMIT 6';
    if ($limitParam !== null) {
        if (strtolower($limitParam) === 'all') {
            $limitSql = '';
        } elseif (ctype_digit((string)$limitParam)) {
            $limitSql = 'LIMIT ' . (int)$limitParam;
        }
    }

    // Optional protein filter: chicken|beef|pork|vegetarian
    $protein = isset($_GET['protein']) ? strtolower(trim($_GET['protein'])) : null;
    $proteinWhere = '';
    if (in_array($protein, ['chicken', 'beef', 'pork', 'vegetarian'], true)) {
        if ($protein === 'vegetarian') {
            // Exclude recipes that contain any of the meat keywords
            $proteinWhere = "WHERE NOT EXISTS (
                SELECT 1
                FROM recipe_ingredients ri
                JOIN ingredients i ON i.id = ri.ingredient_id
                WHERE ri.recipe_id = r.id
                  AND (
                        i.name LIKE '%chicken%'
                     OR i.name LIKE '%beef%'
                     OR i.name LIKE '%pork%'
                  )
            )";
        } else {
            // Include recipes that contain the selected meat keyword
            $like = $protein; // safe because whitelisted
            $proteinWhere = "WHERE EXISTS (
                SELECT 1
                FROM recipe_ingredients ri
                JOIN ingredients i ON i.id = ri.ingredient_id
                WHERE ri.recipe_id = r.id
                  AND i.name LIKE '%$like%'
            )";
        }
    }

    // Fetch recipes with a representative image_path from recipe_images
    // Picks the first image per recipe; adjust ORDER BY as needed
    $sql = "
        SELECT r.id, r.title, r.description,
               (
                   SELECT ri.image_path
                   FROM recipe_images ri
                   WHERE ri.recipe_id = r.id
                   ORDER BY ri.id ASC
                   LIMIT 1
               ) AS image_path
        FROM recipes r
        $proteinWhere
        ORDER BY r.id DESC
        $limitSql
    ";
    $result = $conn->query($sql);

    if ($result === false) {
        http_response_code(500);
        echo json_encode([ 'error' => 'Database query failed' ]);
        exit;
    }

    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $recipes[] = [
            'id' => $id,
            'title' => $row['title'] ?? '',
            'description' => $row['description'] ?? '',
            'link' => 'recipe.php?id=' . $id,
            'image_path' => $row['image_path'] ?? null
        ];
    }

    echo json_encode($recipes);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([ 'error' => 'Server error' ]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}


