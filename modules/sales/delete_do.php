<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$itemQuantity=array();
	$rs=doquery("select  items.quantity as 'itemQuantity',items.type , sales_items.quantity as 'salesItemQuantity' ,sales_items.item_id from sales_items INNER join items on items.id=sales_items.item_id where sales_id='".$id."'", $dblink);	
	if(numrows($rs)){
		while($r=dofetch($rs)){
			if($r['type']){
				// get items qty from sales item
				for($l=0;$l<$r['salesItemQuantity'];$l++){
					$GrpItems=doquery("select * from item_group where group_item_id='".slash($r['item_id'])."'", $dblink);
					if(numrows($GrpItems) > 0){
						while($grpItemData=dofetch($GrpItems)){
							// update group items
							doquery("update items set quantity=quantity+".$grpItemData['quantity']." where id='".slash($grpItemData["item_id"])."'", $dblink);
						}
					}
				}
			}
			$quantity=$r["salesItemQuantity"];
			doquery("update items set quantity=quantity+".$quantity." where id='".slash($r["item_id"])."'", $dblink);
		}
	}
	doquery("delete from sales_items where sales_id='".$id."'",$dblink);
	doquery("delete from sales where id='".$id."'",$dblink);
	header("Location: sales_manage.php");
	die;
}