<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация - eBookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

    <!-- Navbar -->
    <?php include 'includes/header_auth.php'; ?>

    <main>
        <div class="card login-card">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #0b5367; font-size: 2rem;">Създаване на акаунт</h2>
                <p class="text-muted">Станете част от нашето общество</p>
            </div>

            <form>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ТРИТЕ ИМЕНА</label>
                    <input type="text" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="Иван Иванов">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ИМЕЙЛ</label>
                    <input type="email" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="email@example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ПАРОЛА</label>
                    <input type="password" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-action w-100 shadow-sm">РЕГИСТРИРАЙ СЕ</button>
            </form>

            <div class="text-center mt-4">
                <p class="small mb-0">Вече имате акаунт? <a href="login.html" class="fw-bold text-decoration-none" style="color: #0b5367;">Влезте тук</a></p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>