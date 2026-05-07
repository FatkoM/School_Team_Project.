<?php
require_once 'Config/db_connection.php';

header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);

    // Search the books table with author names from authors table
    $sql = "SELECT b.id, b.title, a.name as author_name FROM books b 
            LEFT JOIN authors a ON b.author_id = a.id 
            WHERE b.title LIKE '%$q%' LIMIT 5";
    $result = $conn->query($sql);

    $results = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = array(
                'id' => $row['id'],
                'title' => htmlspecialchars($row['title']),
                'author' => htmlspecialchars($row['author_name'] ?? 'Unknown Author')
            );
        }
    }
    
    echo json_encode($results);
} else {
    echo json_encode(array());
}
?>