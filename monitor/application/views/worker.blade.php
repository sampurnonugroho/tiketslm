<!DOCTYPE html>
<html>
	<head>
		<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	</head>
	<body>
	
		<p>Count numbers: <output id="result"></output></p>
		<button onclick="delete_sync(), proses()">Start Sync</button> 
		<button onclick="delete_sync()">Delete Sync</button> 

		<p><strong>Note:</strong> Internet Explorer 9 and earlier versions do not support Web Workers.</p>

		<script>
			var total = 0;
		
			function proses(page=1) {
				$.ajax({
					url: 'http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/index2?page='+page,
					dataType: 'html',
					type: 'GET',
					data: {},
					success: function(data) {
						res = JSON.parse(data);
						console.log(res);
						
						if(total==0) {
							total = res.total;
						}
						console.log(total);
						if(res.total!==res.page && res.page!=="done") {
							setTimeout("proses('"+page+"')",10);
						}
						percen = (((res.total*-1)+total)/total)*100;
						// document.getElementById("result").innerHTML = percen.toFixed(2)+"%";
						percen = isNaN(percen) ? 100 : percen.toFixed(2);
						document.getElementById("result").innerHTML = percen+"%";
					}
				});
			}
			
			function delete_sync() {
				$.ajax({
					url: 'http://pt-bijak.co.id/rest_api_dev_minggu/server/api/table/delete_sync',
					dataType: 'html',
					type: 'GET',
					data: {},
					success: function(data) {
						total = 0;
						console.clear();
						console.log("DONE");
					}
				});
			}
		</script>

	</body>
</html>