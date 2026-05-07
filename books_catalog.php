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
                <!-- Карти за книги -->
                <div class="col">
                    <div class="card">
                        <img src="https://picsum.photos/id/237/200/300" class="card-img-top" alt="Book 1">
                        <div class="card-body">
                            <h5 class="card-title">Книга 1</h5>
                            <p class="card-text">Кратко описание на книгата.</p>
                            <p class="fw-bold">Цена: 19.99 лв</p>
                            <a href="book.html" class="btn btn-action btn-lg">Виж повече</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://picsum.photos/id/238/200/300" class="card-img-top" alt="Book 2">
                        <div class="card-body">
                            <h5 class="card-title">Книга 2</h5>
                            <p class="card-text">Кратко описание на книгата.</p>
                            <p class="fw-bold">Цена: 24.99 лв</p>
                            <a href="book.html" class="btn btn-action btn-lg">Виж повече</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://picsum.photos/id/239/200/300" class="card-img-top" alt="Book 3">
                        <div class="card-body">
                            <h5 class="card-title">Книга 3</h5>
                            <p class="card-text">Кратко описание на книгата.</p>
                            <p class="fw-bold">Цена: 21.99 лв</p>
                            <a href="book.html" class="btn btn-action btn-lg">Виж повече</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <img src="https://picsum.photos/id/240/200/300" class="card-img-top" alt="Book 4">
                        <div class="card-body">
                            <h5 class="card-title">Книга 4</h5>
                            <p class="card-text">Кратко описание на книгата.</p>
                            <p class="fw-bold">Цена: 18.99 лв</p>
                            <a href="book.html" class="btn btn-action btn-lg">Виж повече</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>