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
var lastDescription = "";
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
        var wallet = list.wallet;
        wallet = wallet * 1;
        $("#wallet").text(formatToCurrency(wallet));
        $("#fight-number").text(list.fightnumber);
        if(lastDescription != list.fightdescription){
            lastDescription = list.fightdescription;
            $("#description").text(list.fightdescription);
            $("#description").height( $("textarea")[0].scrollHeight );
        }
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
        var meronPayout = meronBet*1 + meronWin;
        var walaPayout = walaBet*1 + walaWin;
        var meronPer100 = walaRemaining * (100 / meronMainBet);
        var walaPer100 = meronRemaining * (100 / walaMainBet);
        meronPer100 = meronPer100 + 100;
        walaPer100 = walaPer100 + 100;
        $("#meron-main-bet").text(formatToCurrency(meronMainBet));
        $("#wala-main-bet").text(formatToCurrency(walaMainBet));
        $("#meron-bet").text(formatToCurrency(meronBet));
        $("#wala-bet").text(formatToCurrency(walaBet));
        $("#meron-payout").text(formatToCurrency(meronPayout));
        $("#wala-payout").text(formatToCurrency(walaPayout));
        $("#meron-per100").text(formatToCurrency(meronPer100));
        $("#wala-per100").text(formatToCurrency(walaPer100));
        var status = list.fightstatus;
        if(lastStatus != status){
            lastStatus = status;
            if(status == "open"){
                switchButtonState("enabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-success">OPEN</span></p>';
            }else if(status == "lastcall"){
                switchButtonState("enabled");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-warning">LAST CALL</span></p>';
            }else if(status == "meronlocked"){
                switchButtonState("meronlocked");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">MERON LOCKED</span></p>';
            }else if(status == "walalocked"){
                switchButtonState("walalocked");
                status = '<p class="pl-2 font-weight-bold text-right">Betting: <span class="badge badge-danger">WALA LOCKED</span></p>';
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
    }else if(state == "disabled"){
        $("#meron-button").prop("disabled",true);
        $("#wala-button").prop("disabled",true);
        $("#meron-button").removeClass("btn-success");
        $("#wala-button").removeClass("btn-danger");
        $("#meron-button").addClass("btn-secondary");
        $("#wala-button").addClass("btn-secondary");
    }else if(state == "meronlocked"){
        $("#meron-button").prop("disabled",true);
        $("#meron-button").removeClass("btn-success");
        $("#meron-button").addClass("btn-secondary");
        $("#wala-button").prop("disabled",false);
        $("#wala-button").removeClass("btn-secondary");
        $("#wala-button").addClass("btn-danger");
    }else if(state == "walalocked"){
        $("#wala-button").prop("disabled",true);
        $("#wala-button").removeClass("btn-danger");
        $("#wala-button").addClass("btn-secondary");
        $("#meron-button").prop("disabled",false);
        $("#meron-button").removeClass("btn-secondary");
        $("#meron-button").addClass("btn-success");
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

function formatToCurrency(input){
    var output = parseFloat(input).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    return "₱" + output;
}

$(".currency").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});

function formatCurrency(input, blur) {
    var input_val = input.val();
    
    // don't validate empty input
    if (input_val === "") { return; }
    
        // original length
        var original_len = input_val.length;
  
        // initial caret position 
        var caret_pos = input.prop("selectionStart");
      
        // check for decimal
        if (input_val.indexOf(".") >= 0) {
  
            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");
  
            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
  
            // add commas to left side of number
            left_side = formatNumber(left_side);
  
            // validate right side
            right_side = formatNumber(right_side);
      
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
      
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
  
            // join number by .
            input_val = "₱" + left_side + "." + right_side;
  
    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "₱" + input_val;
      
        // final formatting
        if (blur === "blur") {
            input_val += ".00";
        }
    }
    
    // send updated string to input
    input.val(input_val);
  
    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}

function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
