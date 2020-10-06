	<html>
    <head>
        <style>
            @page { 
				margin: 0px; 
				size: 50mm 20mm portrait; 
			}
			
			p {
				font-size: 8px;
				margin-top: -10px;
				margin-left: -20px;
			}
			
			img {
				margin-top: -4px;
				margin-left: 20px;
			}
			
			#content_qrcode, #content_qrcode2, #content_qrcode3 {
				margin-top: 8px
			}
		</style>
	</head>

	<body>
		<?php 
			$content = '';
			
			
			$num = 1;
			for($i=1; $i<=1; $i++) {
				$kode = $wsid.".CST.".sprintf('%02d', $i);
				$content .= '
					<div id="content_qrcode" style="float: left">
						<img style="padding-top: 2px; padding-left: 2px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$wsid.'.png" width="62" height="62"></img>
					</div>
					<div style="float: left"">
						<p  style="padding-left: 30px; padding-top: 20px">ID ATM : '.$wsid.'</p>
					</div>
				';
				
				if($i!=$num) {
					$content .= '<div style="page-break-after: always;"></div>';
				}
			}
			
			$content .= '<div style="page-break-after: always;"></div>';
			
			$num = $cassette;
			for($i=1; $i<=$cassette; $i++) {
				$kode = $wsid.".CST.".sprintf('%02d', $i);
				$content .= '
					<div id="content_qrcode2" style="float: left">
						<img style="padding-top: 2px; padding-left: 2px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png" width="62" height="62"></img>
					</div>
					<div style="float: left"">
						<p  style="padding-left: 30px; padding-top: 20px">CASSETTE-'.$i.' <br>'.$kode.'</p>
					</div>
				';
				
				if($i!=$num) {
					$content .= '<div style="page-break-after: always;"></div>';
				}
			}
			
			$content .= '<div style="page-break-after: always;"></div>';
			
			$num = $divert;
			for($i=1; $i<=$divert; $i++) {
				$kode = $wsid.".DIV.".sprintf('%02d', $i);
				$content .= '
					<div id="content_qrcode3" style="float: left">
						<img style="padding-top: 2px; padding-left: 2px" src="'.realpath(__DIR__ . '/../../upload/qrcode_bast').'/'.$kode.'.png" width="62" height="62"></img>
					</div>
					<div style="float: left"">
						<p  style="padding-left: 30px; padding-top: 20px">DIVERT-'.$i.' <br>'.$kode.'</p>
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