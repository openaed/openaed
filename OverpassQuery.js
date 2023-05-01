// This file contains code to query the OpenStreetMap database using the Overpass API.

// The query is inside query.overpassql

// Not going to bother explaining this code.

const fs = require("fs");
const query_overpass = require("query-overpass");

function pullData(name, places) {
  let areas = "";
  places.forEach((place) => {
    areas += `\narea[name="${place}"][admin_level=8];`;
  });

  const query = `
[out:json][timeout:25];
(${areas}
  )->.a;
  node[emergency=defibrillator](area.a);
  out;
out body;
>;
`;

  console.log(query);

  const copyMessage =
    "This data is licensed from the OpenStreetMap foundation. Therefore, the same licensing applies to this data. For more information, visit https://www.openstreetmap.org/copyright";

  fs.writeFileSync(
    `./data/${name}.json`,
    JSON.stringify({ aed: [] }, null, "\t")
  );

  query_overpass(query, (error, data) => {
    if (error) console.log(error);
    data.features.forEach((feature) => {
      addParsedData({
        id: feature.properties.id,
        access: feature.properties.tags.access
          ? feature.properties.tags.access
          : null,
        coordinates: {
          lat: feature.geometry.coordinates[1],
          lon: feature.geometry.coordinates[0],
        },
        indoor: feature.properties.tags.indoor
          ? feature.properties.tags.indoor
          : null,
        operator: feature.properties.tags.operator
          ? feature.properties.tags.operator
          : "Onbekende beheerder",
        "operator:website": feature.properties.tags["operator:website"]
          ? feature.properties.tags["operator:website"]
          : null,
        phone: feature.properties.tags.phone
          ? feature.properties.tags.phone
          : null,
        location: feature.properties.tags["defibrillator:location"]
          ? feature.properties.tags["defibrillator:location"]
          : "Onbekend",
        "location:en": feature.properties.tags["defibrillator:location:en"]
          ? feature.properties.tags["defibrillator:location:en"]
          : null,
        manufacturer: feature.properties.tags.manufacturer
          ? feature.properties.tags.manufacturer
          : null,
        level: feature.properties.tags.level
          ? feature.properties.tags.level
          : 0,
        image: feature.properties.tags.image
          ? feature.properties.tags.image
          : null,
        "defibrillator:cabinet": feature.properties.tags[
          "defibrillator:cabinet"
        ]
          ? feature.properties.tags["defibrillator:cabinet"]
          : null,
        "defibrillator:cabinet:manufacturer": feature.properties.tags[
          "defibrillator:cabinet:manufacturer"
        ]
          ? feature.properties.tags["defibrillator:cabinet:manufacturer"]
          : null,
        note: feature.properties.tags.note
          ? feature.properties.tags.note
          : null,
      });
    });
  });

  const addParsedData = (data) => {
    const originalData = JSON.parse(
      fs.readFileSync(`./data/${name}.json`, "utf8")
    );
    const newData = {
      message: copyMessage,
      updated: new Date(),
      aed: [...originalData.aed, data],
    };
    fs.writeFileSync(
      `./data/${name}.json`,
      JSON.stringify(newData, null, "\t")
    );
  };
}

module.exports = pullData;
