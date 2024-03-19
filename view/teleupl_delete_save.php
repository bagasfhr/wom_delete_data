<?php
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
require_once '../../library/excel/simplexlsx.class.php';
ini_set('track_errors', 1);

function parserer($word){
  $word = addslashes($word);
  $word = str_replace("`", "", $word);

  return $word;
}
$condb = connectDB();

$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");

$iddeb = mysqli_real_escape_string($condb,get_param("iddeb"));

$iddeb     = get_param("iddeb");//echo "string $iddeb";die();


$sql = "SELECT 
        a.id, a.agent_id, a.agent_name 
    FROM 
        cc_agent_profile a
    ORDER BY a.id DESC ";
$res = mysqli_query($condb,$sql);
while($rec = mysqli_fetch_array($res)) {
$arr_agentid[$rec["id"]] = $rec["agent_name"]; 
}
mysqli_free_result($res);


$sql = "select a.* from cc_teleupload_param_report_delete_log a
order by a.id desc limit 1 ";
$res = mysqli_query($condb,$sql);
if($rec = mysqli_fetch_array($res)) {
$param_success = $rec["param_success"]; 
$param_failed  = $rec["param_failed"]; 
}
mysqli_free_result($res);


$sql = "SELECT 
        a.id, a.bucket_name 
    FROM 
        cc_teleupload_bucket a
    ORDER BY a.id DESC ";
$res = mysqli_query($condb,$sql);
while($rec = mysqli_fetch_array($res)) {
$arr_bucketid[$rec["id"]] = $rec["bucket_name"]; 
}
mysqli_free_result($res);

$datedate = DATE("Y-m-d h:i:s");

$sqlcl = " SELECT 
a.*
FROM cc_param_delete_filter a 
where a.create_by=$v_agentid ";
$rescl = mysqli_query($condb,$sqlcl);
$tot_data    = mysqli_num_rows($rescl);
while($reccl = mysqli_fetch_array($rescl)){
  $filter_id    = $reccl['id'];
  $bucket_id  = $reccl['bucket_id'];
  $get_nik  = $reccl['get_nik'];
  $get_name = $reccl['get_name'];
  $get_no = $reccl['get_no'];
  $status_id  = $reccl['status_id'];
  $date_period  = $reccl['date_period'];
  $get_pic  = $reccl['get_pic'];
}
$get_nik  = "'".str_replace(',', "','", $get_nik)."'";
$get_name = "'".str_replace(',', "','", $get_name)."'";
$get_no   = "'".str_replace(',', "','", $get_no)."'";
$sWhere   = "";
$mode   = get_param("mode");
// echo "Nik : $get_nik | Agent : $get_name | Phone : $get_no | date : $date_period | status : $status_id";

$arr_date_period = explode(" - ", $date_period);


$sqlv2 = "DELETE FROM cc_teleupload_data_delete_log 
        where delete_by='$v_agentid'";//echo "$sqlv";
mysqli_query($condb,$sqlv2);

$log_query = "";
$log_query = "bucket_name,pic_upload,date_upload_from,date_upload_to,nik,agreement_no,deleted_status,create_time,create_by\n";
$log_query3 = "";
$sukses = 0;
$error  = 0;
$datesftp = date('Y').date('m').date('d')." ".date('h').date('i').date('s');
$sqlcl = " SELECT 
      a.*
      FROM cc_teleupload_data a 
      where a.id IN ($iddeb) ";
