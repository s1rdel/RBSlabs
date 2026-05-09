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

$publisher = $_GET['publisher'];

$sql = "SELECT NAME, ISBN, PUBLISHER, YEAR, QUANTITY 
        FROM literature 
        WHERE PUBLISHER = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$publisher]);

echo "<table border='1'>";
echo "<tr><th>Name</th><th>ISBN</th><th>Publisher</th><th>Year</th><th>Count</th></tr>";

foreach ($stmt as $row) {
    echo "<tr>
        <td>{$row['NAME']}</td>
        <td>{$row['ISBN']}</td>
        <td>{$row['PUBLISHER']}</td>
        <td>{$row['YEAR']}</td>
        <td>{$row['QUANTITY']}</td>
    </tr>";
}

echo "</table>";
?>

</body>
</html>