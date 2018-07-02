<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Karachi');
/*--------------Site Configuration--------------*/
function get_config($var){
	global $dblink;
	$sql="select value from config_variable where `key`='".slash($var)."'";
	$resConfig=doquery($sql,$dblink);
	if(numrows($resConfig)>0){
		while($rowConfig=dofetch($resConfig))
			return unslash($rowConfig["value"]);
	}
}
$admin_types = array("No","Yes");
$admin_email=get_config("admin_email");
$site_title=get_config("site_title");
$site_url=get_config("site_url");
$admin_logo=get_config("admin_logo");
$login_logo=get_config("login_logo");
$address_phone=get_config("address_phone");
function admin_logo(){
	global $admin_logo, $site_title, $site_url;
	echo '<a href="'.$site_url.'/">';
	if(!empty($admin_logo)){
		echo '<img src="./uploads/config/'.$admin_logo.'" alt="'.$site_title.'" title="'.$site_title.'" />';
	}
	else
		echo $site_title;
	echo '</a>';
}
function check_admin_cookie(){
	global $dblink;
	if(isset($_COOKIE["_admin_logged_in"])){
		$r=doquery("select * from admin where id='".$_COOKIE["_admin_logged_in"]."'", $dblink);
		if(numrows($r)>0){
			$r=dofetch($r);
			$_SESSION["logged_in_admin"]=$r;
			return true;
		}
	}
	return false;
}
/*--------------Image Type Validation--------------*/
$file_upload_root="./uploads/";
$file_upload_url=$site_url."/uploads/";
$imagetypes=array("image/bmp","image/x-windows-bmp","image/jpg","image/jpeg","image/pjpeg","image/gif","image/png","image/x-png");
$ziptypes=array("rar","zip");
$month_array=array("Januray","February","March","April","May","June","July","August","September","October","November","December");
$videotypes=array("video/mpeg", "video/mpeg4", "video/avi", "video/flv", "video/mov", "video/avi", "video/mpg", "video/wmv", "video/vid");
$day_name=array('Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za');
$month_name=array('jan','feb','maa','apr','mei','juni','juli','aug','sep','oct','nov','dec');
/*--------------Send Mail Function--------------*/
function sendmail($to, $subject, $message, $efrom){
	@$headers  = 'MIME-Version: 1.0' . "\r\n";
	@$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	@$headers .= "From: ".$efrom. "\r\n";
	@$headers .= "Reply-To: ".$efrom. "\r\n";
	@$headers .= "X-Mailer: PHP/".phpversion();
	@mail($to,$subject,$message,$headers) or die("Email Sending Failed");
}

/*--------------Email Validation--------------*/
function emailok($email) {
  return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
}
/*----------------------------------------*/
function slash($str){
	if(!is_array($str))
		return utf8_encode(addslashes($str));
	else{
		for($i=0; $i<count($str); $i++)
			$str[$i]=slash($str[$i]);
		return $str;
	}
}
function unslash($str){
	return stripslashes(utf8_decode($str));
	}
function url_encode($str){
	return base64_encode(urlencode($str));
	}
function url_decode($str){
	return urldecode(base64_decode($str));
	}			
/*--------------  Function--------------*/
function getrealip(){
    $ip = FALSE;
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = FALSE;
        }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                if (version_compare(phpversion(), "5.0.0", ">=")) {
                    if (ip2long($ips[$i]) != false) {
                        $ip = $ips[$i];
                        break;
                    }
                } else {
                    if (ip2long($ips[$i]) != -1) {
                        $ip = $ips[$i];
                        break;
                    }
                }
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}



