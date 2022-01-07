$(document).ready(function(){
    setTimeout(function(){
        $("#manage-wallet-menu").attr("href","#");
        $("#manage-wallet-menu").addClass("active");
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
    //alert(data);
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
                        <td>'+formatToCurrency(list.amount)+'</td>\
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
    prevDecodedText = "";
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
    lists.forEach(function(list){
        var image = list.image;
        var wallet = list.wallet;
        if(image != "" && image != undefined){
            $("#qr-image").attr("src",image);
        }
        $("#qr-name").val(list.name);
        $("#qr-wallet").val(formatToCurrency(wallet));
        userIdx = list.idx;
    })
    $("#qr-scan-modal").modal("show");
}

function process(){
    var amount = $("#qr-amount").val();
    var type = $("#qr-type").val();
    var error = "";
    //alert(type);
    if(amount == "" || amount == undefined){
        error = "*Amount field should not be empty!";
    }else if(type == "" || type == undefined){
        error = "*Please select transaction type.";
    }else{
        $.ajax({
            type: "POST",
            url: "process.php",
            dataType: 'html',
            data: {
                idx:userIdx,
                amount:amount,
                type:type
            },
            success: function(response){
                var resp = response.split("*_*");
                if(resp[0] == "true"){
                    $('#qr-scan-modal').modal("hide");
                    getTransactionList();
                }else if(resp[0] == "false"){
                    alert(resp[1]);
                } else{
                    alert(response);
                }
            }
        });
    }
    $("#qr-scan-modal-error").text(error);
}

var prevDecodedText = "";

function onScanError(errorMessage) {
    // handle on error condition, with error message
    alert(errorMessage);
}

function onScanSuccess(decodedText, decodedResult) {
    if(prevDecodedText != decodedText){
        prevDecodedText = decodedText;
        getUserDetail(decodedText);
        $("#qr-reader-modal").modal("hide");
    }
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