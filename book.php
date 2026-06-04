<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Подробности за книга</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>
    <main>
        <!-- подробности за книга -->
        <section class="container my-5">
            <?php
                require_once 'Config/db_connection.php';
                
                $bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;
                
                if ($bookId > 0) {
                    $sql = "SELECT b.*, a.name as author_name FROM books b 
                            LEFT JOIN authors a ON b.author_id = a.id 
                            WHERE b.id = $bookId";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        $book = $result->fetch_assoc();
                    } else {
                        $book = null;
                    }
                } else {
                    $book = null;
                }
                
                if ($book) {
            ?>
            <div class="row g-4">
                <!-- Снимка на книгата -->
                <div class="col-md-5">
                    <img src="<?php echo htmlspecialchars($book['image_url'] ?? 'https://picsum.photos/id/237/400/600'); ?>" class="img-fluid rounded shadow-sm"
                        alt="Book Image">
                </div>

                <!-- Подробности -->
                <div class="col-md-7">
                    <h2 class="fw-bold"><?php echo htmlspecialchars($book['title']); ?></h2>
                    <p class="text-muted mb-2"><strong>Автор:</strong> <?php echo htmlspecialchars($book['author_name'] ?? 'Unknown'); ?></p>
                    <p class="mb-3"><strong>Цена:</strong> <?php echo htmlspecialchars($book['price'] ?? 'N/A'); ?> €</p>
                    <p class="mb-4">
                        <?php echo htmlspecialchars($book['description'] ?? 'No description available'); ?>
                    </p>
                    <form method="POST" action="cart.php" class="d-inline">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        <button type="submit" class="btn btn-action btn-lg">Добави в количка</button>
                    </form>
                </div>
            </div>
            <?php
                } else {
            ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Book Not Found</h4>
                <p>The book you are looking for does not exist or has been removed.</p>
                <a href="books_catalog.php" class="btn btn-primary mt-3">Back to Catalog</a>
            </div>
            <?php
                }
            ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>