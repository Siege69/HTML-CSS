<?php
// Database connection
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'clothing_store';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check if "Show All Products" button is clicked
if (isset($_POST['show_all_products'])) {
  $sql = "SELECT p.product_id, p.product_name, p.image_path, AVG(r.rating) AS average_rating
          FROM products p
          LEFT JOIN ratings r ON p.product_id = r.product_id
          GROUP BY p.product_id";
} else {
  // Search query
  $search_term = isset($_GET['search']) ? $_GET['search'] : "";

  $sql = "SELECT p.product_id, p.product_name, p.image_path, AVG(r.rating) AS average_rating
          FROM products p
          LEFT JOIN ratings r ON p.product_id = r.product_id";

  if ($search_term) {
    $sql .= " WHERE p.product_name LIKE '%$search_term%'";
  }
}

$result = mysqli_query($conn, $sql);

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reformina's Appeal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <form action="" method="get" style="text-align:center; margin: 0 auto;">
      <h1>Reformina's Appeal</h1>
      <input type="text" name="search" placeholder="Search Products" style="border-radius: 10px; padding: 5px 10px;">
      <button type="submit" style="border-radius: 10px; padding: 5px 10px;">Search</button>
    </form>
    <form method="post" style="text-align:center; margin: 10px 0;">
      <button type="submit" name="show_all_products" style="border-radius: 10px; padding: 5px 10px;">Show All Products</button>
    </form>
    <h2 style="text-align: center; margin: 10px 0;">See our latest products</h2>
    <p style="font-size: 16px; text-align: center; margin: 0; color: #888;">Explore the freshest styles and hottest trends.</p>
  </header>
  <main>
    <?php if ($result->num_rows > 0): ?>
      <ul class="products">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <li class="product-box">
            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['product_name']; ?>">
            <h2><?php echo $row['product_name']; ?></h2>
            <p>Rating: <?php echo number_format($row['average_rating'], 1); ?></p>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>
  </main>
  <footer style="position: fixed; border-radius: 15px; bottom: 0; left: 0; width: 100%; height: 2.3%; background-color: #000000; color: white; text-align: center; padding: 10px;">Copyright &copy; 2023</footer>
</body>
</html>

