<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["sales_add"])){
	extract($_POST);
	$err="";
	if(empty($date) || empty($customer_name) || count($items)==0)
		$err="Fields with (*) are Mandatory.<br />";
		$items_array=array();
		$i=0;
		foreach($items as $item){
			if(!empty($item)){
				if(array_key_exists($item, $items_array)){
					$items_array[$item]+=$quantity[$i];
				}
				else{
					$items_array[$item]=array("unit_price" => $unit_price[$i],"quantity" => $quantity[$i]);
				}
			}
			$i++;
		}
		if($err==""){
			$sql="INSERT INTO sales (date, customer_name, phone, address, customer_id) VALUES ('".slash(datetime_dbconvert($date))."','".slash($customer_name)."','".slash($phone)."','".slash($address)."','".slash($customer_id)."')";
			doquery($sql,$dblink);
			$sale_id=inserted_id();
			$grand_total_price=$total_quantity=0;
			$dedcutQty=0;
			foreach($items_array as $item_id=>$items){
				$quantity =($items_array[$item_id]['quantity']);
				$unit_price =($items_array[$item_id]['unit_price']);
				//$r=dofetch(doquery("select unit_price from items where id='".slash($item_id)."'", $dblink));
				$total_price=$unit_price*$quantity;
				$grand_total_price+=$total_price;
				$total_quantity+=$quantity;
				doquery("insert into sales_items(sales_id, item_id, unit_price, quantity, total_price) values('".$sale_id."', '".$item_id."', '".$unit_price."', '".$quantity."', '".$total_price."')", $dblink);									
				$r=doquery("select * from item_group where group_item_id='".$item_id."'", $dblink);
				if(numrows($r) > 0){
					while($rs=dofetch($r)){
						$dedcutQty=($rs['quantity'] * $quantity);
						doquery("update purchase_items set quantity=quantity-".$dedcutQty." where item_id='".slash($rs['item_id'])."'", $dblink);	
					}
				}	
				//doquery("update items set quantity=quantity-".$quantity." where id='".slash($item_id)."'", $dblink);		
			}
			doquery("update sales set total_items=".$total_quantity.",total_price='".$grand_total_price."', discount='".$discount."', net_price='".($grand_total_price-$discount)."' where id='".$sale_id."'", $dblink);
			unset($_SESSION["sales_manage"]["add"]);
			header('Location: sales_manage.php?tab=list&print='.$sale_id.'&msg='.url_encode("Sucessfully Added"));
			die;
		}
		else{
			foreach($_POST as $key=>$value)
				$_SESSION["sales_manage"]["add"][$key]=$value;
			header('Location: sales_manage.php?tab=add&err='.url_encode($err));
			die; 
		}
}