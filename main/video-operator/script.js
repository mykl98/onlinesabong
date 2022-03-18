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
            $("#meron-description").text(list.merondescription);
            $("#meron-description").height( $("textarea")[0].scrollHeight );
            $("#wala-description").text(list.waladescription);
            $("#wala-description").height( $("textarea")[0].scrollHeight );
            $("#description").text(list.fightdescription);
            $("#description").height( $("textarea")[0].scrollHeight );
        }
        var meronMainBet = list.meronmainbet;
        var walaMainBet = list.walamainbet;
        var meronDeduction = meronMainBet * 0.07;
        var walaDeduction = walaMainBet * 0.07;
        var meronRemaining = meronMainBet - meronDeduction;
        var walaRemaining = walaMainBet - walaDeduction;
        var meronPer100 = 0;
        var walaPer100 = 0;
        if(walaRemaining != 0 && meronRemaining != 0){
            var meronPer100 = walaRemaining * (100/meronMainBet);
            var walaPer100 = meronRemaining * (100 / walaMainBet);
            meronPer100 = meronPer100 + 100;
            walaPer100 = walaPer100 + 100;
        }
        $("#meron-main-bet").text(formatToCurrency(meronMainBet));
        $("#wala-main-bet").text(formatToCurrency(walaMainBet));
        $("#meron-per100").text(formatToCurrency(meronPer100));
        $("#wala-per100").text(formatToCurrency(walaPer100));
        var status = list.fightstatus;
        if(lastStatus != status){
            lastStatus = status;
            if(status == "open"){
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-success">OPEN</span></h5>';
            }else if(status == "lastcall"){
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-warning">LAST CALL</span></h5>';
            }else if(status == "meronlocked"){
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-danger">MERON LOCKED</span></h5>';
            }else if(status == "walalocked"){
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-danger">WALA LOCKED</span></h5>';
            }else if(status == "locked"){
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-danger">LOCKED</span></h5>';
            }else{
                status = '<h5 class="text-white text-right p-2 m-0">Betting: <span class="badge badge-secondary">WAITING</span></h5S>';
            }
            $("#fight-status-container").html(status);
        }
    })
}

function formatToCurrency(input){
    var output = parseFloat(input).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    return "₱" + output;
}

switchPage("fight-break");

function switchPage(camera){
    $(".camera").hide();
    switch(camera) {
        case "fight-break":
            $("#fight-break").show();
            break;
        case "side-by-side":
            $("#side-by-side").show();
            break;
        case "fight-ongoing":
            $("#fight-ongoing").show();
            break;
        case "declare-winner":
            $("#declare-winner").show();
            break;
        default:
            break;
    }

    if (timer) {
        clearTimeout(timer);
    }
    timer = setTimeout(function(){
        $("#slider").show();
        $("#button").hide();
    },5000);
}

function declareWinner(winner){
    var result = "";
    switch(winner){
        case "draw":
            result = '<h5 class="text-white text-center p-2 m-0 bg-primary rounded">DRAW</h5>';
            break;
        case "meron":
            result = '<h5 class="text-white text-center p-2 m-0 bg-success rounded">MERON WIN!</h5>';
            break;
        case "wala":
            result = '<h5 class="text-white text-center p-2 m-0 bg-danger rounded">WALA WIN!</h5>';
            break;
        default:
            break;
    }
    $("#result-container").html(result);
    if(result != ""){
        setTimeout(function(){
            $("#result-container").html("");
        },10000)
    }
}

var timer;

function showButton(){
    $("#button").show();
    $("#slider").hide();
    timer = setTimeout(function(){
        $("#slider").show();
        $("#button").hide();
    },5000)
}