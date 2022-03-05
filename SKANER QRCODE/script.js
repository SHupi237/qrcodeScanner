window.onload = function() {   
    //Skrypt ten pochodzący z gita,odpowiada za działanie skanera qrcode
    function onScanSuccess(decodedText, decodedResult) {
        const workersInformations = decodedResult.decodedText;
        const informationArray = workersInformations.split(',')
        //Tutaj wyłaniamy interesujące nas dane z stworzonego przez nas arraya
        const name = informationArray[1];
        const surName = informationArray[2];
        const password = informationArray[5];
        //W ajaxie wysyłamy dane do getUser.php gdzie dane będą wykorzystane do zapytania sql
        $.ajax({
            type:'GET',
            url:'getUser.php?name=' + name + '&surname=' + surName + '&password=' + password,
            success: function(response) {
                if (response !== 'false') {
                    //Jeśli zwróci nam coś innego niż fale,dane zwrócone z getUser.php wysyłamy do areaSite.php w linku
                    window.location.href = './areaSite.php?id='+ JSON.parse(response).id +'&area_id=' + JSON.parse(response).area_id + '&name='+JSON.parse(response).name + '&surname=' + JSON.parse(response).surName;
                } else {
                    alert('Nie ma takiego pracownika');
                }
            },
        })
    }
    function onScanFailure(error) {
        return true
    }
    let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 20, qrbox: {width: 250, height: 250} },false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure); 
    }
