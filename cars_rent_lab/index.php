<?php

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$result = "";

if (isset($_POST["income"])) {
    $date = strtotime($_POST["date"]);
    $total = 0;

    $filter = [
        "end_date" => ['$lte' => $date]
    ];

    $query = new MongoDB\Driver\Query($filter);
    $rows = $manager->executeQuery("dbforlab.rentals", $query);

    foreach ($rows as $rental) {
        $total += $rental->cost;
    }

    $result = "Доход: " . $total;
}

if (isset($_POST["mileage"])) {
    $mileage = (int)$_POST["mileage_value"];

    $filter = [
        "mileage" => ['$lt' => $mileage]
    ];

    $query = new MongoDB\Driver\Query($filter);
    $cars = $manager->executeQuery("dbforlab.cars", $query);

    foreach ($cars as $car) {
        $result .= "Марка: " . $car->brand .
                   ", Год: " . $car->year .
                   ", Пробег: " . $car->mileage .
                   "<br>";
    }

    if ($result == "") {
        $result = "Машины не найдены";
    }
}

if (isset($_POST["brands"])) {
    $query = new MongoDB\Driver\Query([]);
    $cars = $manager->executeQuery("dbforlab.cars", $query);

    $brands = [];

    foreach ($cars as $car) {
        $brands[] = $car->brand;
    }

    $brands = array_unique($brands);

    foreach ($brands as $brand) {
        $result .= $brand . "<br>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Прокат автомобилей</title>
</head>
<body>

<h1>Прокат автомобилей</h1>

<h2>1. Доход на дату</h2>
<form method="post">
    <input type="date" name="date" required>
    <button type="submit" name="income">Показать доход</button>
</form>

<h2>2. Машины с пробегом меньше указанного</h2>
<form method="post">
    <input type="number" name="mileage_value" placeholder="Введите пробег" required>
    <button type="submit" name="mileage">Найти</button>
</form>

<h2>3. Марки автомобилей</h2>
<form method="post">
    <button type="submit" name="brands">Показать марки</button>
</form>

<hr>

<h2>Результат:</h2>
<div id="result">
    <?php echo $result; ?>
</div>

<h2>Сохранённый результат:</h2>
<div id="saved"></div>

<script>
let result = document.getElementById("result").innerHTML;

if (result.trim() != "") {
    localStorage.setItem("savedResult", result);
}

let saved = localStorage.getItem("savedResult");

if (saved) {
    document.getElementById("saved").innerHTML = saved;
} else {
    document.getElementById("saved").innerHTML = "Нет сохранённых данных";
}
</script>

</body>
</html>