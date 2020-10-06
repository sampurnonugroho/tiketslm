<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
<script src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
<script src="<?=base_url()?>assets/notify.min.js"></script>
<table>
	
</table>
	<b>Actions:</b>
	<div id="status"></div>
	 
	<br />
	 
	<b>All Items:</b>
	<div id="results"></div>
<script>
	// $("table").notify(
		// "I'm to the right of this box", 
		// { position:"right middle", className: "success" }
	// );
	// $.notify(
		// "I'm to the right of this box", 
		// { position:"right middle", className: "success" }
	// );

	var data_array = [];
	$(document).scannerDetection({
		timeBeforeScanTest: 40, // 1000 wait for the next character for upto 200ms
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
		preventDefault: true,
		endChar: [13],
		onComplete: function(barcode, qty) {
			// var obj = {};
			// obj.kode = barcode;
			// obj.jenis = "seal";
			// obj.status = "available";
			// data_array.push(obj);
			
			// $.notify(
				// "I'm to the right of this box", 
				// { position:"right middle", className: "success" }
			// );
			
			// console.log(data_array);
			// process_item(item_id,item_value);
			// $.notify(JSON.stringify(barcode), "success");
			$.post('<?php echo base_url().'seal/check_seal'?>', { value: barcode })
				.done(function( data ) {
					result = JSON.parse(data);
					$.notify(JSON.stringify(result), "error");
					if(result.result==-1) {
						$.notify("Seal "+barcode+", tidak dikenal!", "error");
						data = {
							'info': result.info,
							'table': result.table,
							'jenis': result.jenis,
							'status': 'unidentified'
						};
						process_item(barcode, data);
					} else {
						if(result.result==0) {
							$.notify("Seal "+barcode+", sudah terpakai!", "error");
							data = {
								'info': result.info,
								'table': result.table,
								'jenis': result.jenis,
								'status': 'used'
							};
							process_item(barcode, data);
						} else {
							$.notify("Seal "+barcode+", siap digunakan!", "success");
							data = {
								'info': result.info,
								'table': result.table,
								'jenis': result.jenis,
								'status': 'available'
							};
							process_item(barcode, data);
						}
					}
				}
			);
		}, 
		onError: function(string, qty) {
			var execute = false;
			// $.notify(barcode, "error");
			// console.log(data_array);
			if(string=="1") {
				execute = true;
				barcode = "195.2001";
			} else if(string=="2") {
				execute = true;
				barcode = "195.2002";
			} else if(string=="3") {
				execute = true;
				barcode = "195.2003";
			} else {
				if(!isNaN(string)) {
					$.notify("Nomor "+string+", gak ada data!", "warn");
				} else {
					if(string.indexOf("%") != -1) {
						$.notify("Battery "+string+", tersisa!", "error");
					} else {
						$.notify("Huruf "+string+", gak ada data!", "warn");
					}
				}
				execute = false;
			}
			
			if(execute) {
				$.post('<?php echo base_url().'seal/check_seal'?>', { value: barcode })
					.done(function( data ) {
						result = JSON.parse(data);
						// $.notify(JSON.stringify(result), "error");
						if(result.result==-1) {
							$.notify("Seal "+barcode+", tidak dikenal!", "error");
							data = {
								'info': result.info,
								'table': result.table,
								'jenis': result.jenis,
								'status': 'unidentified'
							};
							process_item(barcode, data);
						} else {
							if(result.result==0) {
								$.notify("Seal "+barcode+", sudah terpakai!", "error");
								data = {
									'info': result.info,
									'table': result.table,
									'jenis': result.jenis,
									'status': 'used'
								};
								process_item(barcode, data);
							} else {
								$.notify("Seal "+barcode+", siap digunakan!", "success");
								data = {
									'info': result.info,
									'table': result.table,
									'jenis': result.jenis,
									'status': 'available'
								};
								process_item(barcode, data);
							}
						}
					}
				);
			}
		}
	});
	
	function guid() {
		return parseInt(Date.now() + Math.random());
	}
	
	// PROCESS
	function process_item(item_kode, item_value) {
        
        if(item_value==""){
            delete_item(item_kode);
        }else if(checkIfExists(item_kode)){
            edit(item_kode,item_value);
        }else if(!checkIfExists(item_kode)){
            add(item_kode,item_value);
        }
        
        //read the items
        showAllItems();
    }
	
	// ADD
    function add(item_kode, item_value){
		
        data_array.push({
            "item_kode" : item_kode,
            "item_info" : item_value.info,
            "item_table" : item_value.table,
            "item_jenis" : item_value.jenis,
            "item_status" : item_value.status
        });
        $("#status").text("Successfully added.");
    }
 
    // EDIT
    function edit(item_kode, item_value){
        
        //delete first
        data_array.remove("item_kode", item_kode);
        
        //then add again
        data_array.push({
            "item_kode" : item_kode,
			"item_info" : item_value.info,
			"item_table" : item_value.table,
            "item_jenis" : item_value.jenis,
            "item_status" : item_value.status
        });
        $("#status").text("Successfully edited.");
            
    }
    
    // DELETE
    function delete_item(item_kode){
        data_array.remove("item_kode", item_kode);
        $("#status").text("Successfully deleted.");
    }
	
	// READ
	function showAllItems(){
        status = $("#status_list").val();
        //clear results text
        $("#results").text("");
		
		sel_avail = (status=="available") ? "selected" : "";
		sel_used = (status=="used") ? "selected" : "";
        
        var arr_len = data_array.length;
        
        // display also the array length
		var select = ""+
			"<select id='status_list'>"+
			"<option value='available' "+sel_avail+">available</option>"+
			"<option value='used' "+sel_used+">used</option>"+
			"</select>"+
		"";
		
        $("#results").append("Array len: " + arr_len+ " <button style='color: blue' onclick='update_all_seal()'>Set All to</button> "+select+"<br />");
        
        //loop through the array to read the items
        for(var x=0; x<arr_len; x++){
            var item_kode = data_array[x]['item_kode'];
            var item_info = data_array[x]['item_info'];
            var item_table = data_array[x]['item_table'];
            var item_jenis = data_array[x]['item_jenis'];
            var item_status = data_array[x]['item_status'];
            
            //append to results div to display
			
			var button = "";
			var deletes = "";
			if(item_status=="unidentified") {				
				button = "<button style='color: blue' onclick='register_seal(\""+item_kode+"\",\""+item_jenis+"\",\""+item_table+"\",\""+item_info+"\")'>Register It</button>";
				// deletes = "<button style='color: red' onclick='delete_seal(\""+item_kode+"\",\""+item_jenis+"\")'>Delete It</button>";
			} else {
				button = "<button style='color: blue' onclick='register_seal(\""+item_kode+"\",\""+item_jenis+"\",\""+item_table+"\",\""+item_info+"\")'>Update</button>";
				deletes = "<button style='color: red' onclick='delete_seal(\""+item_kode+"\",\""+item_jenis+"\")'>Delete It</button>";
			}
			
			html = ""+
				"item_kode: " + item_kode  + " || " +
				"item_info: " + item_info  + " || " +
				"item_jenis: " + item_jenis  + " || " +
				"item_status: <span style='padding: 1px; background-color: #7FFF00; font-weight: bold; font-size: 16px'>" + item_status  + "</span> || " + button + deletes +
				"<br />"+
			"";
            $("#results").append(html);
        }
		
		console.log(data_array);
    }
	
	function register_seal(item_kode, item_jenis, item_table, item_info) {
		// $.notify(item_kode+", "+item_jenis+", "+item_table, "warn");
		$.post('<?php echo base_url().'seal/update_seal'?>', {kode: item_kode, jenis: item_jenis, table: item_table, info: item_info})
		.done(function(data) {
			$.notify(data, "warn");
			data = JSON.parse(data);
			console.log(data);
			if(checkIfExists(data.kode)){
				edit(data.kode,data);
			}else if(!checkIfExists(data.kode)){
				add(data.kode,data);
			}
			
			showAllItems();
		});
	}
	
	
	function update_all_seal() {
		status = $("#status_list").val();
		$.post('<?php echo base_url().'seal/update_all_seal'?>', {data: JSON.stringify(data_array), status: status}).done(function(data) {
			// console.log(data);
			data = JSON.parse(data);
			$.each(data, function( index, value ) {
				console.log(value.kode);
				if(checkIfExists(value.kode)){
					edit(value.kode,value);
				}
			});
			
			showAllItems();
		});
	}
	
	function delete_seal(item_kode, item_jenis) {
		myFunction(item_kode,item_jenis);
	}
	
	function myFunction(item_kode, item_jenis) {
		var answer = prompt("Are you sure? Please type 'YES'!");
		var n = answer.length;
		if(n > 0) {
			if (answer != null && answer == "YES") { 
				myFunction2(item_kode,item_jenis)
			} else {
				myFunction(item_kode,item_jenis)
			}
		} else if(n == 0) {  
			myFunction(item_kode,item_jenis)
		} 
	}
	
	function myFunction2(item_kode, item_jenis) {
		var answer = prompt("REALLY? Please type 'YES I AM'!");
		var n = answer.length;
		if(n > 0) {
			if (answer != null && answer == "YES I AM") { 
				$.notify("DATA DELETED!", "error");
				// $.post('<?php echo base_url().'seal/delete_seal'?>', {kode: item_kode, jenis: item_jenis}).done(function(data) {
					// if(data=="success") {
						// $.notify("DATA DELETED!", "error");
						// delete_item(item_kode);
					// }
					
					// showAllItems();
				// });
			} else {
				myFunction2(item_kode,item_jenis)
			}
		} else if(n == 0) {  
			myFunction2(item_kode,item_jenis)
		} 
	}
	
	function checkIfExists(check_item_kode) {
        //get the array length first
        var arr_len = data_array.length;
        
        //search the array
        //there might be another way
        for(var x=0; x<arr_len; x++){
            var item_kode = data_array[x]['item_kode'];
            
            if(check_item_kode==item_kode){
                //it means the item exists
                return true;
            }
        }
        
        return false;
    }
	
	//needs a remove function
    Array.prototype.remove = function(name, value) {
        array = this;
        var rest = $.grep(this, function(item){
            return (item[name] != value);  
        });
 
        array.length = rest.length;
        $.each(rest, function(n, obj) {
            array[n] = obj;
        });
    };
    // ———— functions [END] ————
</script>