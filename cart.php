<?php
session_start();
require_once 'Config/db_connection.php';

$successMessage = '';
$errorMessage = '';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $book_id = intval($_POST['book_id'] ?? 0);
    
    if ($action === 'add' && $book_id > 0) {
        // Add book to cart
        if (isset($_SESSION['cart'][$book_id])) {
            $_SESSION['cart'][$book_id]['quantity']++;
        } else {
            // Get book details
            $sql = "SELECT b.*, a.name as author_name FROM books b 
                    LEFT JOIN authors a ON b.author_id = a.id 
                    WHERE b.id = $book_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $book = $result->fetch_assoc();
                $_SESSION['cart'][$book_id] = [
                    'id' => $book['id'],
                    'title' => $book['title'],
                    'author' => $book['author_name'] ?? 'Unknown',
                    'price' => $book['price'],
                    'image_url' => $book['image_url'] ?? 'https://picsum.photos/id/24/100/150',
                    'quantity' => 1
                ];
            }
        }
        // Redirect to prevent form resubmission
        header('Location: cart.php');
        exit;
    } elseif ($action === 'update' && $book_id > 0) {
        $quantity = intval($_POST['quantity'] ?? 1);
        if ($quantity > 0) {
            $_SESSION['cart'][$book_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$book_id]);
        }
        header('Location: cart.php');
        exit;
    } elseif ($action === 'remove' && $book_id > 0) {
        unset($_SESSION['cart'][$book_id]);
        header('Location: cart.php');
        exit;
    } elseif ($action === 'checkout') {
        if (empty($_SESSION['cart'])) {
            header('Location: cart.php');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $orderTotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $orderTotal += $item['price'] * $item['quantity'];
        }

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare('INSERT INTO orders (user_id, total_amount) VALUES (?, ?)');
            $stmt->bind_param('id', $_SESSION['user_id'], $orderTotal);
            $stmt->execute();
            $orderId = $conn->insert_id;
            $stmt->close();

            $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, book_id, quantity, book_price) VALUES (?, ?, ?, ?)');
            foreach ($_SESSION['cart'] as $item) {
                $itemStmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']);
                $itemStmt->execute();
            }
            $itemStmt->close();

            $conn->commit();
            $_SESSION['cart'] = [];
            $successMessage = 'Поръчката е успешно завършена. Благодарим за пазаруването!';
        } catch (Exception $e) {
            $conn->rollback();
            $errorMessage = 'Възникна грешка при завършване на поръчката. Моля опитайте отново.';
        }
    }
}

// Calculate totals
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
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

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <div class="row g-5">

                <div class="col-lg-8">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Продукт</th>
                                    <th class="text-center">Количество</th>
                                    <th class="text-end">Цена</th>
                                    <th class="text-end">Общо</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($_SESSION['cart'])): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                                        <p class="text-muted">Вашата количка е празна</p>
                                        <a href="books_catalog.php" class="btn btn-action">Разгледай книгите</a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($_SESSION['cart'] as $book_id => $item): ?>
                                    <tr style="border-bottom: 1px solid #f1f1f1;">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                    class="book-img me-3"
                                                    alt="<?php echo htmlspecialchars($item['title']); ?>">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($item['title']); ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars($item['author']); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center border rounded-pill px-2 py-1">
                                                <form method="POST" action="cart.php" class="d-inline">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo max(1, $item['quantity'] - 1); ?>">
                                                    <button type="submit" class="btn btn-sm border-0">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>
                                                <span class="px-2 fw-bold"><?php echo $item['quantity']; ?></span>
                                                <form method="POST" action="cart.php" class="d-inline">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                                    <button type="submit" class="btn btn-sm border-0">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold" style="color: #0b5367;">
                                            <?php echo number_format($item['price'], 2); ?> €
                                        </td>
                                        <td class="text-end fw-bold" style="color: #0b5367;">
                                            <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="cart.php" class="d-inline">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>

                        </table>

                    </div>

                    <a href="books_catalog.php"
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
                            <span><?php echo number_format($total, 2); ?> €</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-success small">
                            <span>Доставка</span>
                            <span>Безплатна</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">

                            <span class="fw-bold">ОБЩО</span>

                            <span class="fw-bold fs-4" style="color: #0b5367;">
                                <?php echo number_format($total, 2); ?> €
                            </span>

                        </div>

                        <?php if (!empty($_SESSION['cart'])): ?>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="action" value="checkout">
                            <button type="submit" class="btn btn-action w-100">
                                ЗАВЪРШИ ПОРЪЧКАТА
                            </button>
                        </form>
                        <?php endif; ?>

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