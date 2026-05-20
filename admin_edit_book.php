<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$successMessage = '';

$id = intval($_GET['id'] ?? ($_POST['id'] ?? 0));
if ($id <= 0) {
    header('Location: admin_dashboard.php');
    exit;
}

// Fetch book
$stmt = $conn->prepare('SELECT b.id, b.title, b.price, b.description, b.image_url, b.author_id, a.name AS author_name FROM books b LEFT JOIN authors a ON b.author_id = a.id WHERE b.id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    header('Location: admin_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $title = trim($_POST['title'] ?? '');
    $authorName = trim($_POST['author'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imageUrl = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') $errors[] = 'Моля въведете заглавие.';
    if ($authorName === '') $errors[] = 'Моля въведете автор.';
    if ($price <= 0) $errors[] = 'Моля въведете валидна цена.';

    if (empty($errors)) {
        $conn->begin_transaction();
        try {
            // find or create author
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

            $update = $conn->prepare('UPDATE books SET title = ?, author_id = ?, price = ?, description = ?, image_url = ? WHERE id = ?');
            $update->bind_param('sidssi', $title, $authorId, $price, $description, $imageUrl, $id);
            $update->execute();
            $update->close();

            $conn->commit();
            $successMessage = 'Промените бяха записани успешно.';
            // reload book data
            $stmt = $conn->prepare('SELECT b.id, b.title, b.price, b.description, b.image_url, b.author_id, a.name AS author_name FROM books b LEFT JOIN authors a ON b.author_id = a.id WHERE b.id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book = $result->fetch_assoc();
            $stmt->close();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = 'Грешка при запис. Моля опитайте отново.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редакция на книга - Админ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="container my-5">
    <div class="card p-4 shadow-sm">
        <h5 class="mb-3">Редакция на книга</h5>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e, ENT_QUOTES, 'UTF-8').'</li>'; ?></ul></div>
        <?php endif; ?>

        <form method="POST" action="admin_edit_book.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="update_book" value="1">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Заглавие</label>
                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Автор</label>
                    <input type="text" name="author" class="form-control" required value="<?= htmlspecialchars($book['author_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Цена</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control" required value="<?= htmlspecialchars(number_format($book['price'], 2), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Снимка (URL)</label>
                    <input type="text" name="image_url" class="form-control" value="<?= htmlspecialchars($book['image_url'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($book['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-action">Запази промените</button>
                <a href="admin_dashboard.php" class="btn btn-outline-secondary ms-2">Откажи</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
