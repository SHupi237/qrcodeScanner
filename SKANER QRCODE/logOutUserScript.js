window.onload = function() {   
    function onScanSuccess(decodedText, decodedResult) {
        const workersInformations = decodedResult.decodedText;
        const informationArray = workersInformations.split(',');
        const id = informationArray[0];
        const name = informationArray[1];
        const surname = informationArray[2];
        const password = informationArray[5];
        
        alert(workersInformations)
        $.ajax({
            type:'GET',
            url:'logOutUser.php?id=' + id + '&name=' + name + '&surname=' + surname,
            success: function(response) {
                if (response !== 'false') {
                    window.location.href='logOutUser.php?id=' + id + '&name=' + name + '&surname=' + surname;
                }else{
                    alert('Nie udane logowanie.Skontaktuj się z administratorem strony')
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