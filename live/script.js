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
        $("#fight-number").text(list.fightnumber);
        if(lastDescription != list.fightdescription){
            lastDescription = list.fightdescription;
            $("#description").text(list.fightdescription);
            $("#description").height( $("textarea")[0].scrollHeight );
        }
        var meronMainBet = list.meronmainbet;
        var walaMainBet = list.walamainbet;
        var meronDeduction = meronMainBet * 0.07;
        var walaDeduction = walaMainBet * 0.07;
        var meronRemaining = meronMainBet - meronDeduction;
        var walaRemaining = walaMainBet - walaDeduction;
        var meronPer100 = walaRemaining * (100 / meronMainBet);
        var walaPer100 = meronRemaining * (100 / walaMainBet);
        meronPer100 = meronPer100 + 100;
        walaPer100 = walaPer100 + 100;
        $("#meron-main-bet").text(formatToCurrency(meronMainBet));
        $("#wala-main-bet").text(formatToCurrency(walaMainBet));
        $("#meron-per100").text(formatToCurrency(meronPer100));
        $("#wala-per100").text(formatToCurrency(walaPer100));
        var status = list.fightstatus;
        if(lastStatus != status){
            lastStatus = status;
            if(status == "open"){
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-success">OPEN</span></p>';
            }else if(status == "lastcall"){
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-warning">LAST CALL</span></p>';
            }else if(status == "meronlocked"){
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">MERON LOCKED</span></p>';
            }else if(status == "walalocked"){
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">WALA LOCKED</span></p>';
            }else if(status == "locked"){
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">LOCKED</span></p>';
            }else{
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-secondary">WAITING</span></p>';
            }
            $("#fight-status-container").html(status);
        }
    })
}

function formatToCurrency(input){
    var output = parseFloat(input).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    return "₱" + output;
}