/*--------------Create Thumb Function--------------*/
function createThumb($image_path,$image_type,$thumb_size,$thumb_path, $height=""){
	$img=$image_path;
	$newfilename=$thumb_path;
	$w=$thumb_size;
	$h=$thumb_size;
	if($height!="")
		$h=$height;
	//Check if GD extension is loaded
	if (!extension_loaded('gd') && !extension_loaded('gd2')) {
	    trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
    //Get Image size info
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1: $im = imagecreatefromgif($img); break;

        case 2: $im = imagecreatefromjpeg($img);  break;

        case 3: $im = imagecreatefrompng($img); break;

        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;

    }
    //If image dimension is smaller, do not resize
    if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
    }else{
    	if($height==""){
			if ($w/$imgInfo[0] < $h/$imgInfo[1]) {
				$nWidth = $w;
				$nHeight = $imgInfo[1]*($w/$imgInfo[0]);
	        }else{
				$nWidth = $imgInfo[0]*($h/$imgInfo[1]);
		        $nHeight = $h;
			}
		}
		else{
			$nWidth=$w;
			$nHeight=$h;
		}
	}
	$nWidth = round($nWidth);
	$nHeight = round($nHeight);
	$newImg = imagecreatetruecolor($nWidth, $nHeight);
	/* Check if this image is PNG or GIF, then set if Transparent*/  
	if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	}
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
	//Generate the file, and rename it to $newfilename
    switch ($imgInfo[2]) {
	    case 1: imagegif($newImg,$newfilename); break;

        case 2: imagejpeg($newImg,$newfilename);  break;

        case 3: imagepng($newImg,$newfilename); break;

        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;

    }
    return $newfilename;
}

/*--------------ImageCreateFromBMP Function--------------*/
function ImageCreateFromBMP($filename){
   if (! $f1 = fopen($filename,"rb")) return FALSE;

   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
   if ($FILE['file_type'] != 19778) return FALSE;

   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] = 4-(4*$BMP['decal']);
   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

   $PALETTE = array();
   if ($BMP['colors'] < 16777216)
   {
    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
   }

   $IMG = fread($f1,$BMP['size_bitmap']);
   $VIDE = chr(0);

   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
   $P = 0;
   $Y = $BMP['height']-1;
   while ($Y >= 0)
   {
    $X=0;
    while ($X < $BMP['width'])
    {
     if ($BMP['bits_per_pixel'] == 24)
        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
     elseif ($BMP['bits_per_pixel'] == 16)
     { 
        $COLOR = unpack("n",substr($IMG,$P,2));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 8)
     { 
        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 4)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 1)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     else
        return FALSE;
     imagesetpixel($res,$X,$Y,$COLOR[1]);
     $X++;
     $P += $BMP['bytes_per_pixel'];
    }
    $Y--;
    $P+=$BMP['decal'];
   }

   fclose($f1);

 return $res;
}

