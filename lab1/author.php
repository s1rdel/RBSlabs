<?php
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Publisher</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

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

foreach ($stmt as $row) {
    echo "{$row['NAME']} ({$row['YEAR']})<br>";
}
?>

</body>
</html>