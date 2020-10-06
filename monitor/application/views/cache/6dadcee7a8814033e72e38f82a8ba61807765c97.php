<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <title>B.I.M.A</title>
    <link href="<?=base_url()?>depend/css/reset.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/layout.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>depend/css/themes.css" rel="stylesheet" type="text/css">
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
	<link href="<?=base_url()?>assets/constellation/assets/css/mini3537.css?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,planning,table,calendars,wizard,gallery" rel="stylesheet" type="text/css">
	
	<link href="<?=base_url()?>depend/dist/style.css" rel="stylesheet" type="text/css">
	
	
	<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
    <!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
    <!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
    <!-- Jquery -->
    
	
	<style>
		/*	start styles for the ContextMenu	*/
        .context_menu {
            background-color: white;
            border: 1px solid gray;
        }

        .context_menu_item {
            padding: 3px 6px;
        }

        .context_menu_item:hover {
            background-color: #CCCCCC;
        }

        .context_menu_separator {
            background-color: gray;
            height: 1px;
            margin: 0;
            padding: 0;
        }
		
		.controls {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 40px;
			color: rgb(86, 86, 86);
			font-family: Roboto, Arial, sans-serif;
			-moz-user-select: none;
			font-size: 18px;
			background-color: rgb(255, 255, 255);
			padding: 0px 17px;
			border-bottom-right-radius: 2px;
			border-top-right-radius: 2px;
			background-clip: padding-box;
			box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;
			min-width: 64px;
			border-left: 0px none;
			outline: currentcolor none medium;
		}
		
		#searchInput {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 50%;
		}

		#searchInput:focus {
			border-color: #4d90fe;
		}

		ul#geoData {
			text-align: left;
			font-weight: bold;
			margin-top: 10px;
		}

		ul#geoData span {
			font-weight: normal;
		}
		
		.pac-container {
			z-index: 999990 !important;
		}
		
		.select2-container {
			z-index: 999999 !important;
		}
		
		.block-content .no-margin.last-child, .block-content .message.no-margin.last-child {
			margin-top: 25px !important;
		}
		
		.no-margin .block-controls:first-child {
			height: 34px !important;
		}
		
		.easyui-validatebox {
			margin: 0px;
			padding-top: 0px;
			padding-bottom: 0px;
			height: 28px;
			line-height: 28px;
			width: 152px;
			ime-mode: disabled;
		}
		
		#theme-default .acitem li a.active {
			background: #f9f9f9 !important;
			color: #333 !important;
		}
		
		.no-js #loader { display: none;  }
		.js #loader { display: block; position: absolute; left: 100px; top: 0; }
		.se-pre-con {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url(<?=base_url()?>depend/images/Preloader_8.gif) center no-repeat #fff;
		}
		
		.message.success li, div.message.success, p.message.success {
			color: white;
		}
		
		.jconfirm.jconfirm-white .jconfirm-bg, .jconfirm.jconfirm-light .jconfirm-bg {
			background-color: #444;
			opacity: 0.2;
			z-index: -1;
		}
	</style>
</head>

<body id="theme-default" class="full_block">
	<div class="se-pre-con"></div>
    <div id="actionsBox" class="actionsBox">
		<div id="actionsBoxMenu" class="menu">
			<span id="cntBoxMenu">0</span>
			<a class="button box_action">Archive</a>
			<a class="button box_action">Delete</a>
			<a id="toggleBoxMenu" class="open"></a>
			<a id="closeBoxMenu" class="button t_close">X</a>
		</div>
		<div class="submenu">
			<a class="first box_action">Move...</a>
			<a class="box_action">Mark as read</a>
			<a class="box_action">Mark as unread</a>
			<a class="last box_action">Spam</a>
		</div>
	</div>
	
    <div id="container">
        <div id="header" class="dark_d">
			<div class="header_center">
				<div class="logo">
					<img src="<?=base_url()?>assets/constellation/assets/images/bijak.png" width="50" height="50" alt="">
					
				</div>
				<p style="color:white;font-size:20px;"><b>PT. Bintang Jasa Artha Kelola</b><br>BIJAK INTEGRATED MONITORING APPLICATION</p>
			</div>
			
		</div>
		<li class="orange_lin">
		<p style="color:white;font-size:20px;"><marquee direction="left">BIJAK INTEGRATED MONITORING APPLICATION
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp [CAST IN TRANSIT, CASH REPLANISH, FLM & SLM] 
		</marquee></p>
		</li>
		<li class="blue_lin"></li>
        <div id="content">
            <div class="grid_container">
                <div class="grid_12">
                    <div class="widget_wrap">
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
				
						<?php echo $__env->yieldContent('content'); ?>
					</div>
                </div>
            </div>
        </div>
    
	</div>
	
	<li class="dark_d"></li>
	
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
<script src="<?=base_url()?>depend/js/easing.jquery.js"></script>
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

<script src="<?=base_url()?>depend/dist/jquery.min.js"></script>
<script src="<?=base_url()?>depend/dist/script.js"></script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>
<script type="text/javascript" src="<?=base_url()?>assets/constellation/assets/js/ContextMenu.js"></script>

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
	