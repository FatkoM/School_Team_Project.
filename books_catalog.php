<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог с книги</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>


    <main>
        <!-- Каталог за всички книги -->
        <section class="container my-5">
            <h2 class="mb-4 text-center">Каталог на всички книги</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                require_once 'Config/db_connection.php';
                
                $sql = "SELECT b.*, a.name as author_name FROM books b 
                        LEFT JOIN authors a ON b.author_id = a.id";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($book = $result->fetch_assoc()) {
                ?>
                <!-- Карти за книги -->
                <div class="col">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($book['image_url'] ?? 'https://picsum.photos/id/237/200/300'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($book['description'] ?? 'Кратко описание на книгата.', 0, 100)); ?>...</p>
                            <p class="fw-bold">Цена: <?php echo htmlspecialchars($book['price']); ?> €</p>
                            <div class="d-flex gap-2">
                                <a href="book.php?id=<?php echo $book['id']; ?>" class="btn btn-action btn-lg">Виж повече</a>
                                <form method="POST" action="cart.php" class="d-inline">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn btn-outline-primary">Добави в количка</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Няма налични книги в каталога.
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>