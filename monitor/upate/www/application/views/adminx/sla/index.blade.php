@extends('layouts.master')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <style>
				* {
					box-sizing: border-box;
				}

				html {
					font-family: helvetica;
				}

				html,
				body {
					max-width: 100vw;
					
				}

				table {
					margin: auto;
					border-collapse: collapse;
					overflow-x: auto;
					display: block;
					width: fit-content;
					max-width: 100%;
					box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
					
				}

				td,
				th {
					border: solid rgb(200, 200, 200) 1px;
					padding: .5rem;
					font-size: 12px;
					
				}

				th {
					text-align: left;
					background-color: rgb(190, 220, 250);
					text-transform: uppercase;
					border: rgb(50, 50, 100) solid 1px;
					border-top: none;
					text-align: center;
				}

				td {
					white-space: nowrap;
					border-bottom: none;
					color: rgb(20, 20, 20);
					border: rgb(50, 50, 100) solid 1px;
				}

				td:first-of-type,
				th:first-of-type {
					border-left: none;
				}

				td:last-of-type,
				th:last-of-type {
					border-right: none;
				}
				
				table tfoot td {
					border: rgb(50, 50, 100) solid 2px;
				}
			</style>
        </head>

        <body class='default'>

            <div class="grid_14">
                <div class="widget_wrap">
                    <div class="widget_top">
                        <span class="h_icon list_images"></span>
                        <h6>LAPORAN SERVICE LEVEL AGREEMENT (FLM)</h6>
                    </div>
                    <div class="widget_content">
                        <div id="jqxgrid"></div>
                    </div>
                </div>
            </div>
            <div class="grid_14">
                <div class="block-border">
                    <!-- <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle" rowspan="4">NO</th>
                                    <th style="vertical-align: middle" rowspan="4">ATM ID</th>
                                    <th style="vertical-align: middle" rowspan="4">Lokasi</th>
                                    <th style="vertical-align: middle" rowspan="4">ATM/CRM</th>
                                    <th style="vertical-align: middle" colspan="28">REKONSILIASI</th>
                                    <th style="vertical-align: middle" rowspan="4">Counter (khusus CRM) (x1000)</th>
                                    <th style="vertical-align: middle" rowspan="2">Selisih</th>
                                    <th style="vertical-align: middle" rowspan="4">Time</th>
                                    <th style="vertical-align: middle" colspan="4">PENGISIAN BERIKUTNYA</th>
                                    <th style="vertical-align: middle" rowspan="4" width="10%">KET <br>NAIK/TURUN LIMIT</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle" colspan="6">PENGISIAN SEBELUMNYA</th>
                                    <th style="vertical-align: middle" colspan="11">PERHITUNGAN FISIK UANG</th>
                                    <th style="vertical-align: middle" colspan="11">PERHITUNGAN DISPENSED COUNTER</th>
                                    <th style="vertical-align: middle" rowspan="3">Jml Csst</th>
                                    <th style="vertical-align: middle" rowspan="2" colspan="2">Jml Isi</th>
                                    <th style="vertical-align: middle" rowspan="3">Total</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle"rowspan="2">TANGGAL</th>
                                    <th style="vertical-align: middle"rowspan="2">JML CSST</th>
                                    <th style="vertical-align: middle"width="200px" colspan="4">JML ISI</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 1</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 2</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 3</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 4</th>
                                    <th style="vertical-align: middle"colspan="2">RJT.</th>
                                    <th style="vertical-align: middle">TOTAL RP</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 1</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 2</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 3</th>
                                    <th style="vertical-align: middle"colspan="2">CSST 4</th>
                                    <th style="vertical-align: middle"colspan="2">RJT.</th>
                                    <th style="vertical-align: middle">TOTAL RP</th>
                                    <th style="vertical-align: middle">TOTAL RP</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle" colspan="2">50</th>
                                    <th style="vertical-align: middle" colspan="2">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">(x1,000)</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                    <th style="vertical-align: middle">(x1,000)</th>
                                    <th style="vertical-align: middle">(x1,000)</th>
                                    <th style="vertical-align: middle">50</th>
                                    <th style="vertical-align: middle">100</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                    </div> -->
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Lokasi</th>
                                    <th>No. Ticket</th>
                                    <th>No. Job Card</th>
                                    <th>Problem</th>
                                    <th>Action Taken</th>
                                    <th>Entry Date</th>
                                    <th>Call In / Email Time</th>
                                    <th>Arrival Date</th>
                                    <th>Estimate Tima Arrival / Appointment</th>
                                    <th>Arrive Time</th>
                                    <th>Start Date</th>
                                    <th>Start Time</th>
                                    <th>Close Date</th>
                                    <th>Close Time</th>
                                    <th style="background-color: #ebebeb">Response Time</th>
                                    <th style="background-color: #ebebeb">Minute</th>
                                    <th style="background-color: #ebebeb">Repair Time</th>
                                    <th style="background-color: #ebebeb">Minute</th>
                                    <th style="background-color: #ebebeb">Resolution Time</th>
                                    <th style="background-color: #ebebeb">Minute</th>
                                    <th style="background-color: #ebebeb">DT (%)</th>
                                    <th style="background-color: #ebebeb">Up Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 0;
                                    foreach($data_sla as $r) {
                                        $no++;
                                        echo "<tr>";
                                        echo "<td>$no.</td>";
                                        echo "<td>".$r['wsid']."</td>";
                                        echo "<td>".$r['lokasi']."</td>";
                                        echo "<td>".$r['ticket']."</td>";
                                        echo "<td></td>";
                                        echo "<td>".$r['problem_type']."</td>";
                                        echo "<td></td>";
                                        echo "<td>".$r['entry_date']."</td>";
                                        echo "<td>".$r['email_time']."</td>";
                                        echo "<td>".$r['arrival_date']."</td>";
                                        echo "<td></td>";
                                        echo "<td>".$r['arrival_time']."</td>";
                                        echo "<td>".$r['start_date']."</td>";
                                        echo "<td>".$r['start_time']."</td>";
                                        echo "<td>".$r['close_date']."</td>";
                                        echo "<td>".$r['close_time']."</td>";
                                        echo "<td>".$r['response_time']."</td>";
                                        echo "<td>".$r['minute_1']."</td>";
                                        echo "<td>".$r['repair_time']."</td>";
                                        echo "<td>".$r['minute_2']."</td>";
                                        echo "<td>".$r['resolution_time']."</td>";
                                        echo "<td>".$r['minute_3']."</td>";
                                        echo "<td>".$r['dt']."</td>";
                                        echo "<td>".$r['uptime']."</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <br>

            </div>
        </body>

    </html>

@endsection