<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

$featuredBooks = [];
$featuredBooksResult = $conn->query('SELECT id, title, description, price, image_url FROM books ORDER BY id DESC LIMIT 3');
if ($featuredBooksResult) {
    while ($row = $featuredBooksResult->fetch_assoc()) {
        $featuredBooks[] = $row;
    }
}

$newStoreBook = null;
if (!empty($featuredBooks)) {
    $newStoreBook = $featuredBooks[array_rand($featuredBooks)];
}

$randomBook = null;
$randomBookResult = $conn->query('SELECT id, title, description, price, image_url FROM books ORDER BY RAND() LIMIT 1');
if ($randomBookResult) {
    $randomBook = $randomBookResult->fetch_assoc();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Електронна книжарница</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
  #search-results {
    position: absolute;
    top: calc(100% + 2px);
    left: 0;
    width: 100%;
    background-color: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
}

#search-results div {
    padding: 8px;
    cursor: pointer;
}

#search-results div:hover {
    background-color: #f0f0f0;
}
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>

    <main>
        <!-- Hero promo section -->
        <section class="container my-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="p-5 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #0b4265, #3a8fc0); color: white;">
                        <h1 class="display-6 fw-bold">Открий нови книги днес</h1>
                        <p class="lead">Най-добрите заглавия за училище, програмиране и бизнес, подбрани специално за теб.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Бързо намиране на най-новите книги</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Препоръки от реални ученици</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Лесна поръчка и бърза доставка</li>
                        </ul>
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="books_catalog.php" class="btn btn-light btn-lg">Разгледай каталога</a>
                            <a href="cart.php" class="btn btn-outline-light btn-lg">Виж количката</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="p-4 rounded-4 shadow-sm h-100" style="background: #ffffff;">
                                <h5 class="mb-3">Избрано за теб</h5>
                                <?php if (!empty($randomBook)): ?>
                                    <div class="d-flex gap-3 align-items-start">
                                        <img src="<?= htmlspecialchars($randomBook['image_url'] ?: 'https://picsum.photos/120/160', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($randomBook['title'], ENT_QUOTES, 'UTF-8') ?>" class="rounded" style="width: 96px; height: 128px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-2"><?= htmlspecialchars(mb_strimwidth($randomBook['title'], 0, 40, '...'), ENT_QUOTES, 'UTF-8') ?></h6>
                                            <p class="small text-muted mb-2"><?= htmlspecialchars(mb_strimwidth($randomBook['description'] ?: 'Няма описание.', 0, 80, '...'), ENT_QUOTES, 'UTF-8') ?></p>
                                            <p class="fw-semibold mb-3">Цена: <?= number_format($randomBook['price'], 2) ?> €</p>
                                            <a href="book.php?id=<?= urlencode($randomBook['id']) ?>" class="btn btn-sm btn-action">Виж продукта</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p>Няма налични препоръчани книги в момента.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-4 rounded-4 shadow-sm h-100" style="background: #eef5fc;">
                                <h5 class="mb-3">Ново в магазина</h5>
                                <?php if (!empty($newStoreBook)): ?>
                                    <div class="d-flex gap-3 align-items-start">
                                        <img src="<?= htmlspecialchars($newStoreBook['image_url'] ?: 'https://picsum.photos/120/160', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($newStoreBook['title'], ENT_QUOTES, 'UTF-8') ?>" class="rounded" style="width: 96px; height: 128px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-2"><?= htmlspecialchars(mb_strimwidth($newStoreBook['title'], 0, 40, '...'), ENT_QUOTES, 'UTF-8') ?></h6>
                                            <p class="small text-muted mb-2"><?= htmlspecialchars(mb_strimwidth($newStoreBook['description'] ?: 'Няма описание.', 0, 80, '...'), ENT_QUOTES, 'UTF-8') ?></p>
                                            <p class="fw-semibold mb-3">Цена: <?= number_format($newStoreBook['price'], 2) ?> €</p>
                                            <a href="book.php?id=<?= urlencode($newStoreBook['id']) ?>" class="btn btn-sm btn-primary">Виж най-новото</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p>Няма нови книги в момента.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="section-divider">

        <!-- Популярни заглавия -->
        <section class="container my-5">
            <h2 class="mb-4 text-center">Популярни заглавия</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php if (!empty($featuredBooks)): ?>
                    <?php foreach ($featuredBooks as $book): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?= htmlspecialchars($book['image_url'] ?: 'https://picsum.photos/200/300', ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(mb_strimwidth($book['description'] ?? 'Няма описание.', 0, 100, '...'), ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="fw-bold mb-3">Цена: <?= number_format($book['price'], 2) ?> €</p>
                                    <a href="book.php?id=<?= urlencode($book['id']) ?>" class="btn btn-action btn-lg mt-auto">Виж повече</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Няма налични книги</h5>
                                <p class="card-text">Каталогът се обновява скоро.</p>
                                <a href="books_catalog.php" class="btn btn-action btn-lg">Виж каталога</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <!-- Live Search JS -->
    <script>
        const searchInput = document.getElementById('search');
        const resultsDiv = document.getElementById('search-results');

        searchInput.addEventListener('keyup', function () {
            const query = this.value.trim();

            if (query.length === 0) {
                resultsDiv.innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search.php?q=' + encodeURIComponent(query), true);
            xhr.onload = function () {
                if (this.status === 200) {
                    resultsDiv.innerHTML = this.responseText;
                }
            };
            xhr.send();
        });

        resultsDiv.addEventListener('click', function (e) {
            if (e.target.tagName === 'DIV') {
                searchInput.value = e.target.textContent;
                resultsDiv.innerHTML = '';
            }
        });
    </script>
</body>

</html>