function get_image($img, $size, $folder){
	global $site_url;
	if(!empty($img)){
		$ext=explode(".", $img);
		$ext=$ext[count($ext)-1];
		$image_name=str_replace(".".$ext, "", $img);
		if(file_exists($folder."/thumbnails/".$image_name."_".$size.".".$ext)){
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
		else{
			switch($size){
				case "large": $width=800; break;
				case "medium": $width=240; break;
				case "thumbnail": $width=130; break;
				case "avatar": $width=56; break;
		}
			if(!is_dir($folder."/thumbnails"))
				mkdir($folder."/thumbnails");
			createThumb($folder."/".$img,"", $width, $folder."/thumbnails/".$image_name."_".$size.".".$ext);
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
	}
	return;
}


function get_image_mcp($img, $size, $folder){
	if(!empty($img)){
		$ext=explode(".", $img);
		$ext=$ext[count($ext)-1];
		$image_name=str_replace(".".$ext, "", $img);
		if(file_exists($folder."/thumbnails/".$image_name."_".$size.".".$ext)){
			unlink($folder."/thumbnails/".$image_name."_".$size.".".$ext);
		}
		switch($size){
			case "large": $width=800; break;
			case "medium": $width=240; break;
			case "thumbnail": $width=130; break;
			case "avatar": $width=56; break;
			
			if(!is_dir($folder."/thumbnails"))
				mkdir($folder."/thumbnails");
			createThumb($folder."/".$img,"", $width, $folder."/thumbnails/".$image_name."_".$size.".".$ext);
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
	}
	return;
}

/*-------------- Function--------------*/
function get_bitly( $url ){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );
	$url='http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login=sacom&apiKey=R_792325a8f2b7d40db961199f59672dfe';
    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );
    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
	$sar=explode('"',$content);
	return $sar[17];
}

/*--------------getCountryCombo Function--------------*/
function getCountryCombo($country){
 	global $dblink;
 	$rs=doquery("select iso, printable_name from country",$dblink);
 	$str="<select name='country'><option value=''>Select Any</option>";
	 while($r=dofetch($rs)){
	 	if($country==$r["iso"])
			$selected="selected";
		else
			$selected="";
		$str.="<option value='".$r["iso"]."' ".$selected.">".$r["printable_name"]."</option>";
 	}
 	$str.="</select>";
 	return $str;
}
$product_type = array("Raw Material","Sale Item");
/*--------------getCountryName Function--------------*/
function getCountryname($country){
	global $dblink;
	$r=dofetch(doquery("select printable_name from country where iso='$country'",$dblink));
	return $r["printable_name"];
}
function getAdminType($value){
	if( $value == 1 ) {
		return "Yes";
	}
	else {
		return "No";
	}
}
/*--------------getPaymentType Function--------------*/
function getPaymentType($value){
	if($value)
		return "Debit";
	else
		return "Credit";
}
/*--------------getProductType Function--------------*/
function getProductType($value){
	if( $value == 1 ) {
		return "Sale Item";
	}
	else {
		return "Raw Material";
	}
}
/*--------------Sorttable Function--------------*/
function sorttable($table,$id,$sort,$type,$more_cond=''){
	global $dblink;
	if($more_cond!="")
		$more_cond=' and '.$more_cond;
	if($type=="add"){
		$res=doquery("select sortorder from ".$table." where sortorder>=".$sort.$more_cond,$dblink);
		if(numrows($res)>0){
			doquery("update ".$table." set sortorder=sortorder+1 where sortorder >=".$sort.$more_cond,$dblink);
		}
		doquery("update ".$table." set sortorder=".$sort." where id=".$id,$dblink);
	}
	if($type=="edit"){
		$rs=doquery("select sortorder from $table where id='$id'",$dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			if($r["sortorder"]>$sort){
				doquery("update $table set sortorder=sortorder+1 where sortorder>=$sort and sortorder<".$r["sortorder"].$more_cond,$dblink);
			}
			elseif($r["sortorder"]<$sort){
				doquery("update $table set sortorder=sortorder-1 where sortorder<=$sort and sortorder>".$r["sortorder"].$more_cond,$dblink);
			}
			doquery("update $table set sortorder=$sort where id='".$id."'",$dblink);		
		}
	}
	if($type=="delete"){
		$rs=doquery("select sortorder from $table where id='$id'",$dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			doquery("update $table set sortorder=sortorder-1 where sortorder>".$r["sortorder"].$more_cond, $dblink);
		}
	}
}

/*--------------getCMS Function--------------*/
function getCMS($id){
	global $dblink;
	$rs=doquery("select title, body from cms where id='$id'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$r["title"]=stripslashes($r["title"]);
		$r["body"]=stripslashes($r["body"]);
	}
	else{
		$r["title"]="Oops Page not found";
		$r["body"]="The page you requested is not found on this server.";
	}
	return $r;
}

/*--------------generate_seo_link Function--------------*/
function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array()){
	$return = trim(preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input)));
	if($remove_words){
		$return = remove_words($return,$replace,$words_array);
	}
	return str_replace(' ',$replace,$return);
}

/*--------------Remove_words Function--------------*/
function remove_words($input,$replace,$words_array = array(),$unique_words = true){
	$input_array = explode(' ',$input);
	$return = array();
	foreach($input_array as $word){
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true)){
			$return[] = $word;
		}
	}
	return implode($replace,$return);
}

/*--------------getFilename Function--------------*/
function getFilename($originalname, $title){
	$ext=explode(".", $originalname);
	$ext=$ext[count($ext)-1];
	return generate_seo_link($title).".".$ext;
}

