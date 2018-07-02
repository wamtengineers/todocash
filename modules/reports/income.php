<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["income"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["income"]["date_from"]))
	$date_from=$_SESSION["reports"]["income"]["date_from"];
else
	$date_from='';

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["income"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["income"]["date_to"]))
	$date_to=$_SESSION["reports"]["income"]["date_to"];
else
	$date_to='';

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if( empty( $extra ) ) {
	$extra = ' and 1=0 ';
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
                <input type="hidden" name="tab" value="income" />
                <span class="col-sm-2 text-to">Date From</span>
                <div class="col-sm-3">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" >
                </div>
                <span class="col-sm-2 text-to">Date From</span>
                <div class="col-sm-3">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" >
                </div>
                
                <div class="col-sm-2 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    
    
 
    
    	<?php
		$sql="select sum(total_items), sum(total_price), sum(discount), sum(net_price) from sales where 1 $extra";
		$total=dofetch(doquery($sql, $dblink));
		?>
    	<tr>
            <th class="text-right">Total Items Sold</th>
            <th class="text-right"><?php echo $total[ "sum(total_items)" ]?></th>
        </tr>
        <tr>
            <th class="text-right">Total Price</th>
            <th class="text-right">Rs. <?php echo curr_format($total[ "sum(total_price)" ])?></th>
        </tr>
        <tr>
            <th class="text-right">Total Discount</th>
            <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(discount)" ])?></th>
        </tr>
        <tr class="head">
            <th class="text-right">Net Total</th>
            <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(net_price)" ])?></th>
        </tr>
          <tr>
            <th class="text-right">Total Purchase</th>
            
            <?php
			
			$totalItemPrice=0;
			
			///SELECT item_group.item_id ,sum(item_group.quantity) , sum(sales_items.quantity) FROM `sales` 
			//	   INNER JOIN sales_items on sales_items.sales_id=sales.id
				//   INNER JOIN item_group on item_group.group_item_id=sales_items.item_id
				//where 1=1 $extra group by sales_items.item_id 
			
			
			
        	$sql=doquery("SELECT item_group.item_id ,sum(item_group.quantity) , sum(sales_items.quantity) FROM `sales` INNER JOIN sales_items on sales_items.sales_id=sales.id INNER JOIN item_group on item_group.group_item_id=sales_items.item_id where 1=1 group by sales_items.item_id", $dblink);
			
		
			
		//	$total_item=dofetch(doquery($sql, $dblink));
		    	
			//	if($total_item)
				while($total_item=dofetch($sql))
				{
					
					 $totalSaleItems=$total_item['sum(item_group.quantity)']*$total_item['sum(sales_items.quantity)'];
					  
					
					$purchased_item=doquery("SELECT * from purchase_items where purchase_items.item_id='".$total_item['item_id']."'", $dblink);
					
					if(numrows($purchased_item) > 0)
					{
						while($purchase_items=dofetch($purchased_item))
						{
								$purchase_items['quantity'];
								
								//echo $totalSaleItems;
								if($totalSaleItems  > 0)
								{
									
									//echo $purchase_items['quantity'];
									 $remainingItemqty = $totalSaleItems-$purchase_items['quantity'];
										
									if($remainingItemqty > 0)
									{	
										$totalItemPrice +=$purchase_items['quantity']*$purchase_items['unit_price'];
									 	
									}
									else
									{
										
										$totalItemPrice +=$totalSaleItems*$purchase_items['unit_price'];
									}
									
									$totalSaleItems-=$purchase_items['quantity'];
								
							
								}
						
									
						}
						
					}
					
				}
			
			?>
            
            <th class="text-right" >Rs. <?php echo curr_format($totalItemPrice)?></th>
        </tr>
  	</table>
</div>
