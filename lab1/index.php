<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Бібліотека</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Пошук книг у бібліотеці</h2>

<form action="publisher.php" method="get">
    <label>Оберіть видавництво:</label>
    
    <select name="publisher">
        <option>Генеза</option>
        <option>Ранок</option>
    </select>

    <button type="submit">Результати пошуку</button>
</form>

<form action="period.php" method="get">

    <label>Пошук за роками:</label><br><br>

    <input type="number"
           name="year_from"
           placeholder="Від">

    <input type="number"
           name="year_to"
           placeholder="До">

    <button type="submit">Пошук</button>
</form>

<form action="author.php" method="get">

    <label>Пошук за автором:</label><br><br>

    <input type="text"
           name="author"
           placeholder="Ім'я автора">

    <button type="submit">Пошук</button>
</form>

</body>
</html>