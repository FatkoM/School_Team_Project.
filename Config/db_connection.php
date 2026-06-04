<?php
$servername = getenv('MYSQL_HOST') ?: 'localhost';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';
$database = getenv('MYSQL_DATABASE') ?: 'e-books';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure users table exists for authentication
$createUsersTableSql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (!$conn->query($createUsersTableSql)) {
    die("Error creating users table: " . $conn->error);
}

$adminColumnSql = "SHOW COLUMNS FROM users LIKE 'is_admin'";
$adminResult = $conn->query($adminColumnSql);
if ($adminResult && $adminResult->num_rows === 0) {
    $alterUsersSql = "ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0";
    if (!$conn->query($alterUsersSql)) {
        die("Error altering users table: " . $conn->error);
    }
}

$createAuthorsTableSql = "CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (!$conn->query($createAuthorsTableSql)) {
    die("Error creating authors table: " . $conn->error);
}

$createBooksTableSql = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (!$conn->query($createBooksTableSql)) {
    die("Error creating books table: " . $conn->error);
}

$bookColumns = [
    'author_id' => "ALTER TABLE books ADD COLUMN author_id INT DEFAULT NULL",
    'price' => "ALTER TABLE books ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    'description' => "ALTER TABLE books ADD COLUMN description TEXT",
    'image_url' => "ALTER TABLE books ADD COLUMN image_url VARCHAR(255)"
];

foreach ($bookColumns as $column => $sql) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM books LIKE '$column'");
    if ($checkColumn && $checkColumn->num_rows === 0) {
        if (!$conn->query($sql)) {
            die("Error altering books table: " . $conn->error);
        }
    }
}

$createOrdersTableSql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (!$conn->query($createOrdersTableSql)) {
    die("Error creating orders table: " . $conn->error);
}

$orderColumns = [
    'total_amount' => "ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00"
];

foreach ($orderColumns as $column => $sql) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM orders LIKE '$column'");
    if ($checkColumn && $checkColumn->num_rows === 0) {
        if (!$conn->query($sql)) {
            die("Error altering orders table: " . $conn->error);
        }
    }
}

$createOrderItemsTableSql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    book_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (!$conn->query($createOrderItemsTableSql)) {
    die("Error creating order_items table: " . $conn->error);
}
// Ensure order_items has the expected columns (backfill if table pre-existed without them)
$orderItemColumns = [
    'book_price' => "ALTER TABLE order_items ADD COLUMN book_price DECIMAL(10,2) NOT NULL DEFAULT 0.00"
];

foreach ($orderItemColumns as $column => $sql) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM order_items LIKE '$column'");
    if ($checkColumn && $checkColumn->num_rows === 0) {
        if (!$conn->query($sql)) {
            die("Error altering order_items table: " . $conn->error);
        }
    }
}
?>
