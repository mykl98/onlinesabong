$(document).ready(function(){
    setTimeout(function(){
        $("#manage-user-menu").attr("href","#");
        $("#manage-user-menu").addClass("active");
    },100)
})

$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal.show').length && $(document.body).addClass('modal-open');
});

$(".modal").on("hidden.bs.modal",function(){
    $(this).find("form").trigger("reset");
    $('.modal-backdrop').remove();
})

var baseUrl = $("#base-url").text();
var userIdx;

getUserDetails();
getUserList();

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

function getUserList(){
    $.ajax({
		type: "POST",
		url: "get-user-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderUserList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderUserList(data){
    //alert(data);
    var lists = JSON.parse(data);
    var markUp = '<table id="user-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Name</th>\
                                <th>Username</th>\
                                <th>Wallet</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        markUp += '<tr>\
                        <td>'+list.name+'</td>\
                        <td>'+list.username+'</td>\
                        <td>'+formatToCurrency(list.wallet)+'</td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#user-table-container").html(markUp);
    $("#user-table").DataTable();
}

function formatToCurrency(input){
    var output = parseFloat(input).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    return "₱" + output;
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