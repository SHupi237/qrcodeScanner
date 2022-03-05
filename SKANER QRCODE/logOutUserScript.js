window.onload = function() {   
     //Skrypt ten pochodzący z gita,odpowiada za działanie skanera qrcode
    function onScanSuccess(decodedText, decodedResult) {
        const workersInformations = decodedResult.decodedText;
        const informationArray = workersInformations.split(',');
        //Tutaj wyłaniamy interesujące nas dane z stworzonego przez nas arraya
        const id = informationArray[0];
        const name = informationArray[1];
        const surname = informationArray[2];
        const area_id = informationArray[4];

        const password = informationArray[5];
        //W ajaxie wysyłamy dane do logOutUser.php gdzie dane będą wykorzystane do zapytania sql
        $.ajax({
            type:'GET',
            url:'logOutUser.php?id=' + id + '&name=' + name + '&surname=' + surname,
            success: function(response) {
                if (response !== 'false') {
                    //Jeśli odpowiedź jest inna niż false to wysyłamy dane użtkownika do wylogowania
                    alert("Wylogowanie przeszedło pomyśłnie")
                    setTimeout(window.location.href='logOutUser.php?id=' + id + '&name=' + name + '&surname=' + surname + '&area_id=' + area_id,5000)
                }else{
                    alert('Nie udane wylogowanie.Skontaktuj się z administratorem strony')
                } 
            },
        });
    }
    function onScanFailure(error) {
        return true;
    }
    let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 20, qrbox: {width: 250, height: 250} },false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure); 
    }