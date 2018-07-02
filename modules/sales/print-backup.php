<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$sale=dofetch(doquery("select * from sales where id='".slash($_GET["id"])."'", $dblink));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice</title>
<style>
.clearfix:after {
	content: "";
	display: table;
	clear: both;
}
#main {
width:65mm;
border:0;
}
a {
	color: #5D6975;
	text-decoration: underline;
}
body {
	position: relative;
	margin: 0;
	color: #000;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	padding: 0px
}
p{margin:0 0 5px 0}
#logo {
	text-align: center;
	margin-bottom: 10px;
}
#right_title {
	font-size: 18px;
	font-style: italic;
	font-weight: bolder;
	float: right;
	margin-right: 5px;
	text-decoration: underline;
}
#center_title {
	font-size: 22px;
	font-style: normal;
	font-weight: bold;
	float: right;
	padding-top: 45px;
	text-transform: uppercase
}
#inv_status {
	margin-bottom: 30px;
	font-size: 14px;
}
#inv_status_alrt {
	font-size: 16px;
	font-weight: bold;
	text-align: center;
	border: thin solid #666;
	float: right;
	margin-right: 5px;
	position: relative;
	padding-top: 5px;
	padding-right: 30px;
	padding-bottom: 5px;
	padding-left: 30px;
}
#project {
	float: left;
	font-size: 14px;
}
#project div {
	margin-bottom: 5px;
}
#customer {
	float: right;
	text-align: center;
	line-height: 1em;
}
#jbnum {
	width: 200px;
	padding: 5px;
	line-height: 1em;
	margin-bottom: 5px;
	background-color: #444;
	color: #fff;
}
#customer span {
	color: #000000;
	text-align: left;
	width: 52px;
	margin-right: 10px;
	display: inline-block;
	font-size: 13px;
}
#company {
	float: right;
	text-align: right;
}
table {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0;
	margin-bottom: 20px;
}
table tr:nth-child(2n-1) td {
	background: #F5F5F5;
}
table th, table td {
	text-align: left;
}
table th {
    border: 1px solid #fff;
    color: #fff;
    font-weight: bold;
    line-height: 0.9em;
    padding: 10px 0;
    text-align: center;
    white-space: nowrap;
}
.data-table td{border:1px solid #afafaf;}
.data-table td strong{text-align:right;display:block}
#th_center {
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #666666;
}
#cinfo_table {
	height: auto;
	width: 49%;
	float: left;
}
#cinfo_table_cntr {
	height: auto;
	width: 260px;
	margin-left: 266px;
}
#cinfo_table_rgt {
	height: auto;
	width: 49%;
	float: right;
}
#inchk_table {
	float: left;
	width: 393px;
}
#inchk_table td {
	border: thin solid #CCCCCC;
	padding-top: 1px;
	padding-bottom: 1px;
	line-height: 1.5em;
}
#othrd_table {
	float: right;
	width: 393px;
}
#othrd_table td {
	border: thin solid #CCC;
	padding-top: 1px;
	padding-bottom: 1px;
	line-height: 1.5em;
}
.tableamount {
	text-align: right;
}
#acc {
	border: thin solid #000;
	padding-right: 15px;
	display: block;
	line-height: 20px;
}
#rbr {
	border-right-width: thin;
	border-right-style: solid;
	border-right-color: #000;
	background-color: #ccc;
	width: 100px;
	white-space: nowrap;
	float: left;
	padding-left: 10px;
}
#acc span {
	margin-left: 15px;
}
table .service, table .desc {
	text-align: left;
}
table td {
	text-align: right;
	padding-top: 10px;
	padding-right: 5px;
	padding-bottom: 10px;
	padding-left: 5px;
	line-height: .01em;
}
table td.service, table td.desc {
	vertical-align: top;
}
table td.unit, table td.qty, table td.total {
	font-size: 1.2em;
}
table td.grand {
	border-top: 1px solid #5D6975;
	;
}
#notices {
	margin-top: 20px;
	float: left;
	clear: both;
	width: 100%;
}
#signcompny {
	text-align: center;
	border-top-width: thin;
	border-top-style: solid;
	border-top-color: #000;
	margin: 50px 0;
}
#signcus {
	text-align: center;
	border-top-width: thin;
	border-top-style: solid;
	border-top-color: #000;
	margin-right: 5px;
	margin-top: 100px;
}
footer {
	color: #5D6975;
	width: 100%;
	height: 30px;
	position: absolute;
	bottom: 0;
	border-top: 1px solid #C1CED9;
	padding: 8px 0;
	text-align: center;
}
.comnme {
	font-size: 22px;
	font-weight: bold;
}
.contentbox{display:block}