/*--------------getSortCombo Function--------------*/
function getSortCombo($table,$selected,$type,$more_cond='')
{
	global $dblink;
	if($more_cond!="")
		$more_cond=' and '.$more_cond;
	$sql="select count(id) from $table where 1".$more_cond;
	$res=doquery($sql,$dblink);
	$row=dofetch($res);
	if($type=="add")
		$cnt=$row[0]+1;
	else
		$cnt=$row[0];
	echo "<select name='sortorder'>";
	for($i=1;$i<=$cnt;$i++)
	{
		if($selected>0)
		{
			if($i==$selected)
				echo "<option value='$i' selected>$i</option>";
			else
				echo "<option value='$i'>$i</option>";
		}
		else
		{
			if($i==$cnt)
				echo "<option value='$i' selected>$i</option>";
			else
				echo "<option value='$i'>$i</option>";
		}
	}
	echo "</select>";
}

/*--------------getInputBox Function--------------*/
function getInputBox($type, $value, $id, $class,$default_values){
	global $file_upload_url;
	switch($type){
		case "text":
				echo '<input type="text" size="62%" name="text_'.$id.'" class="'.$class.'" value="'.$value.'" />';
        break;
		case "submit":
			echo '<input type="submit" name="submit_'.$id.'" class="'.$class.'" value="'.$value.'" />';
	    break;
		case "button":
			echo '<input type="button" name="submit_'.$id.'" class="'.$class.'" value="'.$value.'" />';
	    break;
		case "file":
			if ($value != "") {
			echo '<input type="file" size="50%" name="file_'.$id.'" class="'.$class.'" /><a href="'.$file_upload_url.'/config/'.$value.'" target="_blank" style=" color:#000;">&nbsp;&nbsp;Previous File</a>';
			}
			else{
				echo '<input type="file" size="50%" name="file_'.$id.'" class="'.$class.'" />&nbsp;&nbsp;No File Exist';
				}
        break;
		case "textarea":
			echo '<textarea name="textarea_'.$id.'" class="'.$class.'" cols="80" rows="5">'.$value.'</textarea>';
        break;
		case "editor";
			echo '<textarea name="editor_'.$id.'" id="editor_'.$id.'" class="'.$class.'" cols="30" rows="5">'.$value.'</textarea><br /><a rev="editor_'.$id.'" class="UploadCenterButton" href="#">Upload Center</a>';
        break;
		case "radio":
			$radioarray=explode(";",$default_values);
			foreach($radioarray as $radio){
			if(strpos($radio, ":selected")!== FALSE){
				$selected='checked="checked"';
				$radio=str_replace(":selected", "", $radio);
			}
			else
				$selected="";
			echo '<input type="radio" name="radio_'.$id.'" value="'.$radio.'" '.$selected.' class="'.$class.'" />'.$radio.'';
			}
        break;
		case "checkbox":
			$checkarray=explode(";",$default_values);
			foreach($checkarray as $check){
			if(strpos($check, ":selected")!== FALSE){
				$selected='checked="checked"';
				$check=str_replace(":selected", "", $check);
			}
			else
				$selected="";
			echo '<input type="checkbox" name="checkbox_'.$id.'[]" value="'.$check.'" '.$selected.' class="'.$class.'" />'.$check.'';
			}
		break;
		case "combobox":
			$optionsarray=explode(";",$default_values);
			echo '<select name="combobox_'.$id.'" class="'.$class.'">';
			foreach($optionsarray as $option){
			if(strpos($option, ":selected")!== FALSE){
				$selected='selected="selected"';
				$option=str_replace(":selected", "", $option);
			}
			else
				$selected="";
			echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
			}
			echo '</select>';
		break;
	}
}
/////////////////////////Date Convert///////////////////////////

