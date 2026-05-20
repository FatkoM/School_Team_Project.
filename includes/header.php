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
                    <a class="nav-link active" aria-current="page" href="index.php">Начало</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="books_catalog.php">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
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
                        <a class="nav-link" href="login.php">Вход</a>
                    </li>
                <?php endif; ?>
            </ul>
            <form class="d-flex position-relative" role="search">
                <input class="form-control me-2" type="search" placeholder="Търси книга..." aria-label="Search" id="search-input">
                <button class="btn btn-action btn-search btn-lg" type="submit"
                    style="font-weight:700;">Търси</button>
                <!-- Autocomplete dropdown -->
                <div id="autocomplete-dropdown" class="position-absolute bg-white border rounded shadow-sm" 
                     style="top: 100%; left: 0; right: 0; max-width: 350px; z-index: 1000; display: none; max-height: 300px; overflow-y: auto; pointer-events: auto;">
                </div>
            </form>
        </div>
    </div>
</nav>

<script>
    const searchInput = document.getElementById('search-input');
    const dropdown = document.getElementById('autocomplete-dropdown');
    let debounceTimer;

    // Show autocomplete results as user types
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
            return;
        }

        // Debounce the search
        debounceTimer = setTimeout(() => {
            fetch(`search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    dropdown.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(book => {
                            const item = document.createElement('div');
                            item.className = 'p-2 border-bottom';
                            item.style.cssText = 'cursor: pointer; transition: background-color 0.2s;';
                            item.innerHTML = `<strong>${book.title}</strong><br><small class="text-muted">${book.author}</small>`;
                            
                            // Hover effect
                            item.addEventListener('mouseover', () => {
                                item.style.backgroundColor = '#f0f0f0';
                            });
                            item.addEventListener('mouseout', () => {
                                item.style.backgroundColor = 'transparent';
                            });
                            
                            // Click to navigate to book page
                            item.addEventListener('click', () => {
                                window.location.href = `book.php?id=${book.id}`;
                            });
                            
                            dropdown.appendChild(item);
                        });
                        dropdown.style.display = 'block';
                        dropdown.style.pointerEvents = 'auto';
                    } else {
                        const noResults = document.createElement('div');
                        noResults.className = 'p-2 text-muted text-center';
                        noResults.textContent = 'No books found';
                        dropdown.appendChild(noResults);
                        dropdown.style.display = 'block';
                        dropdown.style.pointerEvents = 'auto';
                    }
                })
                .catch(error => {
                    console.error('Error fetching autocomplete results:', error);
                    dropdown.innerHTML = '<div class="p-2 text-danger text-center">Error loading results</div>';
                    dropdown.style.display = 'block';
                });
        }, 300);
    });

    // Handle form submission (full search)
    searchInput.closest('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `search_results.php?q=${encodeURIComponent(query)}`;
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>