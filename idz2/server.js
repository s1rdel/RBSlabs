const express = require("express");
const { MongoClient } = require("mongodb");

const app = express();

app.use(express.urlencoded({ extended: true }));

const url = "mongodb://127.0.0.1:27017";
const client = new MongoClient(url);

let db;

async function start() {
    await client.connect();
    db = client.db("dbforlab");

    app.listen(3000, () => {
        console.log("Server started on port 3000");
    });
}

start();

app.get("/", (req, res) => {
    res.send(getHTML(""));
});

app.post("/", async (req, res) => {
    let result = "";

    if (req.body.income !== undefined) {
        const date = Math.floor(new Date(req.body.date).getTime() / 1000);

        const rentals = await db.collection("rentals").find({
            end_date: { $lte: date }
        }).toArray();

        let total = 0;

        rentals.forEach(r => {
            total += r.cost;
        });

        result = "Доход: " + total;
    }

    if (req.body.mileage !== undefined) {
        const mileage = parseInt(req.body.mileage_value);

        const cars = await db.collection("cars").find({
            mileage: { $lt: mileage }
        }).toArray();

        cars.forEach(car => {
            result +=
                "Марка: " + car.brand +
                ", Год: " + car.year +
                ", Пробег: " + car.mileage +
                "<br>";
        });

        if (result === "") {
            result = "Машины не найдены";
        }
    }

    if (req.body.brands !== undefined) {
        const cars = await db.collection("cars").find().toArray();

        let brands = [];

        cars.forEach(car => {
            brands.push(car.brand);
        });

        brands = [...new Set(brands)];

        brands.forEach(brand => {
            result += brand + "<br>";
        });
    }

    res.send(getHTML(result));
});

function getHTML(result) {
    return `
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
        ${result}
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
    `;
}