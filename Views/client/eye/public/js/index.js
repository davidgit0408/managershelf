var Results = new function ResultsInit() {

    let filename = '';
    let storeData = [];

    this.FileName = function () {
        filename = 'etr_' + Date.now();
        storeData[filename] = [];
        console.debug(filename)
    }

    this.StoreData = function (data) {
        if (data.state === 0) {
            storeData[filename].push(data)
        }

    }

    this.CreateFile = function () {
        //require fs to create a new file 
        let bodyValue = {
            filename: filename,
            data: storeData[filename]
        }
        fetch('/clicked',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(bodyValue)
            })
            .then(function (response) {
                if (response.ok) {
                    console.log('Click was recorded');
                    return;
                }
                throw new Error('Request failed.');
            })
            .catch(function (error) {
                console.log(error);
            });
    }

}
