$(document).ready(function(){
    setTimeout(function(){
        $("#log-menu").attr("href","#");
        $("#log-menu").addClass("active");
    },100)
})

var baseUrl = $("#base-url").text();
var churchIdx;

getUserDetails();
getLogList();

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

function getLogList(){
    $.ajax({
		type: "POST",
		url: "get-log-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderLogList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderLogList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="log-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Date</th>\
                                <th>Time</th>\
                                <th>Log</th>\
                                <th>Account</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        markUp += '<tr>\
                        <td>'+list.date+'</td>\
                        <td>'+list.time+'</td>\
                        <td>'+list.log+'</td>\
                        <td>'+list.account+'</td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#log-table-container").html(markUp);
    $("#log-table").DataTable();
}

function clearLogs(idx){
    if(confirm("Are you sure you want to clear the entire log history?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "clear-log.php",
            dataType: 'html',
            data: {
                dummy:"dummy"
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getLogList();
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