function date_dbconvert($date){
	$date = explode("/", $date);
	return date("Y-m-d", strtotime($date[2]."-".$date[1]."-".$date[0]));
}
function date_convert($date_added){
	return date("d/m/Y", strtotime($date_added));
}
function datetime_dbconvert($date){
	$datetime = explode(" ", $date);
	$date = date_dbconvert($datetime[0]);
	return date("Y/m/d H:i:s", strtotime($date." ".$datetime[1]." ".$datetime[2]));
}
function datetime_convert($date_added){
	return date("d/m/Y h:i A", strtotime($date_added));
}
function get_title($table_id, $table){
 global $dblink;
 $rs=doquery("select title from $table where id=$table_id", $dblink);
 if(numrows($rs)>0){
  $r=dofetch($rs);
  return unslash($r["title"]);
 }
}
function get_field($table_id, $table, $field_name='title'){
 	global $dblink;
 	$rs=doquery("select ".$field_name." from $table where id='".$table_id."'", $dblink);
 	if(numrows($rs)>0){
 		$r=dofetch($rs);
 		return unslash($r[$field_name]);
 	}
}

function get_country($table_id, $table){
	global $dblink;
	$rs=doquery("select country from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["country"]);
	}
}

function get_page_url($page_id){
	global $dblink, $site_url;
	$page=doquery("select seo_url_path, seo_url from pages where id='".$page_id."'", $dblink);
	$url="";
	if(numrows($page)>0){
		$page=dofetch($page);
		$path=unslash($page["seo_url_path"]);
		$seo_url=unslash($page["seo_url"]);
		$url=$site_url."/".($path!=""? $path."/":"").$seo_url.".html";
	}
	return $url;
}
function get_name($table_id, $table){
	global $dblink;
	$rs=doquery("select name from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["name"]);
	}
}

function get_username($table_id, $table){
	global $dblink;
	$rs=doquery("select username from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["username"]);
	}
}

function get_menu($position, $parent=0){
	global $dblink, $site_url;
	$rs=doquery("select * from frontmenus where position='".$position."' and status=1 and parentid='".$parent."' order by sortorder", $dblink);
	if(numrows($rs)>0){
		$str='<ul>';
		while($r=dofetch($rs)){
			if(unslash($r["url"])=="#age-group"){
				$str.='<li><a href="#">'.unslash($r["title"]).'</a>';
				$rs1=doquery("select title, seo_url from age_group where status='1' order by sortorder", $dblink);
				if(numrows($rs1)>0){
					$str.='<ul>';
					while($r1=dofetch($rs1)){
						$str.='<li><a href="'.$site_url."/".unslash($r1["seo_url"]).'.html">'.unslash($r1["title"]).'</a></li>';
					}
					$str.='</ul>';
				}
				$str.='</li>';
			}
			else{
				$str.='<li><a href="'.(strpos($r["url"], "//")!==false? unslash($r["url"]):$site_url."/".unslash($r["url"])).'">'.unslash($r["title"]).'</a>';
				$str.=get_menu($position, $r["id"]);
				$str.='</li>';
			}
		}
		$str.='</ul><div class="clr"></div>';
		return $str;
	}
}
function curr_format($amount){
	return number_format($amount, 0, '.',',')." ".get_config("currency_code");
}
$all_sites_array=array();
function get_site($site_name){
	global $dblink;
	if(!isset($all_sites_array[$site_name])){
		$rs=doquery("select * from auction_site where title='".slash($site_name)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$all_sites_array[$site_name]=$r;
		}
	}
	if(isset($all_sites_array[$site_name]))
		return $all_sites_array[$site_name];
}
function file_content($url, $site_name){
	$filename=generate_seo_link($url).".html";
	if(is_file("module/".$site_name."/cache/".$filename) && (time()-filemtime("module/".$site_name."/cache/".$filename))<3600){
		$content=file_get_contents("module/".$site_name."/cache/".$filename);
	}
	else{
		$site=get_site($site_name);
		if(isset($_SESSION["current_running_site"][$site_name]["total_pages"]) && $site["batch_size"]<=$_SESSION["current_running_site"][$site_name]["total_pages"]){
			sleep($site["batch_delay"]);
			$_SESSION["current_running_site"][$site_name]["total_pages"]=0;
		}
		if(!isset($_SESSION["current_running_site"][$site_name]["total_pages"]))
			$_SESSION["current_running_site"][$site_name]["total_pages"]=1;
		else
			$_SESSION["current_running_site"][$site_name]["total_pages"]++;
		$content=file_get_contents($url);
		file_put_contents("module/".$site_name."/cache/".$filename, $content);
	}
	return $content;
}

