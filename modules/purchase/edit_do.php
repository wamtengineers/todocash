<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["purchase_edit"])){
	extract($_POST);
	$err="";
	if(empty($date) || empty($supplier_name) || count($items)==0)
		$err="Fields with (*) are Mandatory.<br />";
	$items_array=array();
	$i=0;
	foreach($items as $item){
		if(!empty($item)){
			if(array_key_exists($item, $items_array)){
				$items_array[$item]["quantity"]+=$quantity[$i];
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
	if($err==""){
		$sql="Update purchase set `date`='".slash(datetime_dbconvert(unslash($date)))."',`supplier_name`='".slash($supplier_name)."',`phone`='".slash($phone)."',`address`='".slash($address)."',`supplier_id`='".slash($supplier_id)."' where id='".$id."'";
		doquery($sql,$dblink);
		$grand_total_price=$quantity=0;
		foreach($items_array as $item_id=>$item){
			$r=dofetch(doquery("select unit_price from items where id='".slash($item_id)."'", $dblink));
			$total_price=$item["unit_price"]*$item["quantity"];
			$grand_total_price+=$total_price;
			$quantity+=$item["quantity"];
			$prev=doquery("select id, quantity from purchase_items where purchase_id='".$id."' and item_id='".$item_id."'", $dblink);
			if(numrows($prev)){
				$prev=dofetch($prev);
				doquery("update purchase_items set `unit_price`='".$item["unit_price"]."', `quantity`='".$item["quantity"]."', `total_price`='".$total_price."' where id='".$prev["id"]."'", $dblink);
				doquery("update items set quantity=quantity+".($item["quantity"]-$prev["quantity"])." where id='".slash($item_id)."'", $dblink);
			}
			else{
				doquery("insert into purchase_items(purchase_id, item_id, unit_price, quantity, total_price) values('".$purchase_id."', '".$item_id."', '".$item["unit_price"]."', '".$item["quantity"]."', '".$total_price."')", $dblink);
				doquery("update items set quantity=quantity+".$item["quantity"]." where id='".slash($item_id)."'", $dblink);	
			}
		}
		doquery("update purchase set total_items=".$quantity.", discount='".$discount."', total_price='".$grand_total_price."', net_price='".($grand_total_price-$discount)."' where id='".$id."'", $dblink);
		unset($_SESSION["purchase_manage"]["edit"]);
		header('Location: purchase_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["purchase_manage"]["edit"][$key]=$value;
		header('Location: purchase_manage.php?tab=edit&err='.url_encode($err));
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from purchase where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$date=date_convert($date);
		$items=$unit_price=$quantity=array();
		$rs=doquery("select * from purchase_items where purchase_id='".$id."' order by id", $dblink);
		if(numrows($rs)){
			while($r=dofetch($rs)){
				$items[]=$r["item_id"];
				$unit_price[]=$r["unit_price"];
				$quantity[]=$r["quantity"];
			}
		}
		if(isset($_SESSION["purchase_manage"]["edit"]))
			extract($_SESSION["purchase_manage"]["edit"]);
	}
	else{
		header("Location: purchase_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: purchase_manage.php?tab=list");
	die;
}