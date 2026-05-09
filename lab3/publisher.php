<?php
include 'db.php';

$publisher = $_GET['publisher'];

$sql = "SELECT NAME, ISBN, PUBLISHER, YEAR, QUANTITY 
        FROM literature 
        WHERE PUBLISHER = :publisher";

$stmt = $pdo->prepare($sql);
$stmt->execute(['publisher' => $publisher]);

echo "<h3>Результат у форматі HTML</h3>";

echo "<table border='1'>";
echo "<tr>
        <th>Name</th>
        <th>ISBN</th>
        <th>Publisher</th>
        <th>Year</th>
        <th>Count</th>
      </tr>";

foreach ($stmt as $row) {
    echo "<tr>";
    echo "<td>{$row['NAME']}</td>";
    echo "<td>{$row['ISBN']}</td>";
    echo "<td>{$row['PUBLISHER']}</td>";
    echo "<td>{$row['YEAR']}</td>";
    echo "<td>{$row['QUANTITY']}</td>";
    echo "</tr>";
}

echo "</table>";
?>