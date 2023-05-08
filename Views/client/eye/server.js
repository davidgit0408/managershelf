console.log('Server-side code running');
const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
//convert json to csv
const csvjson = require('csvjson');
//var temp_data = require('./public/etr_1588501219858.json');

const app = express();

app.use(bodyParser({limit: '50mb'}));

// serve files from the public directory
app.use(express.static('public'));

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// start the express web server listening on 8080
app.listen(8080, () => {
  console.log('listening on 8080');
});

// serve the homepage
app.get('/', (req, res) => {
  res.sendFile(__dirname + '/public');
});

//post per creare file
app.post('/clicked', (req, res) => {
  console.log('store data: ', req.body);
  let data = JSON.stringify(req.body.data, null, '\t');

  const csvData = csvjson.toCSV(req.body.data, {
    headers: 'key'
  });

  console.log("Change json data in csv: ", csvData);

  fs.writeFile(`public/results/${req.body.filename}.json`, data, error => {
    if (error) {
      console.log("An error occurred: ", error);
      res.sendStatus(500);
    } else {
      console.log('Your file is made!');

      fs.writeFile(`public/results/${req.body.filename}.csv`, csvData, error => {
        if (error) {
          console.log("An error occurred: ", error);
          res.sendStatus(500);
        } else {
          console.log('Your file is made!');
          res.sendStatus(201);
        }

      })
    }
  })
});


// code to receive video data from server


var ffmpegPath = require('@ffmpeg-installer/ffmpeg').path;
var ffmpeg = require('fluent-ffmpeg');
ffmpeg.setFfmpegPath(ffmpegPath);
var command = ffmpeg();



app.post('/video', (req, res) => {

  console.log('Video incoming ');
  console.log('Video id: ' + req.body.filename);
  console.log(req.body.data.length);

  var leadingZeroes = 4;

  for (var i = 1; i < req.body.data.length; i++) {
    var frameName = 'etr_' + req.body.filename + '_' + pad(i, 4) + '.png';

    var base64Data = req.body.data[i].replace(/^data:image\/png;base64,/, "");

    fs.writeFile('frames/'+frameName, base64Data, 'base64', function(err) {
      if(err != null) console.log(err);
    });

    console.log(frameName);
  }

  res.sendStatus(200);
  res.end();

  command
    .input('frames/etr_' + req.body.filename + '_%04d.png')
    .inputFPS(15)
    .output('videos/etr_'+req.body.filename+'.avi')
    .outputFPS(15)
    .noAudio()
    .run();



});

function pad(num, size) {
  var s = num+"";
  while (s.length < size) s = "0" + s;
  return s;
}