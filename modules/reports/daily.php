<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["start_date"])){
	$start_date=slash($_GET["start_date"]);
	$_SESSION["reports"]["daily"]["start_date"]=$start_date;
}

if(isset($_SESSION["reports"]["daily"]["start_date"]))
	$start_date=$_SESSION["reports"]["daily"]["start_date"];
else
	$start_date=date("d/m/Y");
if(isset($_GET["end_date"])){
	$end_date=slash($_GET["end_date"]);
	$_SESSION["reports"]["daily"]["end_date"]=$end_date;
}

if(isset($_SESSION["reports"]["daily"]["end_date"]))
	$end_date=$_SESSION["reports"]["daily"]["end_date"];
else
	$end_date=date("d/m/Y");
if($start_date != "" && $end_date != ""){
	$extra.=" and date BETWEEN '".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 12:00:00' AND '".date('Y-m-d',strtotime("+1 day", strtotime(date_dbconvert($end_date))))." 12:00:00'";
}

$order_by = "date";
$order = "asc";
$orderby = $order_by." ".$order;
?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Sales report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="sales_manage.php?tab=report"><i class="fa fa-print" aria-hidden="true"></i></a>  
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <span class="col-sm-1 text-to">Start</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="start_date" id="start_date" placeholder="" class="form-control date-picker"  value="<?php echo $start_date?>" >
                </div>
                <span class="col-sm-1 text-to">End</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="end_date" id="end_date" placeholder="" class="form-control date-picker"  value="<?php echo $end_date?>" >
                </div>
                
                <div class="col-sm-3 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
        	<?php
			$sql="select sum(total_items), sum(total_price), sum(discount), sum(net_price) from sales where 1 $extra order by $orderby";
			$total=dofetch(doquery($sql, $dblink));
			$sql="select sum(payment) from expense where 1 ".str_replace(" date ", " datetime_added ", $extra)." order by ".str_replace("date", "datetime_added", $orderby);
			$expense=dofetch(doquery($sql, $dblink));
			?>
        	<tr class="head">
                <th colspan="6" class="text-right">Total Items</th>
                <th class="text-right"><?php echo $total[ "sum(total_items)" ]?></th>
           	</tr>
            <tr class="head">
               	<th colspan="6" class="text-right">Total Price</th>
                <th class="text-right">Rs. <?php echo curr_format($total[ "sum(total_price)" ])?></th>
            </tr>
            <tr class="head">
            	<th colspan="6" class="text-right">Total Discount</th>
                <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(discount)" ])?></th>
            </tr>
            <tr class="head">
            	<th colspan="6" class="text-right">Net Total</th>
                <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(net_price)" ])?></th>
            </tr>
            <tr class="head">
            	<th colspan="6" class="text-right">Expense</th>
                <th class="text-right" >Rs. <?php echo curr_format($expense[ "sum(payment)" ])?></th>
            </tr>
            <tr class="head">
            	<th colspan="6" class="text-right">Total Cash</th>
                <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(net_price)" ]-$expense[ "sum(payment)" ])?></th>
            </tr>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th class="text-right">Total Items</th>
                <th class="text-right" >Price</th>
                <th class="text-right" >Discount</th>
                <th class="text-right">Net Price</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
            $sql="select * from sales where 1 $extra order by $orderby";
			
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        
                        <td><?php echo datetime_convert($r["date"]); ?></td>
                        <td><?php echo unslash($r["customer_name"]); ?></td>
                        <td class="text-right"><?php echo unslash($r["total_items"]); ?></td>
                        <td class="text-right">Rs. <?php echo curr_format(unslash($r["total_price"])); ?></td>
                        <td class="text-right">Rs. <?php echo curr_format(unslash($r["discount"])); ?></td>
                        <td class="text-right">Rs. <?php echo curr_format(unslash($r["net_price"])); ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="5" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "sales", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="8"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