</style>
		<script>
		function print_page(){
			printer = '<?php echo get_config( 'thermal_printer_title' );?>';
			printers = jsPrintSetup.getPrintersList().split(",");
			if( printers.indexOf( printer ) !== -1 ) {
				jsPrintSetup.setPrinter( printer );
				jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
				// set top margins in millimeters
				jsPrintSetup.setOption('marginTop', 0);
				jsPrintSetup.setOption('marginBottom', 0);
				jsPrintSetup.setOption('marginLeft', 0);
				jsPrintSetup.setOption('marginRight', 0);
				// set page header
				jsPrintSetup.setOption('headerStrLeft', '');
				jsPrintSetup.setOption('headerStrCenter', '');
				jsPrintSetup.setOption('headerStrRight', '');
				// set empty page footer
				jsPrintSetup.setOption('footerStrLeft', '');
				jsPrintSetup.setOption('footerStrCenter', '');
				jsPrintSetup.setOption('footerStrRight', '');
				// Suppress print dialog
				jsPrintSetup.setSilentPrint(true);
				// Do Print
				//jsPrintSetup.print();
				// Restore print dialog
				//jsPrintSetup.setSilentPrint(false);
			}
			else {
				alert( printer + " is not installed." );
			}
			
		}
        </script>
</head>
<body onload="print_page();">
<div id="main">
    <div id="logo"> <img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" style="width:80%;" /></div>
    <div class="contentbox">
        <p>Customer Name: <strong style="float:right"><?php echo unslash($sale["customer_name"]); ?></strong></p>
        <p>Phone: <strong style="float:right">0900 786 01</strong></p>
        <table cellpadding="0" cellspacing="0" align="center" width="800" border="0" class="items">
            <tr>
                <th width="20%"><img src="images/amount.png"></th>
                <th width="20%"><img src="images/rate.png"></th>
                <th width="10%"><img src="images/qty.png"></th>
                <th width="30%"><img src="images/particular.png"></th>
                <th width="5%"><img src="images/sn.png"></th>
            </tr>
            <?php
            $items=doquery("select a.*, b.name_in_urdu from sales_items a left join items b on a.item_id=b.id where sales_id='".$sale["id"]."' order by a.id", $dblink);
            if(numrows($items)>0){
                $sn=1;
                while($item=dofetch($items)){
                    ?>
                    <tr>
                        <td align="right"><?php echo curr_format($item["total_price"])?></td>
                        <td align="right"><?php echo curr_format($item["unit_price"])?></td>
                        <td align="right"><?php echo $item["quantity"]?></td>
                        <td><a href="<?php echo $file_upload_root?>item/<?php echo unslash($item["name_in_urdu"]); ?>" target="_blank"><img src="<?php echo $file_upload_root?>item/<?php echo unslash($item["name_in_urdu"]); ?>"  alt="image" title="" style="height:50px;" /></a></td>
                        <td><?php echo $sn++?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <hr style="border:0; border-top:1px solid #999">
        <p><strong>TOTAL</strong><strong style="float:right"><?php echo curr_format($sale["total_price"])?></strong></p>
        <p><strong>Discount</strong><strong style="float:right"><?php echo curr_format($sale["total_price"])?></strong></p>
        <p><strong>TOTAL</strong><strong style="float:right"><?php echo curr_format($sale["total_price"])?></strong></p>
    </div>
    <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div> 
</div>
</body>
</html>
<?php
die;
}