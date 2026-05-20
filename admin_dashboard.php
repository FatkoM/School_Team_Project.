<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_book'])) {
    $title = trim($_POST['title'] ?? '');
    $authorName = trim($_POST['author'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imageUrl = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = 'Моля въведете заглавие на книгата.';
    }
    if ($authorName === '') {
        $errors[] = 'Моля въведете името на автора.';
    }
    if ($price <= 0) {
        $errors[] = 'Моля въведете валидна цена.';
    }

    if (empty($errors)) {
        $conn->begin_transaction();
        try {
            $authorStmt = $conn->prepare('SELECT id FROM authors WHERE name = ?');
            $authorStmt->bind_param('s', $authorName);
            $authorStmt->execute();
            $authorStmt->store_result();
            $authorId = null;
            if ($authorStmt->num_rows === 1) {
                $authorStmt->bind_result($authorId);
                $authorStmt->fetch();
            }
            $authorStmt->close();

            if (!$authorId) {
                $insertAuthor = $conn->prepare('INSERT INTO authors (name) VALUES (?)');
                $insertAuthor->bind_param('s', $authorName);
                $insertAuthor->execute();
                $authorId = $conn->insert_id;
                $insertAuthor->close();
            }

            $insertBook = $conn->prepare('INSERT INTO books (title, author_id, price, description, image_url) VALUES (?, ?, ?, ?, ?)');
            $insertBook->bind_param('sisss', $title, $authorId, $price, $description, $imageUrl);
            $insertBook->execute();
            $insertBook->close();

            $conn->commit();
            $successMessage = 'Книгата е добавена успешно в каталога.';
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = 'Възникна грешка при добавяне на книгата. Моля опитайте отново.';
        }
    }
}

$totalUsersResult = $conn->query('SELECT COUNT(*) AS count FROM users');
$totalUsers = $totalUsersResult ? $totalUsersResult->fetch_assoc()['count'] : 0;

$totalOrdersResult = $conn->query('SELECT COUNT(*) AS count FROM orders');
$totalOrders = $totalOrdersResult ? $totalOrdersResult->fetch_assoc()['count'] : 0;

$totalRevenueResult = $conn->query('SELECT COALESCE(SUM(total_amount), 0) AS revenue FROM orders');
$totalRevenue = $totalRevenueResult ? $totalRevenueResult->fetch_assoc()['revenue'] : 0;

$totalBooksSoldResult = $conn->query('SELECT COALESCE(SUM(quantity), 0) AS sold FROM order_items');
$totalBooksSold = $totalBooksSoldResult ? $totalBooksSoldResult->fetch_assoc()['sold'] : 0;

$topBooks = [];
$topBooksResult = $conn->query(
    'SELECT b.title, a.name AS author_name, SUM(oi.quantity) AS sold_count, SUM(oi.quantity * oi.book_price) AS revenue
     FROM order_items oi
     JOIN books b ON oi.book_id = b.id
     LEFT JOIN authors a ON b.author_id = a.id
     GROUP BY oi.book_id
     ORDER BY sold_count DESC
     LIMIT 5'
);
if ($topBooksResult) {
    while ($row = $topBooksResult->fetch_assoc()) {
        $topBooks[] = $row;
    }
}

$recentOrders = [];
$recentOrdersResult = $conn->query(
    'SELECT o.id, u.full_name, o.total_amount, o.created_at, COUNT(oi.id) AS items_count
     FROM orders o
     JOIN users u ON o.user_id = u.id
     LEFT JOIN order_items oi ON oi.order_id = o.id
     GROUP BY o.id
     ORDER BY o.created_at DESC
     LIMIT 5'
);
if ($recentOrdersResult) {
    while ($row = $recentOrdersResult->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

// Fetch books for management table
$books = [];
$booksResult = $conn->query('SELECT b.id, b.title, b.price, b.description, b.image_url, a.name AS author_name FROM books b LEFT JOIN authors a ON b.author_id = a.id ORDER BY b.id DESC LIMIT 200');
if ($booksResult) {
    while ($row = $booksResult->fetch_assoc()) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ панел - eBookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .admin-hero {
            background: linear-gradient(135deg, #0b5367 0%, #3a92b4 100%);
            color: #fff;
            border-radius: 1rem;
        }
        .admin-card {
            border-radius: 1rem;
        }
        .admin-badge {
            font-size: 0.75rem;
            letter-spacing: 0.4px;
        }
    </style>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main class="container my-5">
        <section class="admin-hero p-5 mb-5 shadow-sm">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h3">Админ панел</h1>
                    <p class="mb-1 opacity-75">Прегледайте статистики, управление на каталога и последни поръчки.</p>
                    <span class="badge bg-light text-dark admin-badge">Администраторски достъп</span>
                </div>
                <div class="text-white text-end">
                    <p class="mb-1">Общо потребители</p>
                    <h3 class="mb-0"><?= number_format($totalUsers) ?></h3>
                </div>
            </div>
        </section>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row gy-4">
            <div class="col-xl-4">
                <div class="card p-4 admin-card shadow-sm">
                    <div class="mb-4">
                        <h5 class="mb-0">Статистика</h5>
                        <small class="text-muted">Най-важните показатели</small>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="text-secondary">Поръчки</span>
                            <h4 class="mb-0"><?= number_format($totalOrders) ?></h4>
                        </div>
                        <div>
                            <span class="text-secondary">Продадени книги</span>
                            <h4 class="mb-0"><?= number_format($totalBooksSold) ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-secondary">Общо приходи</span>
                            <h4 class="mb-0"><?= number_format($totalRevenue, 2) ?> лв</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card p-4 admin-card shadow-sm">
                    <h5 class="mb-4">Добави нова книга</h5>
                    <form method="POST" action="admin_dashboard.php">
                        <input type="hidden" name="create_book" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Заглавие</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Автор</label>
                                <input type="text" name="author" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Цена</label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Снимка (URL)</label>
                                <input type="text" name="image_url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Описание</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-action mt-4">Добави книга</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row gy-4 mt-4">
            <div class="col-lg-6">
                <div class="card p-4 admin-card shadow-sm">
                    <h5 class="mb-4">Най-продавани книги</h5>
                    <?php if (!empty($topBooks)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($topBooks as $book): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <div class="text-muted small"><?= htmlspecialchars($book['author_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?= htmlspecialchars($book['sold_count'], ENT_QUOTES, 'UTF-8') ?> бр.</div>
                                        <div class="text-muted small"><?= number_format($book['revenue'], 2) ?> лв</div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">Все още няма поръчки.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card p-4 admin-card shadow-sm">
                    <h5 class="mb-4">Последни поръчки</h5>
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Клиент</th>
                                        <th>Сума</th>
                                        <th>Артикули</th>
                                        <th>Дата</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($order['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= number_format($order['total_amount'], 2) ?> лв</td>
                                            <td><?= htmlspecialchars($order['items_count'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Все още няма поръчки.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

        <div class="row gy-4 mt-4">
            <div class="col-12">
                <div class="card p-4 admin-card shadow-sm">
                    <h5 class="mb-4">Управление на каталога</h5>
                    <?php if (!empty($books)): ?>
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Заглавие</th>
                                        <th>Автор</th>
                                        <th>Цена</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $b): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($b['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($b['author_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= number_format($b['price'], 2) ?> лв</td>
                                            <td class="text-end"><a href="admin_edit_book.php?id=<?= urlencode($b['id']) ?>" class="btn btn-sm btn-outline-primary">Редактирай</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Все още няма книги.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
