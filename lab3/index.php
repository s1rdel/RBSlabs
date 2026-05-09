<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Бібліотека Ajax</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Пошук книг у бібліотеці</h2>

<div>
    <label>Оберіть видавництво:</label>

    <select id="publisher">
        <option>Генеза</option>
        <option>Ранок</option>
    </select>

    <button onclick="searchPublisher()">Результати пошуку</button>
</div>

<br>

<div>
    <label>Пошук за роками:</label><br><br>

    <input type="number" id="year_from" placeholder="Від">
    <input type="number" id="year_to" placeholder="До">

    <button onclick="searchPeriod()">Пошук</button>
</div>

<br>

<div>
    <label>Пошук за автором:</label><br><br>

    <input type="text" id="author" placeholder="Ім'я автора">

    <button onclick="searchAuthor()">Пошук</button>
</div>

<hr>

<div id="result">
    Тут буде результат пошуку.
</div>

<script>
function searchPublisher() {
    let publisher = document.getElementById("publisher").value;

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "publisher.php?publisher=" + encodeURIComponent(publisher), true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById("result").innerHTML = xhr.responseText;
        }
    };

    xhr.send();
}

function searchPeriod() {
    let from = document.getElementById("year_from").value;
    let to = document.getElementById("year_to").value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "period.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            let xml = xhr.responseXML;
            let books = xml.getElementsByTagName("book");

            let html = "<h3>Результат у форматі XML</h3>";
            html += "<ul>";

            for (let i = 0; i < books.length; i++) {
                let name = books[i].getElementsByTagName("name")[0].textContent;
                let year = books[i].getElementsByTagName("year")[0].textContent;
                let publisher = books[i].getElementsByTagName("publisher")[0].textContent;

                html += "<li>" + name + " (" + year + "), " + publisher + "</li>";
            }

            html += "</ul>";

            document.getElementById("result").innerHTML = html;
        }
    };

    xhr.send("year_from=" + encodeURIComponent(from) + "year_to=" + encodeURIComponent(to));
}

function searchAuthor() {
    let author = document.getElementById("author").value;

    fetch("author.php?author=" + encodeURIComponent(author))
        .then(response => response.text())
        .then(data => {
            let books = JSON.parse(data);

            let html = "<h3>Результат у форматі JSON</h3>";
            html += "<ul>";

            books.forEach(book => {
                html += "<li>" + book.NAME + " (" + book.YEAR + ")</li>";
            });

            html += "</ul>";

            document.getElementById("result").innerHTML = html;
        });
}
</script>

</body>
</html>