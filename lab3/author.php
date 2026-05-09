<?php
include 'db.php';

header("Content-Type: application/json; charset=utf-8");

$author = $_GET['author'];

$sql = "SELECT l.NAME, l.YEAR
        FROM literature l
        JOIN book_authrs ba ON l.id = ba.FID_BOOK
        JOIN author a ON ba.FID_AUTH = a.id
        WHERE a.NAME LIKE :author";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'author' => "%$author%"
]);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>