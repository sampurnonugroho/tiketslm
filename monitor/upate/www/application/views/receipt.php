	<html>
    <head>
        <style>
            @page { margin: 0px; margin-top: 5px; size: 50mm 20mm portrait; }
			
			p {
				font-size: 8px;
				margin-top: -10px;
				margin-left: -20px;
			}
			
			img {
				margin-top: -5px;
				margin-left: 20px;
			}
		</style>
	</head>

	<body>
		<?php 
			$content = '';
			
			$num = count($data);
			$i = 0;
			foreach($data as $r) {
				$i++;
				$content .= '
					<div style="float: left">
						<img style="padding-top: 2px; padding-left: 2px" src="'.realpath(__DIR__ . '/../../upload/qrcode_receipt').'/'.$r->kode.'.png" width="70" height="70"></img>
					</div>
					<div style="float: left"">
						<p  style="padding-left: 30px; padding-top: 20px">'.$r->kode.'</p>
					</div>
				';
				
				if($i!=$num) {
					$content .= '<div style="page-break-after: always;"></div>';
				}
			}
			
			echo $content;
		?>
	</body>
</html>