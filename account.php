<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$isAdmin = !empty($_SESSION['is_admin']);
$userName = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
$userCreated = '';

$stmt = $conn->prepare('SELECT created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($userCreated);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Моят профил - eBookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        .account-header {
            background: linear-gradient(135deg, #0b5367 0%, #3a92b4 100%);
            color: #fff;
        }
        .account-header .profile-circle {
            width: 60px;
            height: 60px;
            font-size: 1.4rem;
        }
        .account-card {
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .account-card .card-body {
            padding: 2.5rem;
        }
        .account-stat-card {
            background: #f8fafc;
            border-radius: 1rem;
        }
    </style>
</head>
<body>

    <?php include 'includes/header_auth.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 account-card">
                    <div class="account-header p-5">
                        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row gap-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center profile-circle me-3">
                                    <?= htmlspecialchars(mb_substr($userName, 0, 1), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div>
                                    <h1 class="h4 mb-1">Моят профил</h1>
                                    <p class="mb-0 opacity-75">Добре дошли, <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>!</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <?php if ($isAdmin): ?>
                                    <a href="admin_dashboard.php" class="btn btn-light btn-sm">
                                        <i class="bi bi-speedometer2 me-1"></i>Админ панел
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <h2 class="h6 text-secondary mb-3">Лична информация</h2>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-secondary">Име</div>
                                <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-secondary">Имейл</div>
                                <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-secondary">Регистриран на</div>
                                <div class="col-sm-8 fw-semibold"><?= htmlspecialchars($userCreated ?: 'Неналична информация', ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="logout.php" class="btn btn-outline-secondary">Изход</a>
                            <a href="index.php" class="btn btn-action">Към магазина</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
