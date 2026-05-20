<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$cart_count = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? (int) $item['quantity'] : 0;
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = !empty($_SESSION['is_admin']);
$userName = $_SESSION['user_name'] ?? '';
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold d-flex align-items-center">
            <img src="images/logo-eBookStore.png" alt="Logo" width="40" class="me-2">
             eBookStore
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="index.php">Начало</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="books_catalog.php">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="cart.php">
                        Количка
                        <?php if ($cart_count > 0): ?>
                            <span class="badge bg-danger ms-1"><?= $cart_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="account.php">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                 style="width:32px; height:32px; font-size:0.9rem;">
                                <?= htmlspecialchars(mb_substr($userName, 0, 1), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <span class="small mb-0"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php if ($isAdmin): ?>
                                <span class="badge bg-danger ms-2">Admin</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="login.php">Вход</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>