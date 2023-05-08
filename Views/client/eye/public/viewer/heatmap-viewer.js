var frameData = [];

var frameIndex = 0;
var framesPerSecond = 15;

var renderedFrames = [];

var heatMap;

var params;

var getJSON = function(url, callback) {

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    
    xhr.onload = function() {
    
        var status = xhr.status;
        
        if (status == 200) {
            callback(null, xhr.response);
        } else {
            callback(status);
        }
    };
    
    xhr.send();
};





var displayLoop = function () {
    var data = frameData[frameIndex];

    console.log(data.FrameNr);
    renderedFrames.push(document.querySelector('.heatmap-canvas').toDataURL());

    heatMap.addData({x: data.GazeX, y: data.GazeY, value: 0.2});

    frameIndex++;

    if (frameIndex < frameData.length) {
        setTimeout(displayLoop, 1000/framesPerSecond);
    } else {

        if (confirm('Save video to server?')) {
            sendVidToServer();
            alert('Video sent to server!');
        }
        

    }

};



var sendVidToServer = function () {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/video', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        data: renderedFrames,
        filename: params.get('id')
    }));
};


window.addEventListener('load', function() {


    console.log('loaded');

    
    heatMap = h337.create({
        container: document.body,
        backgroundColor: '#ffffff',
        //opacity: 1,
        radius: 50

    });




    params = new URLSearchParams(location.search);
    getJSON('../results/etr_' + params.get('id') + '.json',  function(err, data) {
        
        if (err != null) {
            console.error(err);
        } else {
            console.log(data.length);

            frameData = data;
            displayLoop();
        }

    });
    

});



