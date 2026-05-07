<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Количка - eBookStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

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

        .cart-card {
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 20px;
        }

        .book-img {
            width: 80px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>

    <main>

        <div class="card cart-card">

            <h2 class="fw-bold mb-4" style="color: #0b5367;">
                <i class="bi bi-cart3 me-2"></i>
                Вашата количка
            </h2>

            <div class="row g-5">

                <div class="col-lg-8">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Продукт</th>
                                    <th class="text-center">Количество</th>
                                    <th class="text-end">Цена</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr style="border-bottom: 1px solid #f1f1f1;">

                                    <td class="py-3">

                                        <div class="d-flex align-items-center">

                                            <img src="https://picsum.photos/id/24/100/150"
                                                class="book-img me-3"
                                                alt="Book">

                                            <div>
                                                <h6 class="mb-0 fw-bold">Великият Гетсби</h6>

                                                <small class="text-muted">
                                                    Ф. Скот Фицджералд
                                                </small>
                                            </div>

                                        </div>

                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex align-items-center border rounded-pill px-2 py-1">

                                            <button class="btn btn-sm border-0">
                                                <i class="bi bi-dash"></i>
                                            </button>

                                            <span class="px-2 fw-bold">1</span>

                                            <button class="btn btn-sm border-0">
                                                <i class="bi bi-plus"></i>
                                            </button>

                                        </div>

                                    </td>

                                    <td class="text-end fw-bold" style="color: #0b5367;">
                                        18.90 лв
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <a href="books_catalog.html"
                        class="btn btn-link text-decoration-none p-0 mt-3"
                        style="color: #348096;">

                        <i class="bi bi-arrow-left"></i>
                        Продължи с пазаруването

                    </a>

                </div>

                <div class="col-lg-4">

                    <div class="p-4 rounded-4" style="background-color: #fbf6ec;">

                        <h5 class="fw-bold mb-4">
                            Резюме на поръчката
                        </h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Междинна сума</span>
                            <span>18.90 лв</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-success small">
                            <span>Доставка</span>
                            <span>Безплатна</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">

                            <span class="fw-bold">ОБЩО</span>

                            <span class="fw-bold fs-4" style="color: #0b5367;">
                                18.90 лв
                            </span>

                        </div>

                        <button class="btn btn-action w-100">
                            ЗАВЪРШИ ПОРЪЧКАТА
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>