<?php
if(!defined("APP_START")) die("No Direct Access");
$sql="SELECT I.quantity as 'group_item_quantity', IG.item_id as 'item_id', IG.quantity  as 'item_quantity', I.id as 'group_item_id' from items as I INNER JOIN item_group as IG on IG.group_item_id=I.id where I.type='1'";
$GrpItems=doquery($sql,$dblink);
if( numrows( $GrpItems ) > 0 ) {
	while( $GroupItem = dofetch( $GrpItems ) ){
		$GroupitemQty=$GroupItem['item_quantity'];
		$single_itemQry=doquery("SELECT * from items as I  where I.type='0' AND I.id='".$GroupItem['item_id']."'",$dblink);
		if(numrows($single_itemQry) > 0){
			while($SingleItem=dofetch($single_itemQry)){
				$SingleitemQty=$SingleItem['quantity'];
				$NewGroupItemQty=floor($SingleitemQty/$GroupitemQty);
				doquery("update items set quantity='".$NewGroupItemQty."' where id='".slash($GroupItem['group_item_id'])."'", $dblink);
			}
		}
	}
	unset($_SESSION["items_manage"]["update_items"]);
		header('Location: items_manage.php?tab=list&msg='.url_encode("Sucessfully Updated Added"));
		die;
	}