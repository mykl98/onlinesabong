$(document).ready(function(){
    setTimeout(function(){
        $("#manage-teller-menu").attr("href","#");
        $("#manage-teller-menu").addClass("active");
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

var churchIdx;
var baseUrl = $("#base-url").text();

getTellerList();
getUserDetails();

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

function getTellerList(){
    $.ajax({
		type: "POST",
		url: "get-teller-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderTellerList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderTellerList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="teller-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Name</th>\
                                <th>Cash In</th>\
                                <th>Cash Out</th>\
                                <th>Balance</th>\
                                <th style="min-width:50px;max-width:50px;">Action</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var balance = list.cashin*1 - list.cashout*1;
        markUp += '<tr>\
                        <td>'+list.name+'</td>\
                        <td>'+list.cashin+'</td>\
                        <td>'+list.cashout+'</td>\
                        <td>'+balance+'</td>\
                        <td>\
                            <div class="dropdown">\
                                <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                                <ul class="dropdown-menu">\
                                    <li><a href="#" class="pl-2" onclick="recieveBalance('+list.idx+','+balance+')">Collect Balance</a></li>\
                                </ul>\
                            </div>\
                        </td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#teller-table-container").html(markUp);
    $("#teller-table").DataTable();
}

function recieveBalance(idx,balance){
    if(confirm("Make sure that you have recieved the amount indicated in the balance before procedding this action.")){
        $.ajax({
            type: "POST",
            url: "recieve-balance.php",
            dataType: 'html',
            data: {
                idx:idx,
                balance:balance
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getTellerList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
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