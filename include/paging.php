<?php
if(isset($_GET["rows"]))
	$_SESSION["rows"]=$_GET["rows"];
if(isset($_SESSION["rows"]))
	$rows=$_SESSION["rows"];
else
	$rows=25;
if(isset($_GET['page'])){
	$pageNum = $_GET['page'];
}
else{
	$pageNum=1;
}

function show_page($rows, $pageNum, $query){
	global $dblink;
	$rowsPerPage = $rows;
	$pageNum = $pageNum;
	$offset = ($pageNum - 1) * $rowsPerPage;
	$query = $query." LIMIT $offset, $rowsPerPage";
	$result = doquery($query, $dblink);
	return $result;
}

function pages_list($rowsPerPage, $table, $qr="", $pageNum){
	global $dblink;
	if($qr==""){
		$query   = "SELECT COUNT(*) AS numrows FROM $table";
		$result  = mysql_query($query) or die('Error, query failed');
		$row     = mysql_fetch_array($result);
		$total_rows = $row['numrows'];
	}
	else{
		$result  = doquery($qr, $dblink);
		$total_rows = numrows($result);
	}
	$maxPage = ceil($total_rows/$rowsPerPage);
	$self = $_SERVER['PHP_SELF'];
	$nav  = "<select name='page' onChange=\"window.location.href='$self?page='+this.value\">";

	for($page = 1; $page <= $maxPage; $page++)
	{
	   $nav .= " <option value'$page'".($page == $pageNum?' selected':'').">$page</option> ";
	}
	$nav .= "</select>";
	if ($pageNum > 1)
	{
   		$page  = $pageNum - 1;
   		$prev  = " <a href=\"$self?page=$page\" class=\"prev\">Prev</a> ";
		$first = " <a href=\"$self?page=1\" class=\"first\">First</a> ";
	}
	else
	{
	   $prev  = '&nbsp;'; 
	   $first = '&nbsp;'; 
	}
	if ($pageNum < $maxPage)
	{
	   $page = $pageNum + 1;
	   $next = " <a href=\"$self?page=$page\" class=\"next\">Next</a> ";
	   $last = " <a href=\"$self?page=$maxPage\" class=\"last\">Last</a> ";
	}
	else
	{
	   $next = '&nbsp;'; 
	   $last = '&nbsp;'; 
	}
	
	if($total_rows!=0){
		$numrows="<select name='rows' onChange=\"window.location.href='$self?rows='+this.value\">";
		$numrows.="<option value='10' ";
	
	if($rowsPerPage==10)
		$numrows.="selected";
		$numrows.=">10</option>";
		$numrows.="<option value='25' ";
	
	if($rowsPerPage==25)
		$numrows.="selected";
		$numrows.=">25</option>";
		$numrows.="<option value='100' ";
	
	if($rowsPerPage==100)
		$numrows.="selected";
		$numrows.=">100</option>";
		$numrows.="<option value='1000' ";
	
	if($rowsPerPage==1000)
		$numrows.="selected";
		$numrows.=">1000</option>";
		$numrows.="</select>";
	}
	else
		$numrows="";
    return $total_rows.' records. '.$numrows.$first . $prev . $nav . $next . $last;
}
?>