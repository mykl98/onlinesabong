$(document).ready(function() {
    setTimeout(function(){
        $("#betting-history-menu").attr("href","#");
        $("#betting-history-menu").addClass("active");
    },100)
})

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

var baseUrl = $("#base-url").text();
getUserDetails();
getBettingHistory();

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

function getBettingHistory(){
    $.ajax({
		type: "POST",
		url: "get-betting-history.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderBettingHistory(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderBettingHistory(data){
    //alert(data);
    var lists = JSON.parse(data);
    var markUp = '<table id="history-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Date</th>\
                                <th>Fight #</th>\
                                <th>Bet</th>\
                                <th>Amount</th>\
                                <th>Result</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var fightDetail = JSON.parse(list.fightdetail);
        var date = "";
        var number = "";
        var winner = "";
        fightDetail.forEach(function(detail){
            date = detail.date;
            number = detail.number;
            winner = detail.winner;
        })
        if(winner != "" || winner != undefined){
            var result = "";
            var side = list.side
            if(side == winner){
                result = '<span class="badge badge-success">Winner</span>';
            }else{
                result = '<span class="badge badge-danger">Losser</span>';
            }
            markUp += '<tr>\
                        <td>'+date+'</td>\
                        <td>'+number+'</td>\
                        <td>'+side+'</td>\
                        <td>'+list.amount+'</td>\
                        <td>'+result+'</td>\
                   </tr>';
        }
    })
    markUp += '</tbody></table>';
    //alert(markUp);
    $("#history-table-container").html(markUp);
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