function clean_text($str){
	return trim(preg_replace('/\s+/', ' ', strip_tags($str)));
}
/*-----------------------------Get Image--------------------------*/
function deleteFile($filepath){
	if(is_file($filepath)){
		unlink($filepath);
	}
	global $site_url;
	$ext=substr($filepath, strrpos($filepath, '.')+1);
	$filepath=substr($filepath, 0, strrpos($filepath, '.'));
	$image_path=substr($filepath, 0, strrpos($filepath, '/'));
	$image_name=substr($filepath, strrpos($filepath, '/')+1);
	$image_name=$image_path."/thumbnails/".$image_name;
	if(file_exists($image_name."_large.".$ext)){
		unlink($image_name."_large.".$ext);
	}
	if(file_exists($image_name."_medium.".$ext)){
		unlink($image_name."_medium.".$ext);
	}
	if(file_exists($image_name."_thumbnails.".$ext)){
		unlink($image_name."_thumbnail.".$ext);
	}
	if(file_exists($image_name."_avatar.".$ext)){
		unlink($image_name."_avatar.".$ext);
	}
}
function get_category_id($str){
	global $dblink;
	$str=explode(" - ", $str);
	$str=$str[0];
	$rs=doquery("select id from auction_category where title='".slash($str)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$category_id=$r["id"];
	}
	else{
		$rs=doquery("select auction_category_id from auction_category_acronym where title='".slash($str)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$category_id=$r["auction_category_id"];
		}
		else{
			/*if(!empty($str)){
				doquery("insert into auction_category(title) values('".slash($str)."')", $dblink);
				$category_id=mysql_insert_id();
			}
			else*/
				$category_id=0;
		}
	}
	return $category_id;
}

function get_location_id($str){
	global $dblink;
	$rs=doquery("select id from auction_location where title='".slash($str)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$location_id=$r["id"];
	}
	else{
		$rs=doquery("select auction_location_id from auction_location_acronym where title='".slash($str)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$location_id=$r["auction_location_id"];
		}
		else{
			if(!empty($str)){
				doquery("insert into auction_location(title) values('".slash($str)."')", $dblink);
				$location_id=mysql_insert_id();
			}
			else
				$location_id=0;
		}
	}
	return $location_id;
}

function get_category_name($category_id){
	$title=get_title($category_id, "auction_category");
	return empty($title)?"Uncategorized":$title;
}
function get_location_name($location_id){
	$title=get_title($location_id, "auction_location");
	return empty($title)?"Unknown":$title;
}
function submission_count($cat_id=0){
	global $dblink;
	$sql="select count(1) from submission where status=1";
	if($cat_id!=0)
		$sql.=" and (category_id='".$cat_id."' or category_id in (select id from category where parent_id='".$cat_id."'))";
	$rs=dofetch(doquery($sql, $dblink));
	return $rs["count(1)"];
}
function get_the_excerpt($str){
	if(strlen($str)>35)
		return substr($str, 0, 35)."...";
	else
		return $str;
}
function user_link($user_id, $return=0, $extra=""){
	global $dblink, $site_url;
	$rs=doquery("select username from users where id='".$user_id."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$username=unslash($r["username"]);
	}
	else
		$username='Anonymous';
	$link=$site_url.'/profile/'.$username;
	if($extra!="")
		$link.="?".$extra;
	if($return)
		return $link;
	else
		echo 'by <a href="'.$link.'">'.$username.'</a>';
}
function post_link($post_id, $extra=""){
	global $site_url;
	$link='post/'.$post_id."/".generate_seo_link(get_title($post_id, "submission")).".html";
	if($extra!="")
		$link.='?'.$extra;
	return $site_url."/".$link;
}
function blog_post_link($post_id, $extra=""){
	global $site_url;
	$link='blog/post/'.$post_id."/".generate_seo_link(get_title($post_id, "blog_post")).".html";
	if($extra!="")
		$link.='?'.$extra;
	return $site_url."/".$link;
}
function submission_link($submission_id, $extra="",$page){
	$link=$page.'?id='.$submission_id;
	if($extra!="")
		$link.='&'.$extra;
	return $link;
}

