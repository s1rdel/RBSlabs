const express = require('express');
const mysql = require('mysql2/promise');
const path = require('path');

const app = express();
const PORT = 3000;

const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'lab1',
};

app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

async function getConnection() {
    return await mysql.createConnection(dbConfig);
}

function page(title, content) {
    return `<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>${title}</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
${content}
<br><br>
<a href="/">Повернутися назад</a>
</body>
</html>`;
}

app.get('/', (req, res) => {
    res.send(`<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Бібліотека</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<h2>Пошук книг у бібліотеці</h2>

<form action="/publisher" method="get">
    <label>Оберіть видавництво:</label>

    <select name="publisher">
        <option>Генеза</option>
        <option>Ранок</option>
    </select>

    <button type="submit">Результати пошуку</button>
</form>

<form action="/period" method="get">
    <label>Пошук за роками:</label><br><br>

    <input type="number" name="year_from" placeholder="Від">
    <input type="number" name="year_to" placeholder="До">

    <button type="submit">Пошук</button>
</form>

<form action="/author" method="get">
    <label>Пошук за автором:</label><br><br>

    <input type="text" name="author" placeholder="Ім'я автора">

    <button type="submit">Пошук</button>
</form>

</body>
</html>`);
});

app.get('/publisher', async (req, res) => {
    const publisher = req.query.publisher;

    try {
        const connection = await getConnection();

        const sql = `SELECT NAME, ISBN, PUBLISHER, YEAR, QUANTITY
                     FROM literature
                     WHERE PUBLISHER = ?`;

        const [rows] = await connection.execute(sql, [publisher]);
        await connection.end();

        let table = `<table border="1">
<tr>
<th>Name</th>
<th>ISBN</th>
<th>Publisher</th>
<th>Year</th>
<th>Count</th>
</tr>`;

        rows.forEach(row => {
            table += `<tr>
<td>${row.NAME}</td>
<td>${row.ISBN}</td>
<td>${row.PUBLISHER}</td>
<td>${row.YEAR}</td>
<td>${row.QUANTITY}</td>
</tr>`;});

        table += '</table>';

        res.send(page('Publisher', table));
    } catch (error) {
        res.send(page('Помилка', `<p>Помилка підключення або запиту: ${error.message}</p>`));
    }
});

app.get('/period', async (req, res) => {
    const from = req.query.year_from;
    const to = req.query.year_to;

    try {
        const connection = await getConnection();

        const sql = `SELECT NAME, YEAR, PUBLISHER
                     FROM literature
                     WHERE YEAR BETWEEN ? AND ?`;

        const [rows] = await connection.execute(sql, [from, to]);
        await connection.end();

        let result = '';

        rows.forEach(row => {
            result += `${row.NAME} (${row.YEAR})<br>`;
        });

        res.send(page('Period', result || '<p>Нічого не знайдено</p>'));
    } catch (error) {
        res.send(page('Помилка', `<p>Помилка підключення або запиту: ${error.message}</p>`));
    }
});

app.get('/author', async (req, res) => {
    const author = req.query.author;

    try {
        const connection = await getConnection();

        const sql = `SELECT l.NAME, l.YEAR
                     FROM literature l
                     JOIN book_authrs ba ON l.Id = ba.FID_BOOK
                     JOIN author a ON ba.FID_AUTH = a.Id
                     WHERE a.NAME LIKE ?`;

        const [rows] = await connection.execute(sql, [`%${author}%`]);
        await connection.end();

        let result = '';

        rows.forEach(row => {
            result += `${row.NAME} (${row.YEAR})<br>`;
        });

        res.send(page('Author', result || '<p>Нічого не знайдено</p>'));
    } catch (error) {
        res.send(page('Помилка', `<p>Помилка підключення або запиту: ${error.message}</p>`));
    }
});

app.listen(PORT, () => {
    console.log(`Server started: http://localhost:${PORT}`);
});