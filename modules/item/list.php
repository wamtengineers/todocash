<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["items"]["list"]["q"]=$q;
}
if(isset($_SESSION["items"]["list"]["q"]))
	$q=$_SESSION["items"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and items.title like '%".$q."%'";
	$is_search=true;
}
if(isset($_GET["type"])){
	$type=slash($_GET["type"]);
	$_SESSION["items"]["list"]["type"]=$type;
}
if(isset($_SESSION["items"]["list"]["type"]))
	$type=$_SESSION["items"]["list"]["type"];
else
	$type="";
if($type!=""){
	$extra.=" and type='".$type."'";
	$is_search=true;
}


if(isset($_GET["category"])){
	$category=slash($_GET["category"]);
	$_SESSION["items"]["list"]["category"]=$category;
}
if(isset($_SESSION["items"]["list"]["category"]))
	$category=$_SESSION["items"]["list"]["category"];
else
	$category="";

if($category!=""){
	$extra.=" and item_category.parent_id='".$category."'";
	$is_search=true;
}


if(isset($_GET["stock"])){
	$stock=slash($_GET["stock"]);
	$_SESSION["items"]["list"]["stock"]=$stock;
}
if(isset($_SESSION["items"]["list"]["stock"]))
	$stock=$_SESSION["items"]["list"]["stock"];
else
	$stock="";
if($stock != ""){
	if( $stock == "0" ){
		$extra.=" and quantity>10";
	}
	if( $stock == "1" ){
		$extra.=" and quantity<=10";
	}
	if( $stock == "2" ){
		$extra.=" and quantity=0";
	}
	$is_search=true;
}
$order_by = "title";
$order = "asc";
if( isset($_GET["order_by"]) ){
	$_SESSION["items"]["list"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["items"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["items"]["list"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["items"]["list"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["items"]["list"]["order"] ) ){
	$order = $_SESSION["items"]["list"]["order"];
}
$orderby = $order_by." ".$order;



?>
<div class="page-header">
	<h1 class="title">Manage Items</h1>
  	<ol class="breadcrumb">
    	<li class="active">All the administrators who can use the manage item</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="items_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="items_manage.php?tab=report"><i class="fa fa-print" aria-hidden="true"></i></a>
            <a class="btn btn-primary" href="items_manage.php?tab=update_items"><i  aria-hidden="true"></i>Update Item</a>   
    	</div> 
    </div> 
</div> 
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<div class="col-sm-2">
                	<select name="type" id="type" title="Choose Option">
                        <option value="">Select Product Type</option>
                        <?php
						foreach ($product_type as $key=>$value) {
                            ?>
                            <option value="<?php echo $key?>"<?php echo ($type!="" && $key==$type)?' selected="selected"':""?>><?php echo $value ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                	<select name="category" id="category" title="Choose Option">
                    <option value="">Select Item Category</option>
					<?php
                    $res=doquery("Select * from item_category where parent_id='0' order by sortorder ASC",$dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($category==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                     <?php			
                        }			
                    }
                    ?>
                </select>
                </div>
                <div class="col-sm-2">
                	<select name="stock" id="stock">
                    	<option value="">Select Stock</option>
                        <option value="0"<?php if($stock=="0") echo ' selected="selected"';?>>In Stock</option>
                        <option value="1"<?php if($stock=="1") echo ' selected="selected"';?>>Low Stock</option>
                        <option value="2"<?php if($stock=="2") echo ' selected="selected"';?>>Out of Stock</option>
                    </select>
                </div>
                <div class="col-sm-3 col-xs-8">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-3 col-xs-2 text-left">
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
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="12%">Item Category</th>
                <th width="12%">
                	<a href="items_manage.php?order_by=type&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Product Type 
                        <?php
						if( $order_by == "type" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
 					</a>
 				</th>
                <th width="15%">
                	<a href="items_manage.php?order_by=title&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Title
                    	<?php
						if( $order_by == "title" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
                    </a>
                </th>
                <th class="text-right" width="9%">
                	<a href="items_manage.php?order_by=unit_price&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Unit Price
                        <?php
						if( $order_by == "unit_price" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
 					</a>
                </th>
                <th class="text-right" width="8%">
                	<a href="items_manage.php?order_by=quantity&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Quantity
                		<?php
						if( $order_by == "quantity" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
 					</a>
                </th>
                <th class="text-center" width="10%">Item Group</th>
                <th class="text-center" width="5%">Status</th>
                <th class="text-center" width="10%">Actions</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
			
            $sql="select items.* from items  inner join item_category on item_category.id=items.item_category_id  where 1 $extra order by sortorder";
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php if($r["item_category_id"]==0) echo ""; else echo get_field($r["item_category_id"], "item_category","title");?></td>
                        <td><?php echo getProductType(unslash($r["type"])); ?></td>
                        <td><?php echo unslash($r["title"]); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["unit_price"])); ?></td>
                        <td class="text-right"><?php echo unslash($r["quantity"]); ?></td>
                        <td class="text-center">
                        	<?php
                            if( $r["type"] != 0 ) {
								?>
                        		<a class="fancybox_iframe red item-group" title="Edit Record" href="item_group_manage.php?parent_id=<?php echo $r['id']?>">
                            	    <i class="fa fa-sitemap" aria-hidden="true"></i>
                            	</a>
                                <?php
							}
							?>
                        </td>
                        <td class="text-center"><a href="items_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                            <?php
                            if($r["status"]==0){
                                ?>
                                <img src="images/offstatus.png" alt="Off" title="Set Status On">
                                <?php
                            }
                            else{
                                ?>
                                <img src="images/onstatus.png" alt="On" title="Set Status Off">
                                <?php
                            }
                            ?>
                        </a></td>
                        <td align="center">
                            <a href="items_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a href="items_manage.php?tab=print&id=<?php echo $r['id'];?>" class="barcode_print_button"><img title="Print Label" alt="Print" src="images/view.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="items_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="6" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "items", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="11"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
