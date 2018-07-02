<?php
if(!defined("APP_START")) die("No Direct Access");
if( count( $_POST ) > 0 ) {
	$response = array();
	extract( $_POST );
	if( isset( $action ) && in_array( $action, array( "get_parent_categories", "get_products", "get_orders", "get_expense", "add_expense", "save_order") ) ) {
		switch( $action ) {
			case "get_parent_categories":
				$rs = doquery( "select * from item_category where parent_id=0 and status=1 and sortorder > 0 order by sortorder", $dblink );
				$cats = array();
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$cats[] = array(
							"id" => $r[ "id" ],
							"title" => unslash($r[ "title" ]),
							"products" => array()
						);
					}
				}
				$response = $cats;
			break;
			case "get_products":
				$rs = doquery( "select * from item_category where parent_id='".$category_id."' and status=1 order by sortorder", $dblink );
				$cats = array();
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$cat = array(
							"id" => $r[ "id" ],
							"title" => unslash($r[ "title" ]),
							"products" => array()
						);
						$rs2 = doquery( "select * from items where item_category_id='".$r["id"]."' and status=1 and type=1 order by sortorder", $dblink );
						if( numrows( $rs2 ) > 0 ) {
							while( $r2 = dofetch( $rs2 ) ) {
								$cat[ "products" ][] = array(
									"id" => $r2[ "id" ],
									"title" => unslash( $r2[ "title" ] ),
									"unit_price" => $r2[ "unit_price" ],
									"image" => !empty($r2[ "image" ])?$file_upload_root."item/".unslash( $r2[ "image" ] ):""
								);
							}
						}
						$cats[] = $cat;
					}
				}
				$response = array(
					"category_index" => $category_index,
					"products" => $cats
				);
			break;
			case "get_orders":
				$rs = doquery( "select * from sales where date>='".date("Y-m-d H:i:s", date("H")>12?strtotime("today noon"):strtotime("yesterday noon"))."' order by date desc", $dblink );
				$orders = array();
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$orders[] = get_order( $r[ "id" ]);
					}
				}
				$response = $orders;
			break;
			case "get_expense":
				$rs = doquery( "select * from expense where status=1 and datetime_added>='".date("Y-m-d H:i:s", date("H")>12?strtotime("today noon"):strtotime("yesterday noon"))."' order by datetime_added desc", $dblink );
				$expense = array();
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$expense[] = array(
							"id" => $r[ "id" ],
							"datetime_added" => date("h:i A", strtotime($r[ "datetime_added" ])),
							"details" => unslash($r[ "details" ]),
							"amount" => unslash($r[ "amount" ])
						);
					}
				}
				$response = $expense;
			break;
			case "add_expense":
				$expense = json_decode( $expense );
				if( !empty( $expense->details ) && !empty( $expense->amount ) ) {
					doquery("insert into expense(datetime_added, details, amount, payment, added_by) values(NOW(), '".slash($expense->details)."', '".slash($expense->amount)."', '".slash($expense->amount)."', '".$_SESSION["logged_in_admin"]["id"]."')", $dblink);
					$id = inserted_id();
					$r = dofetch(doquery("select * from expense where id ='".$id."'", $dblink));
					$expense = array(
						"id" => $r[ "id" ],
						"datetime_added" => unslash($r[ "datetime_added" ]),
						"details" => unslash($r[ "details" ]),
						"amount" => unslash($r[ "amount" ])
					);
					$response = array(
						"status" => 1,
						"expense" => $expense
					);
				}
				else{
					$response = array(
						"status" => 0,
						"message" => "Enter Details and Amount"
					);
				}				
			break;
			case "save_order":
				$err = array();
				$order= json_decode($order);
				if( isset( $order->items ) && is_array( $order->items ) ) {
					if( count( $order->items ) > 0 ) {
						if( isset( $order->id ) && is_numeric( $order->id ) ) {
							$grand_total_price=$total_quantity=0;
							foreach( $order->items as $item ) {
								$total_price = $item->unit_price*$item->quantity;
								$grand_total_price+=$total_price;
								$total_quantity+=$item->quantity;
								$sale_item = doquery( "select * from sales_items where sale_id = '".$order->id."' and item_id='".$item->id."'", $dblink );
								if( numrows( $sale_item ) > 0 ) {
									$quanity_difference = $item->quantity - $sale_item[ "quanity" ];
									doquery( "update sales_items set quantity='".$item->quantity."', total_price='".$total_price."' where id='".$sale_item["id"]."'", $dblink );
								}
								else{
									$quanity_difference = $item->quantity;
									doquery("insert into sales_items(sales_id, item_id, unit_price, quantity, total_price) values('".$sale_id."', '".$item->id."', '".$item->unit_price."', '".$item->quantity."', '".$total_price."')", $dblink);
								}
								$r=doquery("select * from item_group where group_item_id='".$item->id."'", $dblink);
								if(numrows($r) > 0){
									while($rs=dofetch($r)){
										$dedcutQty=($rs['quantity'] * $quanity_difference);
										doquery("update items set quantity=quantity-(".$dedcutQty.") where id='".slash($rs['item_id'])."'", $dblink);
									}
								}
							}
							doquery("update sales set total_items=".$total_quantity.", total_price='".$grand_total_price."', discount='0', net_price='".($grand_total_price)."' where id='".$order->id."'", $dblink);
							$order = get_order( $order->id );
						}
						else{
							doquery( "insert into sales( date ) values(NOW())", $dblink );
							$order_id = inserted_id();
							$grand_total_price=$total_quantity=0;
							$dedcutQty=0;
							foreach( $order->items as $item ) {
								$quantity = $item->quantity;
								$unit_price = $item->unit_price;
								$total_price = $item->unit_price*$item->quantity;
								$grand_total_price+=$total_price;
								$total_quantity+=$item->quantity;
								doquery("insert into sales_items(sales_id, item_id, unit_price, quantity, total_price) values('".$order_id."', '".$item->id."', '".$item->unit_price."', '".$item->quantity."', '".$total_price."')", $dblink);									
								$r=doquery("select *  from item_group where group_item_id='".$item->id."'", $dblink);
								if(numrows($r) > 0){
									while($rs=dofetch($r)){
										$dedcutQty=($rs['quantity'] * $item->quantity);
										doquery("update items set quantity=quantity-".$dedcutQty." where id='".slash($rs['item_id'])."'", $dblink);	
									}
								}
							}
							doquery("update sales set total_items=".$total_quantity.", total_price='".$grand_total_price."', discount='0', net_price='".($grand_total_price)."' where id='".$order_id."'", $dblink);
							$order = get_order( $order_id );
						}
					}
					else {
						$err[] = "Blank order";
					}
				}
				else{
					$err[] = "Invalid data.";
				}
				if( count( $err ) > 0 ) {
				
				}
				else{
					$response = array(
						"status" => 1,
						"order" => $order
					);
				}
			break;
		}
	}
	echo json_encode( $response );
	die;
}
if( isset($_GET[ "tab" ]) && in_array( $_GET[ "tab" ], array("print_receipt")) ) {
	switch( $_GET[ "tab" ] ) {
		case "print_receipt":
			include("modules/sales/print.php");
			die;
		break;
	}
}
