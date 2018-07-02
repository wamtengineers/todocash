<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$purchase=dofetch(doquery("select * from purchase where id='".slash($_GET["id"])."'", $dblink));
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invoice</title>
<style> 
strong {
    display: inline-block;
    text-transform: uppercase;
}
.items{ border-collapse: collapse;
    margin: 10px 0;
    padding: 10px;}

.items td, .items th {
    border: 1px solid #ccc;
    padding: 5px;
}
.items th {
    background-color: #eee;
    font-weight: normal;
    text-align: left;
}
</style>
</head>
<body>
    <table width="800" cellpadding="" cellspacing="" border="0" align="center" style="padding: 10px; border: 1px solid rgb(204, 204, 204);">
        <tr>
            <td>
                <table width="800" cellpadding="" cellspacing="" align="center">
        			<tr>
                		<td colspan="2" align="center"><h2 style="margin: 0px 0px 10px; padding: 10px; background-color: rgb(238, 238, 238);">INVOICE</h2></td>
        			</tr>
        			<tr>
                		<td colspan="2">Supplier Name: <strong><?php echo unslash($purchase["supplier_name"])?></strong></td>
        			</tr>
                    <tr>
                       	<td style="width:50%">Invoice: <strong><?php echo unslash($purchase["id"])?></strong></td>
                     	<td><strong><?php echo $site_title?></strong></td>
                    </tr>
        			<tr>
                		<td>Invoice Date: <strong><?php echo date_convert($purchase["date"])?></strong></td>
                		<td>Time: <strong><?php echo date("h:i:s A", strtotime($purchase["ts"]))?></strong></td>
        			</tr>
            	</table>
            </td>
     	</tr>
 		<tr>
        	<td>
                <table cellpadding="0" cellspacing="0" align="center" width="800" border="0" class="items">
                    <tr>
                        <th width="5%">Sn.</th>
                        <th width="30%">Particulars</th>
                        <th width="10%">Quantity</th>
                        <th width="20%">Rate</th>
                        <th width="20%">Amount</th>
                    </tr>
					<?php
                    $items=doquery("select a.*, b.title from purchase_items a left join items b on a.item_id=b.id where purchase_id='".$purchase["id"]."' order by a.id", $dblink);
                    if(numrows($items)>0){
                        $sn=1;
                        while($item=dofetch($items)){
                            ?>
                            <tr>
                                <td><?php echo $sn?></td>
                                <td><?php echo unslash($item["title"])?></td>
                                <td align="right"><?php echo $item["quantity"]?></td>
                                <td align="right"><?php echo curr_format($item["unit_price"])?></td>
                                <td align="right"><?php echo curr_format($item["total_price"])?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
           		</table>
          	</td>
   		</tr>
        <tr>
            <td>
                <table width="800" cellpadding="" cellspacing="" align="center">
                    <tr>
                        <td width="50%">Total No. of item: <strong><?php echo curr_format($purchase["total_items"])?></strong></td>
                        <td>Grand total: <strong><?php echo curr_format($purchase["total_price"])?></strong></td>
            		</tr>
        			<tr>
                        <td colspan="2">Net Payable Amount: <strong><?php echo curr_format($purchase["total_price"])?></strong></td>
                    </tr>
        			<tr>
            			<td colspan="2">Amount in Words: <strong>Rupees <?php echo convert_number_to_words(round($purchase["total_price"]));?></strong></td>
        			</tr>
        		</table>
        	</td>
        </tr>
    </table>
</body>
</html>
<?php
}
die();