<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>B.I.M.A V2.0</title>
    <link href="<?=base_url()?>depend/css/reset.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/layout.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/typography.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/styles.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/shCore.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/jquery.jqplot.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/data-table.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/form.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/ui-elements.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/wizard.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/sprite.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/gradient.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/constellation/assets/js/ContextMenu.js"></script>
	
</head>

<body id="theme-default" class="full_block">
    <div id="container">
        <div id="content">
            <div class="grid_container">
                <div class="grid_12">
                    <div class="">
						<?php if ($session->flashdata('success')): ?>
							<section class="grid_12">
								<ul class="alert message success" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px; color: white" hidden>
									<li><?=$session->flashdata('success')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php elseif ($session->flashdata('error')): ?>
							<section class="grid_12">
								<ul class="alert message error" style="z-index: 99999; position: fixed; top: 1%; right: 1%; width: 200px; color: white" hidden>
									<li><?=$session->flashdata('error')?></li>
									<li class="close-bt"></li>
								</ul>
							</section>
						<?php endif; ?>
						
						<?php //echo $level; ?>
						
						<?php echo $__env->yieldContent('content'); ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.ui.touch-punch.js"></script>
<script src="<?=base_url()?>depend/js/chosen.jquery.js"></script>
<script src="<?=base_url()?>depend/js/uniform.jquery.js"></script>
<script src="<?=base_url()?>depend/js/bootstrap-dropdown.js"></script>
<script src="<?=base_url()?>depend/js/bootstrap-colorpicker.js"></script>
<script src="<?=base_url()?>depend/js/sticky.full.js"></script>
<script src="<?=base_url()?>depend/js/jquery.noty.js"></script>
<script src="<?=base_url()?>depend/js/selectToUISlider.jQuery.js"></script>
<script src="<?=base_url()?>depend/js/fg.menu.js"></script>
<script src="<?=base_url()?>depend/js/jquery.tagsinput.js"></script>
<script src="<?=base_url()?>depend/js/jquery.cleditor.js"></script>
<script src="<?=base_url()?>depend/js/jquery.tipsy.js"></script>
<script src="<?=base_url()?>depend/js/jquery.peity.js"></script>
<script src="<?=base_url()?>depend/js/jquery.simplemodal.js"></script>
<script src="<?=base_url()?>depend/js/jquery.jBreadCrumb.1.1.js"></script>
<script src="<?=base_url()?>depend/js/jquery.colorbox-min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.idTabs.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.multiFieldExtender.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.confirm.js"></script>
<script src="<?=base_url()?>depend/js/elfinder.min.js"></script>
<script src="<?=base_url()?>depend/js/accordion.jquery.js"></script>
<script src="<?=base_url()?>depend/js/autogrow.jquery.js"></script>
<script src="<?=base_url()?>depend/js/check-all.jquery.js"></script>
<script src="<?=base_url()?>depend/js/data-table.jquery.js"></script>
<script src="<?=base_url()?>depend/js/ZeroClipboard.js"></script>
<script src="<?=base_url()?>depend/js/TableTools.min.js"></script>
<script src="<?=base_url()?>depend/js/jeditable.jquery.js"></script>
<script src="<?=base_url()?>depend/js/ColVis.min.js"></script>
<script src="<?=base_url()?>depend/js/duallist.jquery.js"></script>
<!--<script src="<?=base_url()?>depend/js/easing.jquery.js"></script>-->
<script src="<?=base_url()?>depend/js/full-calendar.jquery.js"></script>
<script src="<?=base_url()?>depend/js/input-limiter.jquery.js"></script>
<script src="<?=base_url()?>depend/js/inputmask.jquery.js"></script>
<script src="<?=base_url()?>depend/js/iphone-style-checkbox.jquery.js"></script>
<script src="<?=base_url()?>depend/js/meta-data.jquery.js"></script>
<script src="<?=base_url()?>depend/js/quicksand.jquery.js"></script>
<script src="<?=base_url()?>depend/js/raty.jquery.js"></script>
<script src="<?=base_url()?>depend/js/smart-wizard.jquery.js"></script>
<script src="<?=base_url()?>depend/js/stepy.jquery.js"></script>
<script src="<?=base_url()?>depend/js/treeview.jquery.js"></script>
<script src="<?=base_url()?>depend/js/ui-accordion.jquery.js"></script>
<script src="<?=base_url()?>depend/js/vaidation.jquery.js"></script>
<script src="<?=base_url()?>depend/js/mosaic.1.0.1.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.collapse.js"></script>
<script src="<?=base_url()?>depend/js/jquery.cookie.js"></script>
<script src="<?=base_url()?>depend/js/jquery.autocomplete.min.js"></script>
<script src="<?=base_url()?>depend/js/localdata.js"></script>
<script src="<?=base_url()?>depend/js/excanvas.min.js"></script>
<script src="<?=base_url()?>depend/js/jquery.jqplot.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.dateAxisRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.cursor.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.logAxisRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.canvasTextRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.highlighter.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.pieRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.barRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.pointLabels.min.js"></script>
<script src="<?=base_url()?>depend/js/chart-plugins/jqplot.meterGaugeRenderer.min.js"></script>

