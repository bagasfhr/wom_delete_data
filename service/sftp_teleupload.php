<?php 
include "../../sysconf/global_func.php";
include "../../sysconf/db_config.php";
// include "../../sysconf/session.php";


// $v_agentid                  = get_session("v_agentid");
// $v_agentname                = get_session("v_agentname");

$condb = connectDB();
$v_agentid   = get_param('v_agentid');

    $sqla = "SELECT * FROM cc_tele_parameter_sftp a 
             WHERE 1=1 AND a.modul_code='2'";//echo "$sqla ";die();  b.id='$id' || 13-12-2022 > AND (a.flag_eligible='0' OR a.flag_eligible IS NULL)
    $resa = mysqli_query($condb,$sqla);
    if($reca = mysqli_fetch_array($resa)){
        @extract($reca,EXTR_OVERWRITE);
        $sftp=$sftp_url;
        $user_sftp=$sftp_username;
        $pass_sftp=$sftp_password;
        $pass_sftp = enc_aes256($pass_sftp, "decrypt");
    }

$namafile = "delete_status_$v_agentid";
$filename = "../../public/teleupload/generate/".$namafile.".txt";
            $baris=0;
            $total_data=0;
            if (file_exists($filename)) {
                $fh = fopen($filename, "r");
                // while ($file_handle) {
                while ($line = fgets($fh)) {
                  // <... Do your work with the line ...>
                  $arrline = explode("|", $line);
                  $arrbucket_name[$baris] = $arrline[0];
                  $arrcustomer_id[$baris] = $arrline[1];
                  $arrorder_no[$baris] = $arrline[2];
                  $arrupdate_by[$baris] = $arrline[3];
                  $delete_from = $arrline[4];
                  $delete_to = $arrline[5];
                  $arrdelete_status[$baris] = $arrline[6];
                  $delete_time = $arrline[7];
                  $delete_by = $arrline[8];
                  $arrupdate_time[$baris] = $arrline[9];
                  $arrcustomer_name[$baris] = $arrline[10];
                  $delete_date = $arrline[11];
                  if ($arrdelete_status[$baris]==1) {
                    $total_data++;
                  }
                  $baris++;
                }
                fclose($fh);
                // }
            };
            $delete_date = str_replace("\n", '', $delete_date);

$file                        = "Log Summary delete data Upload $delete_date".".txt";//get_param("file");

// $condb = connectDB();

$params = array(
    'data' => $file,
    'sftp' => $sftp,
    'user' => $user_sftp,
    'pass' => $pass_sftp
);

$body = json_encode($params);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://devcrm.wom.co.id:8766/upload");
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'token: '.$mytoken
));
$exec = curl_exec($ch);
// print_r($exec);

curl_close($ch);

// disconnectDB($condb);

?>