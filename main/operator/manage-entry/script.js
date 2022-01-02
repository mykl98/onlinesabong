$(document).ready(function() {
    setTimeout(function(){
        $("#manage-entry-menu").attr("href","#");
        $("#manage-entry-menu").addClass("active");
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
var entryIdx;
getEntryList();
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

function getEntryList(){
    $.ajax({
		type: "POST",
		url: "get-entry-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderEntryList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderEntryList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="entry-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Date</th>\
                                <th>Fight Number</th>\
                                <th>Meron</th>\
                                <th>Wala</th>\
                                <th>Status</th>\
                                <th style="max-width:50px;min-width:50px;">Action</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var status = list.status;
        var button = "";
        if(status == "waiting"){
            status = '<span class="badge badge-info">Waiting</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="startBetting('+list.idx+')"> Start Betting</a></li>\
                            <li><a href="#" class="pl-2" onclick="editEntry('+list.idx+')"> Edit Entry</a></li>\
                            <li><a href="#" class="pl-2" onclick="cancelEntry('+list.idx+')"> Cancel Entry</a></li>\
                        </ul>\
                      </div>';
        }else if(status == "open"){
            status = '<span class="badge badge-success">Open</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="lastCallBetting('+list.idx+')"> Set Last Call</a></li>\
                        </ul>\
                      </div>';
        }else if(status == "lastcall"){
            status = '<span class="badge badge-warning">Last Call</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="lockBetting('+list.idx+')"> Lock Betting</a></li>\
                        </ul>\
                      </div>';
        }else if(status == "locked"){
            status = '<span class="badge badge-danger">Locked</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="declareWinner('+list.idx+')"> Declare Winner</a></li>\
                        </ul>\
                      </div>';
        }else if(status == "finish"){
            status = '<span class="badge badge-warning">Finish</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="deleteEntry('+list.idx+')"> Delete Entry</a></li>\
                        </ul>\
                      </div>';
        }else if(status == "cancelled"){
            status = '<span class="badge badge-secondary">Cancelled</span>';
            button = '<div class="dropdown">\
                        <button type="button" data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">More</button>\
                        <ul class="dropdown-menu">\
                            <li><a href="#" class="pl-2" onclick="deleteEntry('+list.idx+')"> Delete Entry</a></li>\
                        </ul>\
                      </div>';
        }
        markUp += '<tr>\
                        <td>'+list.date+'</td>\
                        <td>'+list.number+'</td>\
                        <td>'+list.meron+'</td>\
                        <td>'+list.wala+'</td>\
                        <td>'+status+'</td>\
                        <td>'+button+'</td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#entry-table-container").html(markUp);
    $("#entry-table").DataTable();
}

function addEntry(){
    entryIdx = "";
    $("#add-edit-entry-modal-title").text("Add New Entry");
    $("#add-edit-entry-modal").modal("show");
}

function editEntry(idx){
    entryIdx = idx;
    $.ajax({
        type: "POST",
        url: "get-entry-detail.php",
        dataType: 'html',
        data: {
            idx:entryIdx
        },
        success: function(response){
            var resp = response.split("*_*");
            if(resp[0] == "true"){
                renderEditEntry(resp[1]);
            }else if(resp[0] == "false"){
                alert(resp[1]);
            } else{
                alert(response);
            }
        }
    });
}

function renderEditEntry(data){
    var lists = JSON.parse(data);
    lists.forEach(function(list){
        $("#entry-number").val(list.number);
        $("#entry-meron").val(list.meron);
        $("#entry-wala").val(list.wala);
    })
    $("#add-edit-account-modal-title").text("Edit Entry");
    $("#add-edit-entry-modal").modal("show");
}

function saveEntry(){
    var number = $("#entry-number").val();
    var meron = $("#entry-meron").val();
    var wala = $("#entry-wala").val();

    var error = "";
    if(number == "" || number == undefined){
        error = "*Fight number field should not be empty.";
    }else if(meron == "" || meron == undefined){
        error = "*Meron description field should not be empty.";
    }else if(wala == "" || wala == undefined){
        error = "*Wala description field should not be empty.";
    }else{
        $.ajax({
            type: "POST",
            url: "save-entry.php",
            dataType: 'html',
            data: {
                idx:entryIdx,
                number:number,
                meron:meron,
                wala:wala
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    $("#add-edit-entry-modal").modal("hide");
                    getEntryList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }

    $("#add-edit-entry-modal-error").text(error);
}

function cancelEntry(idx){
    if(confirm("Are you sure you want to cancel this Entry?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "cancel-entry.php",
            dataType: 'html',
            data: {
                idx:idx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getEntryList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function startBetting(idx){
    if(confirm("Are you sure you want to start the betting this Entry?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "start-betting.php",
            dataType: 'html',
            data: {
                idx:idx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getEntryList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function lastCallBetting(idx){
    if(confirm("Are you sure you want to send last call for this Entry?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "last-call.php",
            dataType: 'html',
            data: {
                idx:idx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getEntryList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function lockBetting(idx){
    if(confirm("Are you sure you want to lock the betting this Entry?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "lock-betting.php",
            dataType: 'html',
            data: {
                idx:idx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getEntryList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
}

function declareWinner(idx){
    entryIdx = idx;
    $("#declare-winner-modal").modal("show");
}

function declareMeron(){
    $("#declare-winner-modal").modal("hide");
    setTimeout(function(){
        if(confirm("Are you sure you want to declare meron as winner?\nThis Action cannot be undone!")){
            $.ajax({
                type: "POST",
                url: "declare-meron.php",
                dataType: 'html',
                data: {
                    idx:entryIdx
                },
                success: function(response){
                    var resp = response.split("*_*");
                    if(resp[0] == "true"){
                        getEntryList();
                    }else if(resp[0] == "false"){
                        alert(resp[1]);
                    } else{
                        alert(response);
                    }
                }
            });
        }
    },200)
}

function declareWala(){
    $("#declare-winner-modal").modal("hide");
    setTimeout(function(){
        if(confirm("Are you sure you want to declare wala as winner?\nThis Action cannot be undone!")){
            $.ajax({
                type: "POST",
                url: "declare-wala.php",
                dataType: 'html',
                data: {
                    idx:entryIdx
                },
                success: function(response){
                    var resp = response.split("*_*");
                    if(resp[0] == "true"){
                        getEntryList();
                    }else if(resp[0] == "false"){
                        alert(resp[1]);
                    } else{
                        alert(response);
                    }
                }
            });
        }
    },200)
}

function declareDraw(){
    $("#declare-winner-modal").modal("hide");
    setTimeout(function(){
        if(confirm("Are you sure you want to declare draw for this entry?\nThis Action cannot be undone!")){
            $.ajax({
                type: "POST",
                url: "declare-draw.php",
                dataType: 'html',
                data: {
                    idx:entryIdx
                },
                success: function(response){
                    var resp = response.split("*_*");
                    if(resp[0] == "true"){
                        getEntryList();
                    }else if(resp[0] == "false"){
                        alert(resp[1]);
                    } else{
                        alert(response);
                    }
                }
            });
        }
    },200)
}

function deleteEntry(idx){
    if(confirm("Are you sure you want to delete this Entry?\nThis Action cannot be undone!")){
        $.ajax({
            type: "POST",
            url: "delete-entry.php",
            dataType: 'html',
            data: {
                idx:idx
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    getEntryList();
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