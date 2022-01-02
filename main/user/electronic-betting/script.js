$(document).ready(function() {
    setTimeout(function(){
        $("#electronic-betting-menu").attr("href","#");
        $("#electronic-betting-menu").addClass("active");
    },100)
});

/*============== Toggle Dropdown ==================*/
function toggle_menu(ele) {
    //close all ul with children class that are open except the one with the selected id
    $( '.children' ).not( document.getElementById(ele) ).slideUp("Normal");
    $( "#"+ele ).slideToggle("Normal");
    localStorage.setItem('lastTab', ele);
}

$(document).on('shown.lte.pushmenu', function(){
    $("#global-department-name").show();
})

$(document).on('collapsed.lte.pushmenu', function(){
    $("#global-department-name").hide();
})

$(".modal").on("hidden.bs.modal",function(){
    $(this).find("form").trigger("reset");
})

var fightIdx;
var side;
var lastStatus = "1234";
var baseUrl = $("#base-url").text();
getUserDetails();
getDetails();

function getUserDetails(){
    $.ajax({
        type: "POST",
        url: "get-profile-settings.php",
        dataType: 'html',
        data: {
            dummy:"dummy"
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                renderUserDetails(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}

function renderUserDetails(data){
    var lists = JSON.parse(data);

    lists.forEach(function(list){
        if(list.image != ""){
            $("#global-user-image").attr("src", list.image);
        }
        $("#global-user-name").text(list.name);
    })

}

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
                renderDetails(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
            setTimeout(function(){
                getDetails();
            },5000);
        },
        failure: function(){
            setTimeout(function(){
                getDetails();
            },5000);
        }
    });
}

function renderDetails(data){
    //alert(data);
    var lists = JSON.parse(data);
    lists.forEach(function(list){
        $("#wallet").text(list.wallet);
        $("#fight-number").text(list.fightnumber);
        fightIdx = list.fightidx;
        var meronMainBet = list.meronmainbet;
        var walaMainBet = list.walamainbet;
        var meronBet = list.meronbet;
        var walaBet = list.walabet;
        var meronDeduction = meronMainBet * 0.07;
        var walaDeduction = walaMainBet * 0.07;
        var meronRemaining = meronMainBet - meronDeduction;
        var walaRemaining = walaMainBet - walaDeduction;
        var meronWin = walaRemaining * (meronBet/meronMainBet);
        var walaWin = meronRemaining * (walaBet/walaMainBet);
        var meronPayout = (meronBet*1 + meronWin).toFixed(2);
        var walaPayout = (walaBet*1 + walaWin).toFixed(2);
        $("#meron-main-bet").text(meronMainBet);
        $("#wala-main-bet").text(walaMainBet);
        $("#meron-bet").text(meronBet);
        $("#wala-bet").text(walaBet);
        $("#meron-payout").text(meronPayout);
        $("#wala-payout").text(walaPayout);
        var status = list.fightstatus;
        if(lastStatus != status){
            lastStatus = status;
            //alert(status);
            if(status == "open"){
                switchButtonState("enabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-success">OPEN</span></p>';
            }else if(status == "lastcall"){
                switchButtonState("enabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-warning">LAST CALL</span></p>';
            }else if(status == "locked"){
                switchButtonState("disabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">LOCKED</span></p>';
            }else{
                switchButtonState("disabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-secondary">WAITING</span></p>';
            }
            $("#fight-status-container").html(status);
        }
    })
}

function switchButtonState(state){
    if(state == "enabled"){
        $("#meron-button").prop("disabled",false);
        $("#wala-button").prop("disabled",false);
        $("#meron-button").removeClass("btn-secondary");
        $("#wala-button").removeClass("btn-secondary");
        $("#meron-button").addClass("btn-success");
        $("#wala-button").addClass("btn-danger");
    }else{
        $("#meron-button").prop("disabled",true);
        $("#wala-button").prop("disabled",true);
        $("#meron-button").removeClass("btn-success");
        $("#wala-button").removeClass("btn-danger");
        $("#meron-button").addClass("btn-secondary");
        $("#wala-button").addClass("btn-secondary");
    }
}

function addBetMeron(){
    side = "meron";
    $("#add-bet-modal-title").text("Add Bet to Meron");
    $("#add-bet-modal").modal("show");
}

function addBetWala(){
    side = "wala";
    $("#add-bet-modal-title").text("Add Bet to Wala");
    $("#add-bet-modal").modal("show");
}

function confirmBet(){
    var betAmount = $('#bet-amount').val();
    //alert(betAmount);
    var error = "";
    if(betAmount == "" || betAmount == undefined){
        error = "*Bet amount field should not be empty!";
    }else{
        $.ajax({
            type: "POST",
            url: "add-bet.php",
            dataType: 'html',
            data: {
                fightidx:fightIdx,
                side:side,
                bet:betAmount
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getDetails();
                    $("#add-bet-modal").modal("hide");
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
    $("#add-bet-modal-error").text(error);
}

function logout(){
    $.ajax({
        type: "POST",
        url: "logout.php",
        dataType: 'html',
        data: {
            dummy:"dummy"
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                window.open(baseUrl + "/index.php","_self")
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}
