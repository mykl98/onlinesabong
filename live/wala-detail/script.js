var lastDescription = "";
var lastStatus = "";
getDetails();

function getDetails(){
    $.ajax({
        type: "POST",
        url: "get-details.php",
        dataType: 'html',
        data: {
            dummy:"dummy"
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                renderDetail(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
            setTimeout(function(){
                getDetails();
            },5000);
        }
    });
}

function renderDetail(data){
    var lists = JSON.parse(data);
    lists.forEach(function(list){
        if(lastDescription != list.waladescription){
            lastDescription = list.waladescription;
            $("#description").text(list.waladescription);
            $("#description").height( $("textarea")[0].scrollHeight );
        }
        var meronMainBet = list.meronmainbet;
        var walaMainBet = list.walamainbet;
        var meronDeduction = meronMainBet * 0.07;
        var meronRemaining = meronMainBet - meronDeduction;
        var walaPer100 = meronRemaining * (100 / walaMainBet);
        walaPer100 = walaPer100 + 100;
        $("#wala-main-bet").text(meronMainBet);
        $("#wala-per100").text(walaPer100.toFixed(2));
    })
}