function get_parent_cat($id){
	global $dblink;
	$r=doquery("select parent_id from category where id='".$id."'", $dblink);
	if(numrows($r)>0){
		$r=dofetch($r);
		return $r["parent_id"]==0?$id:$r["parent_id"];
	}
	else
		return 0;
}
function user_avatar($user){
	global $site_url;
	if(isset($user["avatar"]) && !empty($user["avatar"]))
		echo '<img class="avatar" src="'.$site_url."/".get_image(unslash($user["avatar"]), 'avatar', 'uploads/user_avatar').'" alt="'.$user["username"].'" width="56" height="56">';
	else
		echo '<img class="avatar" src="'.$site_url.'/images/default-user.png" alt="'.$user["username"].'" width="56" height="56">';
}
function get_user($user_id){
	global $dblink;
	$r=doquery("select * from users where id='".$user_id."'", $dblink);
	if(numrows($r)>0){
		$r=dofetch($r);
		return $r;
	}
	return false;
}
function get_time_diff($time){
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$difference     = $now - strtotime($time);
    $tense         = "ago";
   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
   	}
	$difference = round($difference);
	if($difference != 1) {
    	$periods[$j].= "s";
   	}
   	if($j==0)
   		$difference="few";
   	return "$difference $periods[$j] ago";
}
function follow_link($user_id){
	global $dblink;
	$is_follower=false;
	if(isset($_SESSION["user"])){
		$rs=doquery("select * from user_followers where user_id='".$user_id."' and follower_id='".$_SESSION["user"]["id"]."'", $dblink);
		if(numrows($rs)>0)
			$is_follower=true;
	}
	if($is_follower)
		echo '<a href="'.user_link($user_id, 1, "follow=1").'" class="bttn mini sub follow green"><i class="fa fa-check"></i> Following</a>';
	else
		echo '<a href="'.user_link($user_id, 1, "follow=1").'" class="bttn mini sub follow"><i class="fa fa-rss"></i> Follow</a>';
}
function comments_count($post_id){
	global $dblink;
	$r=dofetch(doquery("select count(1) as total from post_comments where status=1 and post_id='".$post_id."'", $dblink));
	if($r["total"]==1)
		$rtn="<strong>1</strong> Comment";
	else
		$rtn="<strong>".$r["total"]."</strong> Comments";
	echo $rtn;
}
function blog_comments_count($post_id){
	global $dblink;
	$r=dofetch(doquery("select count(1) as total from blog_post_comments where status=1 and post_id='".$post_id."'", $dblink));
	if($r["total"]==1)
		$rtn="<strong>1</strong> Comment";
	else
		$rtn="<strong>".$r["total"]."</strong> Comments";
	echo $rtn;
}
function rand_str($length){
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$str='';
	for($i=0; $i<$length; $i++){
		$str.=$chars[rand(0, strlen($chars))];
	}
	return $str;
}
function put_random_link($content, $url){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	if(strpos($paragraph, '<')!==false){
		$raw_sentenses=explode("<", $paragraph);
		$sentenses=array();
		foreach($raw_sentenses as $k=>$v){
			$temp_sentense=rtrim(trim(substr($v, strpos($v, ">")), ">"));
			if($temp_sentense!="")
				$sentenses[]=$temp_sentense;
		}
		if(count($sentenses)>0){
			$sentense=rand(1, count($sentenses));
			$sentense=$sentenses[$sentense-1];
		}
	}
	if(isset($sentense)){
		$words=explode(" ", $sentense);
		if(count($words)>7)
			$total_words=7;
		else
			$total_words=count($words);
		$word_count=rand(1, $total_words);
		$start_word=rand(1, count($words)-$word_count);
		$word="";
		for($i=$start_word; $i<=$start_word+$word_count-1; $i++)
			$word.=$words[$i-1]." ";
		$word=trim($word);
		return str_replace($word, '<a href="'.$url.'">'.$word.'</a>', $content);		
	}
	return $content;
}
function put_random_tag($content, $tag){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	if(strpos($paragraph, '<')!==false){
		$raw_sentenses=explode("<", $paragraph);
		$sentenses=array();
		foreach($raw_sentenses as $k=>$v){
			$temp_sentense=rtrim(trim(substr($v, strpos($v, ">")), ">"));
			if($temp_sentense!="")
				$sentenses[]=$temp_sentense;
		}
		if(count($sentenses)>0){
			$sentense=rand(1, count($sentenses));
			$sentense=$sentenses[$sentense-1];
		}
	}
	if(isset($sentense)){
		$words=explode(" ", $sentense);
		if(count($words)>7)
			$total_words=7;
		else
			$total_words=count($words);
		$word_count=rand(1, $total_words);
		$start_word=rand(1, count($words)-$word_count);
		$word="";
		for($i=$start_word; $i<=$start_word+$word_count-1; $i++)
			$word.=$words[$i-1]." ";
		$word=trim($word);
		return str_replace($word, '<'.$tag.'>'.$word.'</'.$tag.'>', $content);		
	}
	return $content;
}
function put_random_video($content, $video){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	return str_replace($paragraph, $paragraph.'<div class="random_video">'.$video.'</div>', $content);		
}

