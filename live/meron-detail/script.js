var lastDescription = "";
var lastStatus = "";

function getDetails(){
    $.ajax({
        type: "POST",
        url: "meron-lock.php",
        dataType: 'html',
        data: {
            idx:idx
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                getEntryList();
                renderDetail(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
            setTimeout(function(){
                getDetails();
            },1000);
        },failure: function(error){
            setTimeout(function(){
                getDetails();
            },1000);
        }
    });
}

function renderDetail(data){
    var lists = JSON.parse(data);
    lists.forEach(function(list){
        if(lastDescription != list.merondescription){
            lastDescription = list.merondescription;
            $("#description").text(list.merondescription);
            $("#description").height( $("textarea")[0].scrollHeight );
        }
        var meronMainBet = list.meronmainbet;
        var meronDeduction = meronMainBet * 0.07;
        var meronRemaining = meronMainBet - meronDeduction;
        var meronPer100 = walaRemaining * (100 / meronMainBet);
        meronPer100 = meronPer100 + 100;
        walaPer100 = walaPer100 + 100;
        $("#meron-main-bet").text(meronMainBet);
        $("#meron-per100").text(meronPer100.toFixed(2));
    })
}