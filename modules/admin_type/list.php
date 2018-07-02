<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["admin_type_manage"]["q"]=$q;
}
if(isset($_SESSION["admin_type_manage"]["q"]))
	$q=$_SESSION["admin_type_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and title like '%".$q."%'";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Manage Admin Type</h1>
  	<ol class="breadcrumb">
    	<li class="active">All Admin Type</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="admin_type_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> <a class="btn btn-light" href="#"><i class="fa fa-line-chart"></i></a> 
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
                <th width="5%">S.No</th>
                <th width="5%" class="center">
                    <div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div>
                </th>
                <th width="30%">Title</th>
                <th width="10%">Can Add</th>
                <th width="10%">Can Edit</th>
                <th width="10%">Can Delete</th>
                <th width="10%">Can Read</th>
                <th width="10%" class="center">Status</th>
                <th width="10%" class="center">Actions</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
			$sql="select * from admin_type where 1 $extra";
			$rs=show_page($rows, $pageNum, $sql);
			if(numrows($rs)>0){
				$sn=1;
				while($r=dofetch($rs)){             
					?>
					<tr>
						<td><?php echo $sn;?></td>
						<td class="center">
							<div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
						</td>
						<td><?php echo unslash($r["title"]); ?></td>
						<td><?php echo getAdminType(unslash($r["can_add"])); ?></td>
						<td><?php echo getAdminType(unslash($r["can_edit"])); ?></td>
						<td><?php echo getAdminType(unslash($r["can_delete"])); ?></td>
						<td><?php echo getAdminType(unslash($r["can_read"])); ?></td>
                        <td class="text-center"><a href="admin_type_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                        <td>
                            <a href="admin_type_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="admin_type_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
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
                    <td colspan="3" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "admin_type", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="9"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>