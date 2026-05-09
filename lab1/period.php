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

$from = $_GET['year_from'];
$to = $_GET['year_to'];

$sql = "SELECT NAME, YEAR, PUBLISHER 
        FROM literature 
        WHERE YEAR BETWEEN :from AND :to";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'from' => $from,
    'to' => $to
]);

foreach ($stmt as $row) {
    echo "{$row['NAME']} ({$row['YEAR']})<br>";
}
?>

</body>
</html>