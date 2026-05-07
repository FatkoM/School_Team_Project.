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
        <!-- Carousel -->
        <div id="carouselExampleCaptions" class="carousel slide mb-5">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://picsum.photos/id/237/1200/400" class="d-block w-100" alt="Book 1">
                    <div class="carousel-caption d-none d-md-block carousel-caption-custom rounded p-2">
                        <h5>Нова книга</h5>
                        <p>Открий най-новото заглавие в нашия каталог!</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://picsum.photos/id/238/1200/400" class="d-block w-100" alt="Book 2">
                    <div class="carousel-caption d-none d-md-block carousel-caption-custom rounded p-2">
                        <h5>Топ книга</h5>
                        <p>Любими заглавия сред учениците и учителите.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://picsum.photos/id/239/1200/400" class="d-block w-100" alt="Book 3">
                    <div class="carousel-caption d-none d-md-block carousel-caption-custom rounded p-2">
                        <h5>Специална оферта</h5>
                        <p>Вземи книги с намаление и промоции!</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Предишен</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Следващ</span>
            </button>
        </div>

        <hr class="section-divider">

        <!-- Популярни заглавия -->
        <section class="container my-5">
            <h2 class="mb-4 text-center">Популярни заглавия</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Example book cards -->
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
                            <a href="book.php" class="btn btn-action btn-lg">Виж повече</a>
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