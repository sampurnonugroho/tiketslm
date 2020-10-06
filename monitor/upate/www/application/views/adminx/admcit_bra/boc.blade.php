<html>
    <head>
        <style>
            
            @page { margin: 0px; size: 21cm 29.5cm portrait; }
        
            @font-face {
                font-family: "aaaaa";
                src: URL("'.base_url().'depend/font/serif_dot_digital-7.ttf") format("truetype");
            }
        
            body {
                margin: 27px 30px 0px 30px; size: 14cm 22cm landscape;
                font-family: Calibri;            
            }
        
            table.first {
                font-family: Calibri;            
                font-size: 8pt;
                width: 100%;
            }
            
            #h3 {
                font-family: Calibri; 
                font-size: 12pt;
            }
            
            table.first td {
                line-height: 1px;
            }	
            
            table.second {
                width: 100%;
            }
            
            table.second td {
                line-height: 12px;
            }
            
            .third {
                font-family: Calibri;       
                font-size: 8pt;
                border: 1px solid black;
                border-collapse: collapse;
                position: absolute;
                top: 30;
                right: 260;
                border-style: solid;
            }
            
            .fourth {
                font-family: Calibri;       
                font-size: 8pt;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
            }
            
            .fifth {
                font-family: Calibri;       
                font-size: 8pt;
                border-collapse: collapse;
                border: 1px solid black;
                border-style: solid;
            }
            
            .sixth {
                font-family: Calibri;       
                border: none;
                border-collapse: collapse;
            }
            
            .sixth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
            }
            
            table.fourth td {
                padding: 4px;
                border: 1px solid black;
                border-style: solid;
            }
            
            #noborder {
                border: none;
            }
            #noborderbottom {
                border-bottom: 0px solid white;
            }
            #nobordertop {
                border-top: 0px solid white;
            }
            .noborder {
                border: 0px solid white;
            }
            .noborderbottom {
                border-bottom: 0px solid white;
            }
            .nobordertop {
                border-top: 0px solid white;
            }
            .noborderright {
                border-right: 0px solid white;
            }
            .noborderleft {
                border-left: 0px solid white;
            }
            
            .alignleft {
                float: left;
            }
            .alignright {
                float: right;
            }
        </style>
    </head>

    <body>
        $content_html
    </body>
</html>