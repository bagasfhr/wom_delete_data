<?php
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";

$condb = connectDB();

// get value
$iddet 			= get_param("iddet");
$modul_code 	= get_param("modul_code");
$modul_name 	= get_param("modul_name");
$url 			= get_param("sftp_url");
$username		= get_param("sftp_username");
$password		= get_param("sftp_password");

$total = 0;
if ($modul_code != "") {
	$sql = "SELECT count(id) as total FROM cc_tele_parameter_sftp WHERE modul_code=$modul_code";
	$res = mysqli_query($condb, $sql);
	if ($row = mysqli_fetch_array($res)) {
		$total = $row["total"];
	}

	// update
	if ($total > 0) {
		$sql_data = "UPDATE cc_tele_parameter_sftp SET 
						modul_code 		= $modul_code,
						modul_name 		= '$modul_name',
						sftp_url 			= '$url',
						sftp_username 		= '$username',
						sftp_password 		= '$password'
						WHERE modul_code=$modul_code
						";
	}else{
		$sql_data = "INSERT INTO cc_tele_parameter_sftp SET 
						modul_code 		= $modul_code,
						modul_name 		= '$modul_name',
						sftp_url 			= '$url',
						sftp_username 		= '$username',
						sftp_password 		= '$password'
						";
	}

	$save = mysqli_query($condb, $sql_data);
	if($save){
		echo "Success";
	}else{
		echo $modul_name;
		echo $sql_data;
		echo "failed";
	}
}else{
		echo "sql_data";
	echo "failed";
}


?>