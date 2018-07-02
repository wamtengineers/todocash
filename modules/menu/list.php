<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["menu_manage"]["q"]=$q;
}
if(isset($_SESSION["menu_manage"]["q"]))
	$q=$_SESSION["menu_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and (a.title like '%".$q."%' or a.`url` like '%".$q."%')";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Manage Menu</h1>
  	<ol class="breadcrumb">
    	<li class="active">Admin Panel Menus.</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="menu_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-10 col-xs-8">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-1 col-xs-2">
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
               	<th width="20%">Title</th>
               	<th width="20%">Parent</th>
                <th width="20%">Admin Types</th>  
                <th width="10%">Target URL</th>
                <th width="12%">Sort Order</th>
                <th width="8%" class="round_righttop">Actions</th>
      		</tr>
    	</thead>
    	<tbody>
			<?php 
           	$sql="select a.*, b.title as parent from menu a left join menu b on a.parent_id=b.id where 1 $extra order by a.sortorder, depth asc";
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
                        <td><?php echo unslash($r["title"]); ?></td>
              			<td><?php echo unslash($r["parent"]);?></td>
                        <td>
							<?php
							$admin_type = array();
							$rs2 =doquery("select title from menu_2_admin_type a inner join admin_type b on a.admin_type_id=b.id where menu_id='".$r["id"]."'", $dblink);
							if( numrows( $rs2 ) > 0 ) {
								while( $r2 = dofetch( $rs2 ) ) {
									$admin_type[] = $r2[ "title" ];
								}
							}
							echo implode( ", ", $admin_type );
							?>
                        </td>
               			<td><?php echo unslash($r["url"]); ?></td>
               			<td align="center"><?php echo $r["sortorder"];?></td>
                        <td>
                            <a href="menu_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="menu_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
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
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "menus", $sql, $pageNum)?></td>
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
