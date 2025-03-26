<?php
$conn = new mysqli('db_testing', 'test_php', '123456', 'test_ci_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to test database";
$conn->close();
?>