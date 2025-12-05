<?php
// database.php - single place to configure DB connection

$mysqli = new mysqli('localhost', 'root', '', 'gaje_db');
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Backwards compatibility: some older files use $conn variable
$conn = $mysqli;

function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Simple helper to fetch all rows from a query (returns array)
function fetch_all_rows($result) {
    $rows = [];
    if ($result) {
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
    }
    return $rows;
}

?>
