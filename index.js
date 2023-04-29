require("dotenv").config();

const express = require("express");
const bodyParser = require("body-parser");

const package = require("./package.json");

const fs = require("fs");

const pullData = require("./OverpassQuery");

const app = express();
const port = 3007;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.get("/", (req, res) => {
  res.type = "application/json";
  res.send({
    msg: "Welcome to the OpenAED API",
    data: {
      version: package.version,
      endpoints: [
        {
          path: "/maps",
          description: "Get a list of all available maps",
        },
        {
          path: "/:map",
          description: "Get all AEDs in a map",
        },
        {
          path: "/:map/pull",
          description: "Pull data from OpenStreetMap",
        },
      ],
    },
  });
});

app.get("/maps", (req, res) => {
  res.type = "application/json";
  const maps = fs.readdirSync(`./maps/`).map((file) => {
    return file.replace(".json", "");
  });
  res.send({ msg: "Success", data: maps });
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
    res.status(500).send({ msg: "Internal fault", data: { error: e } });
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
    res.status(500).send({ msg: "Internal fault", data: { error: e } });
  }
});

app.listen(port, () => {
  console.log(`OpenAED listening on http://localhost:${port}`);
});
