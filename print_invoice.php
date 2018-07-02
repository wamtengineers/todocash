<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice</title>
<link href="css/thermal.css" rel="stylesheet" type="text/css" />
</head>
<body onload="1javascript:window.print(false);">
<div id="main">
    <div id="logo"> <img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" style="width:80%;" /></div>
    <div class="contentbox">
        <p>Game Number: <strong style="float:right"><?php echo $game["id"]?></strong></p>
        <p>Table: <strong style="float:right"><?php echo get_field($game["table_id"],"snooker_table", "table_number")?></strong></p>
        <hr>
        <h4 style="margin-bottom:5px; margin-top:10px;">Player Information</h4>
        <p>Player 1: <strong style="float:right"><?php echo unslash($game["player_1"])?></strong></p>
        <p>Player 2: <strong style="float:right"><?php echo unslash($game["player_2"])?></strong></p>
        <p>Loser: <strong style="float:right"><?php echo !empty($game["loser"])?unslash($game[$game["loser"]]):"--"?></strong></p>
        <hr>
        <h4 style="margin-bottom:5px; margin-top:10px;">Game</h4>
        <p>Date: <strong style="float:right"><?php echo date("d-M-Y", strtotime($game["date"]))?></strong></p>
        <p>Start Time: <strong style="float:right"><?php echo date("h:i A", $start_time)?></strong></p>
        <p>End Time: <strong style="float:right"><?php echo date("h:i A", $end_time)?></strong></p>
        <p>Total Mins: <strong style="float:right"><?php echo $game["total_mins"]?> mins</strong></p>
        <hr style="border:0; border-top:1px solid #999">
        <p><strong>TOTAL</strong><strong style="float:right">Rs. <?php echo curr_format($game["total_amount"])?></strong></p>
    </div>
    <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div> 
</div>
</body>
</html>