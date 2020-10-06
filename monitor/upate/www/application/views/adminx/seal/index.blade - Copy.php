<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
<script src="<?=base_url()?>assets/jquery.scannerdetection.js"></script>
<script src="<?=base_url()?>assets/notify.min.js"></script>

<script>
	$(document).scannerDetection({
		timeBeforeScanTest: 1000, // wait for the next character for upto 200ms
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
		preventDefault: true,
		endChar: [13],
		onComplete: function(barcode, qty) {
			
		}, 
		onError: function(string, qty) {
			
		}
	});
</script>