$rescl = mysqli_query($condb,$sqlcl);
$tot_data    = mysqli_num_rows($rescl);
while($reccl = mysqli_fetch_array($rescl)){
  @extract($reccl,EXTR_OVERWRITE);
  $log_query2 .= "INSERT INTO cc_teleupload_data (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time) VALUES ('$id','$bucket_id', '$region', '$kapos_name', '$order_no', '$cabang', '$no_rangka', '$customer_id', '$customer_name', '$item_description', '$mobile1', '$mobile2', '$phone1', '$office_phone1', '$otr_price', '$item_year', '$monthly_income', '$monthly_instalment', '$address_cust', '$kecamatan', '$kelurahan', '$kode_kat', '$tenor_id', '$max_past_due_dt', '$religion', '$cust_rating', '$agrmnt_rating', '$status_kontrak', '$tanggal_jatuh_tempo', '$tgl_lahir_konsumen', '$tgl_lahir_pasangan', '$gender_konsumen', '$kepemilikan_rumah', '$kepemilikan_bpkb', '$asset_type', '$asset_temp', '$label_priority_temp', '$ketegori_product', '$asset_name', '$bpkb_name', '$otr', '$tenor', '$angsuran', '$sisa_piutang', '$sisa_tenor', '$profession_name', '$profession_category_name', '$job_position', '$industry_type_name', '$jumlah_kontrak_perasset', '$estimasi_terima_bersih', '$label_priority', '$spv_id', '$agent_id', '$assign_time', '$assign_status', '$last_phoneno', '$last_phonecall', '$last_phonecall_sub', '$last_followup_call', '$last_followup_by', '$last_followup_date', '$last_followup_time', '$follow_up', '$remark_desc', '$flag_assets', '$create_by', '$create_time', '$update_by', '$update_time');\n";
  $param_custid = preg_replace('/\s+/', '', $customer_id);
  



	  $sqlv = "DELETE FROM cc_teleupload_data 
	          where id = $id";//echo "$sqlv";
	  if(mysqli_query($condb,$sqlv)){
	    $sukses ++;$delete_status=1;

    if ($param_success=='1') {
      $log_query .= "$arr_bucketid[$bucket_id],$arr_agentid[$update_by],$arr_date_period[0],$arr_date_period[1],$param_custid,$order_no,Success,$datedate,$arr_agentid[$v_agentid]\n";
    }
	  $sqlv2 = "DELETE FROM cc_teleupload_data_det 
	          where order_no = $order_no";//echo "$sqlv";
	  mysqli_query($condb,$sqlv2);
	  }else{
	  	$error ++;$delete_status=2;
      if ($param_failed=='1') {
        $log_query .= "$arr_bucketid[$bucket_id],$arr_agentid[$update_by],$arr_date_period[0],$arr_date_period[1],$param_custid,$order_no,Failed,$datedate,$arr_agentid[$v_agentid]\n";

      }
    $sqlv3 = "UPDATE cc_teleupload_data_temp 
              SET delete_status=2 
              where order_no = $order_no";//echo "$sqlv";
    mysqli_query($condb,$sqlv3);
	  }

    $sqlall = "INSERT INTO cc_teleupload_data_delete_log (id_teleupload_data,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time,delete_status)
                    SELECT id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time, '$v_agentid', now(), '$delete_status' FROM cc_teleupload_data_temp
                    WHERE id='$id' AND delete_by='$v_agentid'";//echo "string $sqlall ";die();
      mysqli_query($condb,$sqlall);
      $param_generate="";
      $param_generate = "$arr_bucketid[$bucket_id]|$customer_id|$order_no|$arr_agentid[$update_by]|$arr_date_period[0]|$arr_date_period[1]|$delete_status|$datedate|$arr_agentid[$v_agentid]|$update_time|$customer_name|$datesftp\n";
      $log_query3 .= $param_generate;
}

$param_suk=$sukses;
if ($sukses=='0') {
  $param_suk ='';
}
$param_err=$error;
if ($error=='0') {
  $param_err ='';
}
echo "Berhasil = $sukses \n Tidak Berhasil = $error|$param_suk|$param_err";
/* start log */

$datetimenow = DATE("Y-m-d h:i:s");
$namafile = "Log Summary delete data Upload ".$datesftp.".txt";//echo "string $namafile";
$fp = fopen("../../public/teleupload/delete/".$namafile, 'a');
// fwrite($fp, "#".$datetimenow);
// fwrite($fp, "\n\n");
fwrite($fp, $log_query);
// fwrite($fp, "\n\n#=========================================\n\n");
fclose($fp);

/* end log */
$datetimenow = DATE("Y-m-d h:i:s");
$namafile = "Log Summary delete data Upload2 ".date('Y').date('m').date('d').".txt";//echo "string $namafile";
$fp = fopen("../../public/teleupload/delete/".$namafile, 'a');
// fwrite($fp, "#".$datetimenow);
// fwrite($fp, "\n\n");
fwrite($fp, $log_query2);
// fwrite($fp, "\n\n#=========================================\n\n");
fclose($fp);

/* end log */

/* start log */
// $log_query3 = $log_query3."|$datesftp";
$datetimenow = DATE("Y-m-d h:i:s");
$namafile = "delete_status_$v_agentid.txt";//echo "string $namafile";
unlink("../../public/teleupload/generate/".$namafile);
$fp = fopen("../../public/teleupload/generate/".$namafile, 'a');
// fwrite($fp, "#".$datetimenow);
// fwrite($fp, "\n\n");
fwrite($fp, $log_query3);
// fwrite($fp, "\n\n#=========================================\n\n");
fclose($fp);

/* end log */


disconnectDB($condb);
?>