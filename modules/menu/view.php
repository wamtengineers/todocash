<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div style="float:right; margin-top:15px;"><a href="menu_manage.php?tab=list" title="Back" class="button">Back</a></div>
<h2 id="mang_heading"><?php echo $title;?></h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
    	<tr>
        	<td class="round_lefttop blank"></td>
            <td class="round_righttop blank"></td>
        </tr>
    	<?php
      	$i=0;
		?>
      <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>
      	<td width="18%">Title  </td>
        <td width="87%"><?php echo $title; ?></td>
      </tr>
      <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>
      	<td width="18%">Target URL</td>
        <td width="87%"><?php echo $url; ?></td>
      </tr>
      <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>  
      	<td width="18%">Parent</td>
        <td>
        <?php
           	$res=doquery("Select title from menus where parentid=".$parentid,$dblink);
				if(mysql_num_rows($res)>0){
					$rec=dofetch($res);
					echo unslash($rec["title"]);
				}
				else{
					echo "No Parent";	
				}
		?>
       </td>
     </tr>
     <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>  
     	<td width="18%">Sort Order</td>
        <td><?php echo $sortorder;?></td>
     </tr>
     <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>  
     	<td width="18%" class="list_heading">SubMenu Icon</td>
        <td class="list_heading"><img src="images/menu_icons/small_icons/<?php echo $small_icon; ?>"  alt="icon" title="<?php echo $title;?>" width="20px" height="20px"/></td>
     </tr>
     <tr <?php echo ($i%2==0)?'class="even"':'class="odd"'; $i++;?>>  
     	<td width="18%">Main Menu Icon</td>
        <td><img src="images/menu_icons/<?php echo $icon; ?>"  alt="icon" title="<?php echo $title; ?>" /> </td>
     </tr>
     <tr>
        <td class="round_leftbottom blank"></td>
        <td class="round_rightbottom blank"></td>
    </tr>
</table>
