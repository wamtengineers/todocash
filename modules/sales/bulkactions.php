<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			//die('hello');
			$i=0;
			while($i<count($id)){
				$rs=doquery("select  items.quantity as 'itemQuantity',items.type , sales_items.quantity as 'salesItemQuantity' ,sales_items.item_id from sales_items INNER join items on items.id=sales_items.item_id where sales_id='".$id[$i]."'", $dblink);
				if(numrows($rs)){
					while($r=dofetch($rs)){
						if($r['type']){
							// get items qty from sales item
							for($l=0;$l<$r['salesItemQuantity'];$l++){
								$GrpItems=doquery("select * from item_group where group_item_id='".slash($r['item_id'])."'", $dblink);
								if(numrows($GrpItems) > 0){
									while($grpItemData=dofetch($GrpItems)){
										// update group items
										doquery("update items set quantity=quantity+".$grpItemData['quantity']." 
										where id='".slash($grpItemData["item_id"])."'", $dblink);
									}
								}
							}
						}
						$quantity=$r["salesItemQuantity"];
						doquery("update items set quantity=quantity+".$quantity." where id='".slash($r["item_id"])."'", $dblink);
					}
				}
				doquery("delete from sales_items where sales_id='".$id[$i]."'",$dblink);
				doquery("delete from sales where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				doquery("update sales set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				doquery("update sales set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: sales_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}