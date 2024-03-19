<?php
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
include "global_func_cc.php";
$condb = connectDB();

$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");

$v_skillnya 	    = get_session("v_skillnya");
$agentname          = get_session("v_agentname");
$v_agentname		= get_session("v_agentname");
$v_agentemail		= get_session("v_agentemail");
$v_agentlogin		= get_session("v_agentlogin");

$v				= get_param("v");
$iddet			= get_param("iddet");


$bucket_id		= get_param("bucket_id");
$get_nik	    = get_param("get_nik");
$get_name	    = get_param("get_name");
$get_no	        = get_param("get_no");
$status_id	    = get_param("status_id");
$date_period    = get_param("date_period");
$pic_upload     = get_param("pic_upload");


if(is_array($get_nik)) {
	$vget_nik = trim(implode(',',$get_nik));
}
$vget_nik = preg_replace('/\s+/', '', $vget_nik);
if(is_array($get_name)) {
	$vget_name = implode(',',$get_name);
}
if(is_array($get_no)) {
	$vget_no = implode(',',$get_no);
}
if(is_array($pic_upload)) {
	$vpic_upload = implode(',',$pic_upload);
}
// echo "Nik : $vget_nik | Agent : $vget_name | Phone : $vget_no | date : $date_period | status : $status_id";
//trail log
$reason_log = "cc_teleupload_bpkb $serverid";

$sql_del  	= "DELETE FROM cc_param_delete_filter 
                   	   WHERE create_by = '$v_agentid'"; 
$rec_del = mysqli_query($condb,$sql_del);

//insert
  $sqli = "INSERT INTO cc_param_delete_filter SET
			bucket_id			='$bucket_id',
			get_pic             ='$vpic_upload',
			get_nik			    ='$vget_nik',
			get_name		    ='$vget_name',
			get_no			    ='$vget_no',
			status_id		    ='$status_id',
			date_period		    ='$date_period',
			create_time			= now(),
			create_by			='$v_agentid'";
	//echo $sqli;
if($rec_i = mysqli_query($condb,$sqli)) {
	 	//user trail log
	//$traildesc = "Insert $reason_log Success";
	//cc_insert_trail_log($v_agentid,$traildesc,$condb);
			
	echo "Success";
} else {
			
	//user trail log
	//$traildesc = "Insert $reason_log Failed";
	//cc_insert_trail_log($v_agentid,$traildesc,$condb);
			
	echo "Failed";
}

disconnectDB($condb);
?>