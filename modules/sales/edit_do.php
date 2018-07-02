<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["sales_edit"])){
	extract($_POST);
	$err="";
	if(empty($date) || empty($customer_name) || count($items)==0)
		$err="Fields with (*) are Mandatory.<br />";
		$items_array=array();
		$i=0;
		$total_quantity=0;
		foreach($items as $item){
		if(!empty($item)){
			if(array_key_exists($item, $items_array)){
				$items_array[$item]+=$quantity[$i];
			}
			else{
				$items_array[$item]=array(
				    "unit_price" => $unit_price[$i],
					"quantity" => $quantity[$i]
				);
			}
		}
		$i++;
	}
	$PrevArray=array();
	$updateValues=array();
	if($err==""){
		$sql="Update sales set `date`='".slash(datetime_dbconvert(unslash($date)))."',`customer_name`='".slash($customer_name)."', phone='".slash($phone)."', address='".slash($address)."', customer_id='".slash($customer_id)."' where id='".$id."'";
		doquery($sql,$dblink);
		$grand_total_price=0;
		foreach($items_array as $item_id=>$items){
			$quantity =($items_array[$item_id]['quantity']);
			$unit_price =($items_array[$item_id]['unit_price']);
			//$r=dofetch(doquery("select unit_price from items where id='".slash($item_id)."'", $dblink));
			$total_price=$unit_price*$quantity;
			$grand_total_price+=$total_price;
			$total_quantity+=$quantity;
			$prev_item = doquery("select * from sales_items where sales_id='".$id."' and item_id='".$item_id."'", $dblink);
			if(numrows( $prev_item )){
				$previtemQty=dofetch( $prev_item );
				$sale_item=doquery("select * from item_group where group_item_id='".$previtemQty['item_id']."'", $dblink);
				if(numrows($sale_item) > 0){
					while($sale_items=dofetch($sale_item)){
						$current_saleQty=($sale_items['quantity']*$items['quantity']);
						$prevSale=($sale_items['quantity']*$previtemQty['quantity']);
						$purchase_item=dofetch(doquery("select * from purchase_items where item_id='".$sale_items['item_id']."'", $dblink));
						$total_qty=($purchase_item['quantity']+$prevSale);
						doquery("update purchase_items set quantity='".($total_qty- $current_saleQty)."' where item_id='".slash($sale_items['item_id'])."'", $dblink);
						
					}
				}
				doquery("update sales_items set `unit_price`='".$unit_price."', `quantity`='".$quantity."', `total_price`='".($total_price)."' where id='".$previtemQty["id"]."'", $dblink);
				doquery("update items set quantity=quantity-".($quantity-$previtemQty["quantity"])." where id='".slash($item_id)."'", $dblink);
			}
			else{
				doquery("insert into sales_items(sales_id, item_id, unit_price, quantity, total_price) values('".$id."', '".$item_id."', '".$unit_price."', '".$quantity."', '".$total_price."')", $dblink);
				$r=doquery("select * from item_group where group_item_id='".$item_id."'", $dblink);
				if(numrows($r) > 0){
					while($rs=dofetch($r)){
						$dedcutQty=($rs['quantity'] * $quantity);
						doquery("update purchase_items set quantity=quantity-".$dedcutQty." where item_id='".slash($rs['item_id'])."'", $dblink);	
					}
				}	
				//doquery("update items set quantity=quantity-".$quantity." where id='".slash($item_id)."'", $dblink);	
			}
		}
		doquery("update sales set total_items=".$total_quantity.",total_price='".$grand_total_price."', discount='".$discount."', net_price='".($grand_total_price-$discount)."' where id='".$id."'", $dblink);
		unset($_SESSION["sales_manage"]["edit"]);
		header('Location: sales_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["sales_manage"]["edit"][$key]=$value;
		header('Location: sales_manage.php?tab=edit&err='.url_encode($err));
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from sales where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$date=date_convert($date);
		$items=$quantity=array();
		$rs=doquery("select * from sales_items where sales_id='".$id."'", $dblink);
		if(numrows($rs)){
			while($r=dofetch($rs)){
				$items[]=$r["item_id"];
				$quantity[]=$r["quantity"];
			}
		}	
		if(isset($_SESSION["sales_manage"]["edit"]))
			extract($_SESSION["sales_manage"]["edit"]);
	}
	else{
		header("Location: sales_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: sales_manage.php?tab=list");
	die;
}