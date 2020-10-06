<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>

<?php
	// echo "<pre>";
	// print_r($result);

	echo "<table>";
	echo "<tr>";
	echo "<th>ID TEKNISI</th>";
	echo "<th>NAMA</th>";
	echo "<th>LEVEL</th>";
	echo "<th>ACTION</th>";
	echo "</tr>";
	foreach($result as $r) {
		echo "<tr>";
		echo "<td>".$r->id_teknisi."</td>";
		echo "<td>".$r->nama."</th>";
		echo "<td>".$r->level."</td>";
		echo "<td>";
		echo "<button onClick=\"tes('".$r->nik."', '".base_url()."tracking/tes')\">GET LOCATION</button>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
?>

<script>
	console.log( "1nd loaded jQuery version ($): " + $.fn.jquery + "<br>" );
	
	function tes(id, url) {
		var txt;
		var r = confirm("Press a button! ("+id+")");
		if (r == true) {
			txt = "You pressed OK!";
			
			$.ajax({
				url: url,
				dataType: 'html',
				type: 'POST',
				data: {id:id},
				success: function(data) {
					alert(data);
				}
			});
		} else {
			txt = "You pressed Cancel!";
		}
		
		// alert(txt);
	}
	
	function openDelete(id, url)
	{
		$.modal({
			content: '<br>Anda yakin akan menghapus data dengan ID : ('+id+')?',
			title: 'Delete',
			maxWidth: 400,
			buttons: {
				'Yes': function(win) { 
					$.ajax({
						url: url,
						dataType: 'html',
						type: 'POST',
						data: {id:id},
						success: function(data) {
							if(data=="success") {
								window.location.reload();
							} else {
								win.closeModal();
							}
						}
					});
				},
				'Close': function(win) { win.closeModal(); }
			}
		});
	}
</script>