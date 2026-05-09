<?php
include 'db.php';

header("Content-Type: text/xml; charset=utf-8");

$from = $_POST['year_from'];
$to = $_POST['year_to'];

$sql = "SELECT NAME, YEAR, PUBLISHER 
        FROM literature 
        WHERE YEAR BETWEEN :from AND :to";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'from' => $from,
    'to' => $to
]);

echo "<?xml version='1.0' encoding='UTF-8'?>";
echo "<books>";

foreach ($stmt as $row) {
    echo "<book>";
    echo "<name>" . htmlspecialchars($row['NAME']) . "</name>";
    echo "<year>" . htmlspecialchars($row['YEAR']) . "</year>";
    echo "<publisher>" . htmlspecialchars($row['PUBLISHER']) . "</publisher>";
    echo "</book>";
}

echo "</books>";
?>