function removedir($dir) {
   	$files = array_diff(scandir($dir), array('.','..'));
   	foreach ($files as $file) {
   		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
function get_new_sort_order($table){
	global $dblink;
	$sort=dofetch(doquery("select count(id) from ".$table,$dblink));
	$sort=$sort["count(id)"];
	$sort=$sort+1;
	return $sort;
}
function get_fontawesome_icons(){
	$icons_array=array();
	$str=file_get_contents("css/font-awesome.css");
	if(preg_match_all('/fa-(.*):before\s*{\s*(.*)"/', $str, $matches)){
		$icons=$matches[1];
		$icon_codes=$matches[2];	
		for($i=0; $i<count($icons); $i++){
			$code=str_replace("content: \"\\", '', $icon_codes[$i]);
			$icons_array[]=array($icons[$i], $code);
		}
	}
	return $icons_array;
}
function update_meta($table, $table_id, $meta_key, $meta_value){
	global $dblink;
	$rs=doquery("select id from ".$table."_meta where ".$table."_id='".$table_id."' and meta_key='".slash($meta_key)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$id=$r["id"];
		doquery("update ".$table."_meta set meta_value='".slash($meta_value)."' where id='".$id."'", $dblink);
	}
	else{
		doquery("insert into ".$table."_meta(".$table."_id, meta_key, meta_value) values('".$table_id."', '".slash($meta_key)."', '".slash($meta_value)."')", $dblink);
	}
}
function get_meta($table, $table_id, $meta_key, $meta_value=""){
	global $dblink;
	$rtn="";
	$rs=doquery("select meta_value from ".$table."_meta where ".$table."_id='".$table_id."' and meta_key='".slash($meta_key)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$rtn=unslash($r["meta_value"]);
	}
	else{
		$rtn=$meta_value;
	}
	return $rtn;
}
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function get_order( $id ) {
	global $dblink;
	$order= dofetch(doquery("select * from sales where id='".$id."'", $dblink));
	$order = array(
		"id" => $id,
		"date" => date("h:i A", strtotime($order[ "date" ])),
		"items" => array()
	);
	$rs = doquery( "select a.*, b.title from sales_items a inner join items b on a.item_id = b.id where sales_id='".$id."' order by id", $dblink );
	if( numrows($rs) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$order["items"][] = array(
				"id" => $r[ "item_id" ],
				"title" => unslash( $r[ "title" ] ),
				"unit_price" => (float)$r[ "unit_price" ],
				"quantity" => (int)$r[ "quantity" ]
			);
		}
	}
	return $order;
}