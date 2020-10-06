@extends('layouts.master')

@section('content')
	<?php 
		// print_r($data_flm);
	?>
			<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<link href="<?=base_url()?>assets/lightbox/dist/ekko-lightbox.css" rel="stylesheet" type="text/css">
			<section class="grid_12">
				<div class="block-border">
					<div class="block-content no-title dark-bg">
						<div id="control-bar">
							<div class="container_16">
								<center>
									<img src="<?=base_url()?>constellation/assets/images/bijak.png" width="50" height="50">
								</center>
								<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
									<br>[DATA <?=strtoupper(str_replace("_", " ", $active_menu))?>]
								</p>
							</div>
						</div>	
					</div>
				</div>
				<div class="widget_wrap preview_table">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
					</div>
					<div class="widget_content">
						<div style="padding: 20px">
						<table class="table">
							<tr>
								<th style="text-align: center">DOCUMENT BOC INTERNAL</th>
								<th style="text-align: center">DOCUMENT BOC EXTERNAL</th>
							</tr>
							<tr>
								<td style="text-align: center">
									<a href="<?=$data_foto->document_1?>" data-toggle="lightbox">
										<img width="200px" height="280" src="<?=$data_foto->document_1?>" class="img-fluid">
									</a>
								</td>
								<td style="text-align: center">
									<a href="<?=$data_foto->document_2?>" data-toggle="lightbox">
										<img width="200px" height="280" src="<?=$data_foto->document_2?>" class="img-fluid">
									</a>
								</td>
							</tr>
						</table>
						
					</div>
				</div>
			</section>
			
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="<?=base_url()?>assets/lightbox/dist/ekko-lightbox.js"></script>

        <!-- for documentation only -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/anchor-js/3.2.1/anchor.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function ($) {
                // delegate calls to data-toggle="lightbox"
                $(document).on('click', '[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', function(event) {
                    event.preventDefault();
                    return $(this).ekkoLightbox({
                        onShown: function() {
                            if (window.console) {
                                return console.log('Checking our the events huh?');
                            }
                        },
						onNavigate: function(direction, itemIndex) {
                            if (window.console) {
                                return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                            }
						}
                    });
                });

                //Programmatically call
                $('#open-image').click(function (e) {
                    e.preventDefault();
                    $(this).ekkoLightbox();
                });
                $('#open-youtube').click(function (e) {
                    e.preventDefault();
                    $(this).ekkoLightbox();
                });

				// navigateTo
                $(document).on('click', '[data-toggle="lightbox"][data-gallery="navigateTo"]', function(event) {
                    event.preventDefault();

                    return $(this).ekkoLightbox({
                        onShown: function() {

							this.modal().on('click', '.modal-footer a', function(e) {

								e.preventDefault();
								this.navigateTo(2);

                            }.bind(this));

                        }
                    });
                });


                /**
                 * Documentation specific - ignore this
                 */
                anchors.options.placement = 'left';
                anchors.add('h3');
                $('code[data-code]').each(function() {

                    var $code = $(this),
                        $pair = $('div[data-code="'+$code.data('code')+'"]');

                    $code.hide();
                    var text = $code.text($pair.html()).html().trim().split("\n");
                    var indentLength = text[text.length - 1].match(/^\s+/)
                    indentLength = indentLength ? indentLength[0].length : 24;
                    var indent = '';
                    for(var i = 0; i < indentLength; i++)
                        indent += ' ';
                    if($code.data('trim') == 'all') {
                        for (var i = 0; i < text.length; i++)
                            text[i] = text[i].trim();
                    } else  {
                        for (var i = 0; i < text.length; i++)
                            text[i] = text[i].replace(indent, '    ').replace('    ', '');
                    }
                    text = text.join("\n");
                    $code.html(text).show();

                });
            });
        </script>
@endsection