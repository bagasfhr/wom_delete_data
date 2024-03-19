<?php
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";

$condb = connectDB();

$v_agentid = get_session("v_agentid");

$par_success 	= get_param("par_success");
$par_failed 	= get_param("par_failed");

$par_success=="on"? $par_success=1:$par_success=0;
$par_failed=="on"? $par_failed=1:$par_failed=0;
$sql = "INSERT INTO cc_teleupload_param_report_delete_log SET param_success=$par_success, param_failed=$par_failed, created_by=$v_agentid, created_time=now()";
$save = mysqli_query($condb, $sql);

if ($save) {
	echo "Success";
}else{
	echo "Failed";
}
?>