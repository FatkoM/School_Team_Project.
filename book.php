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
            <div class="row g-4">
                <!-- Снимка на книгата -->
                <div class="col-md-5">
                    <img src="https://picsum.photos/id/237/400/600" class="img-fluid rounded shadow-sm"
                        alt="Book Image">
                </div>

                <!-- Подробности -->
                <div class="col-md-7">
                    <h2 class="fw-bold">Примерно заглавие на книга</h2>
                    <p class="text-muted mb-2"><strong>Автор:</strong> Иван Иванов</p>
                    <p class="mb-3"><strong>Цена:</strong> 24.99 лв</p>
                    <p class="mb-4">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, officia, odio in culpa
                        obcaecati
                        fugit rerum perspiciatis natus error voluptatibus facilis a est, similique molestias quasi
                        recusandae iure cupiditate reiciendis?
                    </p>
                    <button class="btn btn-action btn-lg">Добави в количка</button>
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