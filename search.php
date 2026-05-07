<?php
require_once 'Config/db_connection.php';

if (isset($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);

    // Search the books table
    $sql = "SELECT title FROM books WHERE title LIKE '%$q%' LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output each result as a div for the dropdown
        while ($row = $result->fetch_assoc()) {
            echo '<div class="p-2 border-bottom" style="cursor:pointer;">' . htmlspecialchars($row['title']) . '</div>';
        }
    } else {
        echo '<div class="p-2 text-muted">No matches found</div>';
    }
}
?>