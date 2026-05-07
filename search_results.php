<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results - eBookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="container mt-5">
            <h2 class="mb-4" style="color: #0b5367;">Search Results</h2>
            <div id="search-results-container">
                <!-- Search results will be displayed here -->
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Get search query from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('q');

        if (searchQuery) {
            // Fetch search results
            fetch(`search.php?q=${encodeURIComponent(searchQuery)}`)
                .then(response => response.text())
                .then(data => {
                    const resultsContainer = document.getElementById('search-results-container');
                    resultsContainer.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    document.getElementById('search-results-container').innerHTML = 
                        '<p class="text-danger">Error loading search results</p>';
                });
        } else {
            document.getElementById('search-results-container').innerHTML = 
                '<p class="text-muted">Please enter a search query</p>';
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
