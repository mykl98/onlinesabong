$(document).ready(function(){
    setTimeout(function(){
        $("#electronic-logbook-menu").attr("href","#");
        $("#electronic-logbook-menu").addClass("active");
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
getTransactionList();

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

function getTransactionList(){
    $.ajax({
		type: "POST",
		url: "get-transaction-list.php",
		dataType: 'html',
		data: {
			dummy:"dummy"
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderTransactionList(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderTransactionList(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="transaction-table" class="table table-striped table-bordered table-sm">\
                        <thead>\
                            <tr>\
                                <th>Date</th>\
                                <th>Time</th>\
                                <th>Amount</th>\
                                <th>User</th>\
                                <th>Transaction</th>\
                            </tr>\
                        </thead>\
                        <tbody>';
    lists.forEach(function(list){
        var transaction = list.transaction;
        if(transaction == "cashin"){
            transaction = '<span class="badge badge-success">Cash In</span>';
        }else if(transaction == "cashout"){
            transaction = '<span class="badge badge-danger">Cash Out</span>';
        }
        markUp += '<tr>\
                        <td>'+list.date+'</td>\
                        <td>'+list.time+'</td>\
                        <td>'+list.amount+'</td>\
                        <td>'+list.user+'</td>\
                        <td>'+transaction+'</td>\
                   </tr>';
    })
    markUp += '</tbody></table>';
    $("#transaction-table-container").html(markUp);
    $("#transaction-table").DataTable();
}

function scanQr(){
    $("#qr-reader-modal").modal("show");
}

function getUserDetail(qr){
    $.ajax({
		type: "POST",
		url: "get-user-detail.php",
		dataType: 'html',
		data: {
			qr:qr
		},
		success: function(response){
			var resp = response.split("*_*");
			if(resp[0] == "true"){
				renderUserDetail(resp[1]);
			}else if(resp[0] == "false"){
				alert(resp[1]);
			} else{
				alert(response);
			}
		}
	});
}

function renderUserDetail(data){
    var lists = JSON.parse(data);
    var markUp = '<table id="qr-booking-table" class="table table-striped table-bordered table-sm">\
                    <thead>\
                        <tr>\
                            <th>Type</th>\
                            <th>Time</th>\
                        </tr>\
                    </thead>\
                    <tbody>';
    lists.forEach(function(list){
        var bookings = JSON.parse(list.booking);
        bookings.forEach(function(book){   
            markUp += '<tr>\
                        <td>'+book.type+'</td>\
                        <td>'+book.time+'</td>\
                       </tr>';
        })
        
        userIdx = list.idx;
        $("#qr-name").val(list.name);
        $("#qr-address").val(list.address);
        var image = list.image;
        if(image != ""){
            $("#qr-image").attr("src",image);
        }
    })
    markUp += '</tbody></table>';
    $("#qr-booking-table-container").html(markUp);
    $("#qr-scan-modal").modal("show");
}

function processTransaction(){
    if(userIdx == ""){
        error = "*Unable to login, No scanned user detected.";
    }else{
        if(confirm("Are your sure you want to process this transaction?")){
            $.ajax({
                type: "POST",
                url: "process-transaction.php",
                dataType: 'html',
                data: {
                    idx:userIdx,
                    activity:"login"
                },
                success: function(response){
                    var resp = response.split("*_*");
                    if(resp[0] == "true"){
                        $("#qr-scan-modal").modal("hide");
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
}

var prevDecodedText = "";

function onScanError(errorMessage) {
    // handle on error condition, with error message
    alert(errorMessage);
    setTimeout(function(){
        prevDecodedText = "";
    },10000);
}

function onScanSuccess(decodedText, decodedResult) {
    if(prevDecodedText !== decodedText){
        prevDecodedText = decodedText;
        getUserDetail(decodedText);
        $("#qr-reader-modal").modal("hide");
        setTimeout(function(){
            prevDecodedText = "";
        },10000);
    }
}

var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);

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