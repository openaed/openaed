require("dotenv").config();

const express = require("express");
const bodyParser = require("body-parser");

const mssql = require("mssql");
const fs = require("fs");

const pullData = require("./OverpassQuery");

const app = express();
const port = 3007;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.get("/", (req, res) => {
  res.type = "application/json";
  res.send({ msg: "Welcome to the OpenAED API" });
});

app.get("/:map", (req, res) => {
  res.type = "application/json";
  const map = req.params.map;

  try {
    const maps = fs.readFileSync(`./data/${map}.json`);
    const results = JSON.parse(maps);

    if (!results) {
      res.status(404).send({ msg: "Not found", data: null });
    } else {
      res.send({ msg: "Success", data: results });
    }
  } catch (e) {
    res.status(404).send({ msg: "Not found", data: null });
  }
});

app.get("/:map/pull", async (req, res) => {
  res.type = "application/json";
  const map = req.params.map;

  try {
    const maps = fs.readFileSync(`./maps/${map}.json`);
    const results = JSON.parse(maps);

    pullData(map, results.places);

    if (!results) {
      res.status(404).send({ msg: "Not found", data: null });
    } else {
      res.send({ msg: "Success", data: results });
    }
  } catch (e) {
    res.status(404).send({ msg: "Not found", data: null });
  }
});

app.listen(port, () => {
  console.log(`OpenAED listening on http://localhost:${port}`);
});