<script src="<?=base_url()?>depend/js/custom-scripts.js"></script>

<script src="<?=base_url()?>assets/constellation/assets/js/modal.js"></script>

<script>
	$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
	
	$(document).ready(function() {
		$(".alert").show(1000);
		window.setTimeout(function()  {
			$(".alert").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 5000);
		
		$(document).on("click", ".close-bt", function() {
			$(".alert").remove();
		});
		
		var monthNames = ["Januari", "Febriari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

		// Create a newDate() object
		var newDate = new Date();
		// Extract the current date from Date object
		newDate.setDate(newDate.getDate());
		// Output the day, date, month and year    
		$('#Date').html(newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

		setInterval(function() {
			// Create a newDate() object and extract the seconds of the current time on the visitor's
			var seconds = new Date().getSeconds();
			// Add a leading zero to seconds value
			$("#sec").html((seconds < 10 ? "0" : "") + seconds);
		}, 1000);

		setInterval(function() {
			// Create a newDate() object and extract the minutes of the current time on the visitor's
			var minutes = new Date().getMinutes();
			// Add a leading zero to the minutes value
			$("#min").html((minutes < 10 ? "0" : "") + minutes);
		}, 1000);

		setInterval(function() {
			// Create a newDate() object and extract the hours of the current time on the visitor's
			var hours = new Date().getHours();
			// Add a leading zero to the hours value
			$("#hours").html((hours < 10 ? "0" : "") + hours);
		}, 1000);
	});

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
							// console.log(data);
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
 
<script>
/*=================
CHART 2
===================*/
$(function () {
	/*========================================================
	Data Point Highlighting, Tooltips and Cursor Tracking
	==========================================================*/
	var line1 = [
		['23-May-08', 300.55],
		['20-Jun-08', 566.5],
		['25-Jul-08', 300.88],
		['22-Aug-08', 509.84],
		['26-Sep-08', 300.13],
		['24-Oct-08', 600.75],
		['21-Nov-08', 303],
		['26-Dec-08', 308.56],
		['23-Jan-09', 660.14],
		['20-Feb-09', 346.51],
		['20-Mar-09', 560.99],
		['24-Apr-09', 386.15]
	];
	var plot1 = $.jqplot('chart2', [line1], {
		title: 'Data Monitoring',
		axes: {
			xaxis: {
				renderer: $.jqplot.DateAxisRenderer,
				tickOptions: {
					formatString: '%b&nbsp;%#d'
				}
			},
			yaxis: {
				tickOptions: {
					formatString: '$%.2f'
				}
			}
		},
		highlighter: {
			show: true,
			sizeAdjust: 7.5
		},
		cursor: {
			show: false
		},
		grid: {
			borderColor: '#ccc', // CSS color spec for border around grid.
			borderWidth: 2.0, // pixel width of border around grid.
			shadow: false // draw a shadow for grid.
		},
		seriesDefaults: {
			lineWidth: 2, // Width of the line in pixels.
			shadow: false, // show shadow or not.
			markerOptions: {
				show: true, // wether to show data point markers.
				style: 'filledCircle', // circle, diamond, square, filledCircle.
				// filledDiamond or filledSquare.
				lineWidth: 2, // width of the stroke drawing the marker.
				size: 14, // size (diameter, edge length, etc.) of the marker.
				color: '#ff8a00', // color of marker, set to color of line by default.
				shadow: true, // wether to draw shadow on marker or not.
				shadowAngle: 45, // angle of the shadow.  Clockwise from x axis.
				shadowOffset: 1, // offset from the line of the shadow,
				shadowDepth: 3, // Number of strokes to make when drawing shadow.  Each stroke
				// offset by shadowOffset from the last.
				shadowAlpha: 0.07 // Opacity of the shadow
			}
		}
	});
});

$(function() {
    plot2 = jQuery.jqplot('chart5',
        [
            [
                ['Verwerkende industrie', 9],
                ['Retail', 0],
                ['Primaire producent', 0],
                ['Out of home', 0],
                ['Groothandel', 0],
                ['Grondstof', 0],
                ['Consument', 3],
                ['Bewerkende industrie', 2]
            ]
        ], {
            title: ' ',
            seriesDefaults: {
                shadow: false,
                renderer: jQuery.jqplot.PieRenderer,
                rendererOptions: {
                    startAngle: 180,
                    sliceMargin: 4,
                    showDataLabels: true
                }
            },
            grid: {
                borderColor: '#ccc', // CSS color spec for border around grid.
                borderWidth: 2.0, // pixel width of border around grid.
                shadow: false // draw a shadow for grid.
            },
            legend: {
                show: false,
                location: 'w'
            }
        }
    );
});
</script>
	