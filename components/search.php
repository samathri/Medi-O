<?php
include 'includes/db.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Medi-O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="container mt-5">
    <h3 class="mb-4">Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h3>
    <div class="row">
        <?php
        if (!empty($searchQuery)) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%')");
            $stmt->bind_param("s", $searchQuery);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePaths = explode(',', $row['image_path']);
                    $firstImage = trim($imagePaths[0]);
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="uploads/<?php echo htmlspecialchars($firstImage); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text text-success">Rs. <?php echo htmlspecialchars($row['price']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No products found.</p>';
            }
            $stmt->close();
        } else {
            echo '<p>Please enter a search query.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
