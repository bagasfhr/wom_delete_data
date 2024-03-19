<?php


ini_set( 'max_execution_time', '500' );
ini_set( 'max_file_uploads', '20' );
ini_set( 'max_input_nesting_level', '64' );
ini_set( 'max_input_time', '500' );
ini_set( 'max_input_vars', '50000' );
ini_set( 'memory_limit', '512M' );
ini_set( 'post_max_size', '240M' );

include "../../sysconf/global_func.php";
// include "global_func_telesales.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";

$v_agentid      = get_session("v_agentid");

$condb = connectDB();


$sql = "select a.* from cc_teleupload_param_report_delete_log a
order by a.id desc limit 1 ";
$res = mysqli_query($condb,$sql);
if($rec = mysqli_fetch_array($res)) {
$param_success = $rec["param_success"]; 
$param_failed  = $rec["param_failed"]; 
}
mysqli_free_result($res);

$param_date = DATE("Ymd");
$sqlcl = " SELECT 
a.*
FROM cc_param_delete_filter a 
where a.create_by=$v_agentid ";
$rescl = mysqli_query($condb,$sqlcl);
$tot_data    = mysqli_num_rows($rescl);
while($reccl = mysqli_fetch_array($rescl)){
	$filter_id		= $reccl['id'];
	$bucket_id	= $reccl['bucket_id'];
	$get_nik	= $reccl['get_nik'];
	$get_name	= $reccl['get_name'];
	$get_no	= $reccl['get_no'];
	$status_id	= $reccl['status_id'];
	$date_period	= $reccl['date_period'];
	$get_pic	= $reccl['get_pic'];
}
$get_nik  = "'".str_replace(',', "','", $get_nik)."'";
$get_name = "'".str_replace(',', "','", $get_name)."'";
$get_no   = "'".str_replace(',', "','", $get_no)."'";
$sWhere   = "";
$mode	  = get_param("mode");
$param_fill = 0;
// echo "Nik : $get_nik | Agent : $get_name | Phone : $get_no | date : $date_period | status : $status_id";

$arr_date_period = explode(" - ", $date_period);
// echo "stringxyy $get_nik";
$whereor = " AND ( 1=1 ";

$sqldelall = "DELETE FROM cc_teleupload_data_temp 
      	   WHERE delete_by = '$v_agentid'";
mysqli_query($condb,$sqldelall);

$sWhere2 = "";
if ($bucket_id!='') {
	$sWhere2 = " AND a.bucket_id IN ($bucket_id) ";
}

if ($bucket_id!='') {
	$sWhere .= " AND a.bucket_id IN ($bucket_id) ";
}
if ($get_pic!='') {
	$sWhere .= " AND a.update_by IN ($get_pic) ";
}
if ($date_period!='') {
	$sWhere .= " AND a.update_time >= '$arr_date_period[0] 00:00:00' AND a.update_time <= '$arr_date_period[1] 23:59:59' ";
}

if ($get_nik!='' && $get_nik!="''") {//echo "string1";
	// $whereor .= " OR a.customer_id IN ($get_nik) ";

	//start new tabel
	$param_fill=1;
	$sqlcl = " SELECT 
	      a.*
	      FROM cc_teleupload_data a 
	      where a.customer_id IN ($get_nik) $sWhere";
	$rescl = mysqli_query($condb,$sqlcl);
	$tot_data    = mysqli_num_rows($rescl);
	while($reccl = mysqli_fetch_array($rescl)){
    @extract($reccl,EXTR_OVERWRITE);
	  $sqlall = "INSERT INTO cc_teleupload_data_temp (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time)
		                SELECT b.*, '$v_agentid', now() FROM cc_teleupload_data b
		                WHERE b.id='$id'; ";//echo "$sqlall </br></br>";
      mysqli_query($condb,$sqlall);
    }
    //end new tabel

}
if ($get_name!='' && $get_name!="''") {//echo "string2";
	// $whereor .= " OR a.customer_name IN ($get_name) ";
	//start new tabel
	$param_fill=1;
	$sqlcl = " SELECT 
	      a.*
	      FROM cc_teleupload_data a 
	      where a.customer_name IN ($get_name) $sWhere";
	$rescl = mysqli_query($condb,$sqlcl);
	$tot_data    = mysqli_num_rows($rescl);
	while($reccl = mysqli_fetch_array($rescl)){
    @extract($reccl,EXTR_OVERWRITE);
	  $sqlall = "INSERT INTO cc_teleupload_data_temp (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time)
		                SELECT b.*, '$v_agentid', now() FROM cc_teleupload_data b
		                WHERE b.id='$id' ";
      mysqli_query($condb,$sqlall);
    }
    //end new tabel
}
if ($get_no!='' && $get_no!="''") {//echo "string3";
	// $whereor .= " OR a.mobile1 IN ($get_no) ";
	//start new tabel
	$param_fill=1;
	$sqlcl = " SELECT 
	      a.*
	      FROM cc_teleupload_data a 
	      where a.mobile1 IN ($get_no) $sWhere";
	$rescl = mysqli_query($condb,$sqlcl);
	$tot_data    = mysqli_num_rows($rescl);
	while($reccl = mysqli_fetch_array($rescl)){
    @extract($reccl,EXTR_OVERWRITE);
	  $sqlall = "INSERT INTO cc_teleupload_data_temp (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time)
		                SELECT b.*, '$v_agentid', now() FROM cc_teleupload_data b
		                WHERE b.id='$id' ";
      mysqli_query($condb,$sqlall);
    }
    //end new tabel
}
if ($status_id!='') {//echo "string4";
	// $whereor .= " OR a.last_phonecall IN ($status_id) ";

	//start new tabel
	$param_fill=1;
	$sqlcl = " SELECT 
	      a.*
	      FROM cc_teleupload_data a ,
          cc_teleupload_bucket b
	      where a.last_phonecall IN ($status_id) AND b.bucket_code like '%MFI%' AND a.bucket_id=b.id $sWhere";
	$rescl = mysqli_query($condb,$sqlcl);
	$tot_data    = mysqli_num_rows($rescl);
	while($reccl = mysqli_fetch_array($rescl)){
    @extract($reccl,EXTR_OVERWRITE);
	  $sqlall = "INSERT INTO cc_teleupload_data_temp (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time)
		                SELECT b.*, '$v_agentid', now() FROM cc_teleupload_data b
		                WHERE b.id='$id' ";
      mysqli_query($condb,$sqlall);
    }
    //end new tabel
}

	if ($param_fill==0) {
		//start new tabel
		$sqlcl = " SELECT 
		      a.*
		      FROM cc_teleupload_data a 
		      where 1=1 $sWhere ";//echo "string $sqlcl";
		$rescl = mysqli_query($condb,$sqlcl);
		$tot_data    = mysqli_num_rows($rescl);
		while($reccl = mysqli_fetch_array($rescl)){
	    @extract($reccl,EXTR_OVERWRITE);
		  $sqlall = "INSERT INTO cc_teleupload_data_temp (id,bucket_id, region, kapos_name, order_no, cabang, no_rangka, customer_id, customer_name, item_description, mobile1, mobile2, phone1, office_phone1, otr_price, item_year, monthly_income, monthly_instalment, address_cust, kecamatan, kelurahan, kode_kat, tenor_id, max_past_due_dt, religion, cust_rating, agrmnt_rating, status_kontrak, tanggal_jatuh_tempo, tgl_lahir_konsumen, tgl_lahir_pasangan, gender_konsumen, kepemilikan_rumah, kepemilikan_bpkb, asset_type, asset_temp, label_priority_temp, ketegori_product, asset_name, bpkb_name, otr, tenor, angsuran, sisa_piutang, sisa_tenor, profession_name, profession_category_name, job_position, industry_type_name, jumlah_kontrak_perasset, estimasi_terima_bersih, label_priority, spv_id, agent_id, assign_time, assign_status, last_phoneno, last_phonecall, last_phonecall_sub, last_followup_call, last_followup_by, last_followup_date, last_followup_time, follow_up, remark_desc, flag_assets, create_by, create_time, update_by, update_time,delete_by,delete_time)
			                SELECT b.*, '$v_agentid', now() FROM cc_teleupload_data b
			                WHERE b.id='$id'; ";//echo "string $sqlall </br>";
	      mysqli_query($condb,$sqlall);
	    }
	    //end new tabel
	}
if ($whereor==' AND ( 1=1 ') {
	$whereor = '';
}else{
	$whereor = $whereor." ) ";
	$sWhere  .= $whereor;
}

//file save data
$save_form = "view/teleupload/teleupl_delete_save.php";


	function convMoney($angka){
		$number_string = str_replace("/[^,\d]/g", '', $angka);
	    $split    = explode(".",$number_string);
	    $sisa     = strlen($split[0]) % 3;
	    $rupiah   = substr($split[0], 0, $sisa);
	    
	    if (preg_match_all("/\d{3}/i", substr($split[0], $sisa), $ribuan)) {
	        $separator = $sisa ? '.' : '';
	        $rupiah .= $separator.join('.', $ribuan[0]);
	    }

	    $rupiah = isset( $split[1] ) ? $rupiah . ',' . $split[1] : $rupiah;
	    return $rupiah;
	}


?>
<!-- <input type="hidden" name="agrement_no" id="agrement_no" value="<?php echo $agrement_no;?>"> -->
<input type="hidden" name="cont_phone1" id="cont_phone1" value="<?php echo $mobile_phone1;?>">
<input type="hidden" name="cust_no2" id="cust_no2" value="<?php echo $cust_no;?>">
<input type="hidden" name="cust_name2" id="cust_name2" value="<?php echo $cust_name;?>">
<input type="hidden" name="cust_type2" id="cust_type2" value="<?php echo $cust_type;?>">

<div class="card-bodyxxx">
<div class="table-responsive" style="">
<div style="overflow-x:auto;">
	<table id="table_data_teleupload" class="table table-head-bg-primary ">
		<thead>
			<tr>
				<th style="width:20px"><input type="checkbox" id="checkall"/>ALL</th>
				<th>Bucket Name</th>
				<th>NIK</th>
				<th>Customer Name</th>
				<th>Aggrement No</th>
				<th>PIC Upload</th>
				<th>Upload Date</th>
				<th>Status Call</th>
			</tr>
		</thead>
		<tbody>
				<?php 
					$nos =1;
					$sqlcl = " SELECT 
								a.*,b.bucket_name,c.call_status,d.agent_name
								FROM cc_teleupload_data_temp a 
								left join cc_teleupload_bucket b on a.bucket_id=b.id 
								left join cc_ts_call_status c on a.last_phonecall=c.id
								left join cc_agent_profile d on a.update_by=d.id
								where a.delete_by='$v_agentid'
								ORDER BY a.id ASC ";//echo "string $sqlcl"; 
								//AND MONTH(a.create_time)=MONTH(CURDATE()) AND YEAR(a.create_time)=YEAR(CURDATE()) //NEW AND a.create_name!='' //COLOMN b.call_status_name
					$rescl = mysqli_query($condb,$sqlcl);
					$tot_data    = mysqli_num_rows($rescl);
					while($reccl = mysqli_fetch_array($rescl)){
						$teleupload_id		= $reccl['id'];
						$bucket_name		= $reccl['bucket_name'];
						$customer_id		= $reccl['customer_id'];
						$customer_name		= $reccl['customer_name'];
						$order_no			= $reccl['order_no'];
						$update_by			= $reccl['agent_name'];
						$update_time		= $reccl['update_time'];
						$call_status		= $reccl['call_status'];
						//scope="row"
				?>
						<tr>
							
							<td><input type='checkbox' id='check_assign' name='check_assign' value='<?php echo $teleupload_id; ?>' class='row_bulk' onclick="checkBulk()" ></td>
							<td><?php echo $bucket_name; ?></td>
							<td><?php echo $customer_id; ?></td>
							<td><?php echo $customer_name; ?></td>
							<td><?php echo $order_no; ?></td>
							<td><?php echo $update_by; ?></td>
							<td><?php echo $update_time; ?></td>
							<td><?php echo $call_status; ?></td>
						</tr>
			<?php 
				$nos++;
					}
			?>
			
		</tbody>
	</table>
	</div>
</div>
</div>
<div>
	<input type="text" name="total_data" id="total_data" value="<?php echo $tot_data; ?>" style="margin: 0px 10px; float: right; width:100px" class="form-control" readonly>
	<label style="margin: 0px 10px; float: right;"> Total</label>
	</br></br></br>
	<input type="text" name="total_data_selected" id="total_data_selected" style="margin: 0px 10px; float: right; width:100px" class="form-control" readonly>
	<label style="margin: 0px 10px; float: right;"> Selected Count</label>
	</br></br></br>
	<div class="card-action" style="clear: right;text-align:right;">
									<?php
									 $temp = "&nbsp;&nbsp;";
  									 // $temp .= "<button class=\"btn btn-primary\" id=\"btnDeleteForm\" onclick=\"send_delete2();return false;\" value=\"del\">Delete All</button> &nbsp;";
  									 $temp .= "<button class=\"btn btn-primary\" id=\"btnDeleteForm\" onclick=\"send_delete();return false;\" value=\"del\">Delete</button>";
									echo $temp;
									?>
								</div></br></br></br>
</div>
	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>
	<script>
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script>
	function reload_history_call() {
		
		document.getElementById('tempatLoading').style.display = "block";
		
		var agreeno = document.getElementById('agrement_no').value;
		var params  = "service/wom/get_pay_history.php?agreeno="+agreeno;
		$.ajax({
			url: params,
			type: "POST",
			dataType: 'json',
			success: function(data){ 
                $('#table_history_payment').dataTable().fnPageChange(0);
		document.getElementById('tempatLoading').style.display = "none";
			}
		});
		return false;
	}
	
	var oTable = $('#table_data_teleupload').dataTable({
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
     dom: 'Bfrtip<"top"><"bottom"l><"clear">',
     buttons: [
        {
            extend: 'collection',
            text: 'Action',
            className: 'my-1'
        }],
        "info": false,
        "searching": false,
    	"bProcessing": true,
    	"scrollY": 800,
	    "scroller": {
	        "rowHeight": 800
	    },
	    "scrollCollapse": true,
    	"columnDefs": [{
			"targets": 0,
			"orderable": false
		}]
	});

	$('#table_data_teleupload').on( 'page.dt', function () {
   	setTimeout(function(){checkBulkpage()}, 500);
	});

	$('#example').on( 'length.dt', function ( e, settings, len ) {
   	setTimeout(function(){checkBulkpage()}, 500);
	});
	
	    $('#checkall').click(function(e){
	      	var i = '';
            var param_totaldata     = document.getElementById('total_data_selected').value;
            param_totaldata=0;  
            document.getElementById('total_data_selected').value=param_totaldata;
	    	if (document.getElementById('checkall').checked == true) {
		        var row_bulk   = document.getElementsByName("check_assign");

		        var chklength = row_bulk.length;             
		        for(k=0;k<chklength;k++) {
		       //  // var chk_arr2 = document.getElementById("comid[]").value;

		        	row_bulk[k].checked = true;
		        	row_bulk[k].parentNode.parentNode.style.backgroundColor = "#cfbdd1";
		        	param_totaldata++;
                    document.getElementById('total_data_selected').value=param_totaldata;
		       // alert(chklength);

		        } 
	       
	    	} else if (document.getElementById('checkall').checked == false) {
		       var row_bulk   = document.getElementsByName("check_assign");
		       var chklength = row_bulk.length;      
		       param_totaldata=0;  
               document.getElementById('total_data_selected').value=param_totaldata;     
		       for(k=0;k< chklength;k++) {
		       //    row_bulk[k].checked = false;
		        // alert("NON SUKS");
		         row_bulk[k].checked = false;
		         row_bulk[k].parentNode.parentNode.style.backgroundColor = "transparent";
		       }    
	      }
	      param_totaldata = countBulkDataTable();
          document.getElementById('total_data_selected').value=param_totaldata;
	    });

        function checkBulk(){
            var param_totaldata     = document.getElementById('total_data_selected').value;
            var row_bulk            = document.getElementsByClassName('row_bulk');
            // if (param_totaldata=="") {
              param_totaldata=0;
              document.getElementById('total_data_selected').value=param_totaldata;
            // }
            // alert(row_bulk);
            for (var i = 0; i < row_bulk.length; i++) {
                if(row_bulk[i].checked == true){
                    row_bulk[i].parentNode.parentNode.style.backgroundColor = "#cfbdd1";
                    param_totaldata++;
                    document.getElementById('total_data_selected').value=param_totaldata;
                     // alert("1"+row_bulk.value);
                }
            else{
                    row_bulk[i].parentNode.parentNode.style.backgroundColor = "transparent";
                    // param_totaldata--;
                    // document.getElementById('total_data_selected').value=param_totaldata;
            //          // alert("2"+row_bulk.value);
                }
            }

		    // count checked value start
		    // var oTable = $('#table_data_teleupload').DataTable();
		    // var allPages = oTable.cells( ).nodes( );
      //       var capture2 = "";
		    // values = $(allPages).find('input[id="check_assign"]:checked');
		    // values.each(function( index, element ) {
		    //   capture2 = capture2+ "," + element.value;     
		    // });
		    // var arrcapture = capture2.split(",");
		    // var total_data_select = arrcapture.length-1;
		    // document.getElementById('total_data_selected').value=total_data_select;
		    param_totaldata = countBulkDataTable();
         	document.getElementById('total_data_selected').value=param_totaldata;
        }


    

	   	function send_delete(){
		      var countcek    = $(":checkbox:checked").length;
		      // console.log(method);
		      if(countcek==0){
		        return alert("You must select data first!");
		      }
		      else{

		          swal({
		            title: 'Apakah anda yakin ingin menghapus data?',
		            text: "",
		            type: 'warning',
		            buttons:{
		              confirm: {
		                text : 'Yes',
		                className : 'btn btn-success'
		              },
		              cancel: {
		                visible: true,
		                className: 'btn btn-danger'
		              }
		            }
		          }).then((Save) => {
		            if (Save) {
    					// document.getElementById('tempatLoading').style.display = "block";
					   document.getElementById('tempatLoading').style.display = "block";
		               var data = new FormData();
		               // var form_data = $('#frmDataDet').serializeArray();
		               // $.each(form_data, function (key, input) {
		               //    data.append(input.name, input.value);
		               //    // console.log(input.name+' - '+input.value);
		               // });

		                  // count checked value start
		                  var oTable = $('#table_data_teleupload').DataTable();
		                  var allPages = oTable.cells( ).nodes( );
		                  var capture = "";
		                  values = $(allPages).find('input[id="check_assign"]:checked');
		                  values.each(function( index, element ) {
		                    capture = capture + "," + element.value;     
		                  });
		                  capture = "0" + capture;
		                  // count checked value end
		                  data.append('iddeb', capture);

		               data.append('key', 'value'); 

						//start new param		    
						var urldeleteparam  = "view/teleupload/teleupload_delete_param2_save.php";
						$.ajax({
								url: urldeleteparam,
								type: "POST",
						        data: data,
								processData: false,
								contentType: false,
								// dataType: 'json',
								success: function(data1){ 
									// document.getElementById('tempatLoading').style.display = "none";

									//start new attach
								    var param_agent = "<?php echo $v_agentid; ?>";
									var urlupdatepolo  = "view/report/convert/convert_wom2.php?capture="+capture+"&agent_id="+param_agent;
									// $.ajax({
									// 		url: urlupdatepolo,
									// 		type: "POST",
									// 		dataType: 'json',
									// 		success: function(data2){ 
									// 			// document.getElementById('tempatLoading').style.display = "none";
									// 			var datares = data.responseMessage; 
									// 			var datares2 = data.errorMessage;
												// if (data2=='Success') {
													// alert(data);	  

									               $.ajax({
									                    url: "<?php echo $save_form; ?>",
									                    type: "post",
									                    data: data,
									                  processData: false,
									                  contentType: false,
									                    success: function(d) {
									                    	document.getElementById('tempatLoading').style.display = "none";
									                      var warn = d;

									                      warn = warn.split("|");
									                      if(warn[0]=="Success!") {
									                        var vtype = "Success";
									                        var text  = "Success!\nData: "+warn[1]+'/'+warn[2];
									                      } else {
									                        var vtype = "error";  
									                        var text  = warn[0];
									                      }
									                      // console.log(text);
									                      $('#table_data_teleupload').dataTable().fnPageChange(0);
									     //                  


									                      // swal({ title: "Save Data!", type: vtype,  text: text,   timer: 5000,   showConfirmButton: true });
									                      swal({
												            title: 'Delete Data Upload',
												            text: text,
												            type: 'Success',
												            buttons:{
												              confirm: {
												                text : 'OK',
												                className : 'btn btn-success'
												              }
												            }
												          }).then((Save) => {
												            if (Save) {
																	// viewFile("");
																	// window.open("public/teleupload/report/02Nov2023.pdf");
																	//start SFTP
															    	var param_report = "<?php echo $param_success; ?>";
															    	var param_report_fail = "<?php echo $param_failed; ?>";
																	if (param_report=='1' || param_report_fail=='1') {
																	var urlupdatepolo  = "service/teleupload/sftp_teleupload.php?v_agentid="+param_agent;
																	$.ajax({
																			url: urlupdatepolo,
																			type: "POST",
																			dataType: 'json',
																			success: function(data){ 
																				// document.getElementById('tempatLoading').style.display = "none";
																				var datares = data.responseMessage; 
																				var datares2 = data.errorMessage;
																				if (datares=='SUCCESS') {
																					// alert(datares);	            					
																	 																	 
																				}else{
																					// alert(datares);
																				}																		
																			}
																	});
																	}
													    // 			//end SFTP
												                    var param_date = "<?php echo $param_date; ?>";
																	var urlreport = "public/teleupload/report/"+param_date+".pdf";
																	var param_report = "<?php echo $param_success; ?>";
																	var param_report_fail = "<?php echo $param_failed; ?>";
																	if (param_report=='1') {
																		// var wopen2=window.open(urlreport,"report_pdf","width=600,height=500, toolbar=no,scrollbars=yes,resizable=yes");
																  //   	wopen2.focus();
																  		if (warn[1]!='') {
																  			window.open('view/report/his_teleupload_delete_succ_xlsx.php?v_agentid='+param_agent, "mywindow1", "width=1200,height=1200");
																  		}
																        
																	}
																	if (param_report_fail=='1') {
																		// var wopen2=window.open(urlreport,"report_pdf","width=600,height=500, toolbar=no,scrollbars=yes,resizable=yes");
																  //   	wopen2.focus();
																  		if (warn[2]!='') {
																        	window.open('view/report/his_teleupload_delete_fail_xlsx.php?v_agentid='+param_agent, "mywindow2", "width=1200,height=1200");
																        }
																	}
																	
												            		$('#loadpenawarandata').load('view/teleupload/get_cust_call3.php', function( response, status, xhr ) {
																	}); 
															        
																}
															})
									                      if(warn=="Success") {
									                        table = $('#table_data_teleupload').DataTable();
									                        
									                        // method = $('#assignment_method').val();
									                      } 
									                      // setTimeout('location.reload(true);', 1500);
									                    }
									                });          					
									 																	 
												// }else{
												// 	// alert(datares);
												// }//sukses attach																		
											// } //data attach
									// });//ajax attach
					    			//end new attach																		
								}
						});
						//end new param
		               
		              
		            } else {
		              swal.close();
		            }
		          });
		      }
        }


        function send_delete2(){
		    
		      var countcek    = '1';
		      // console.log(method);
		      if(countcek==0){
		        return alert("You must select data first!");
		      }
		      else{

		          swal({
		            title: 'Apakah anda yakin ingin menghapus data?',
		            text: "",
		            type: 'warning',
		            buttons:{
		              confirm: {
		                text : 'Yes',
		                className : 'btn btn-success'
		              },
		              cancel: {
		                visible: true,
		                className: 'btn btn-danger'
		              }
		            }
		          }).then((Save) => {
		            if (Save) {
    					// document.getElementById('tempatLoading').style.display = "block";
					   document.getElementById('tempatLoading').style.display = "block";
		               var data = new FormData();
		               // var form_data = $('#frmDataDet').serializeArray();
		               // $.each(form_data, function (key, input) {
		               //    data.append(input.name, input.value);
		               //    // console.log(input.name+' - '+input.value);
		               // });

		                  // count checked value start
		                  var oTable = $('#table_data_teleupload').DataTable();
		                  var allPages = oTable.cells( ).nodes( );
		                  var capture = "";
		                  values = $(allPages).find('input[id="check_assign"]');
		                  values.each(function( index, element ) {
		                    capture = capture + "," + element.value;     
		                  });
		                  capture = "0" + capture;
		                  // count checked value end
		                  data.append('iddeb', capture);

		               data.append('key', 'value'); 

						//start new param		    
						var urldeleteparam  = "view/teleupload/teleupload_delete_param2_save.php";
						$.ajax({
								url: urldeleteparam,
								type: "POST",
						        data: data,
								processData: false,
								contentType: false,
								// dataType: 'json',
								success: function(data1){ 
									// document.getElementById('tempatLoading').style.display = "none";

									//start new attach
								    var param_agent = "<?php echo $v_agentid; ?>";
									var urlupdatepolo  = "view/report/convert/convert_wom2.php?capture="+capture+"&agent_id="+param_agent;
									// $.ajax({
									// 		url: urlupdatepolo,
									// 		type: "POST",
									// 		dataType: 'json',
									// 		success: function(data2){ 
									// 			// document.getElementById('tempatLoading').style.display = "none";
									// 			var datares = data.responseMessage; 
									// 			var datares2 = data.errorMessage;
												// if (data2=='Success') {
													// alert(data);	  

									               $.ajax({
									                    url: "<?php echo $save_form; ?>",
									                    type: "post",
									                    data: data,
									                  processData: false,
									                  contentType: false,
									                    success: function(d) {
									                    	document.getElementById('tempatLoading').style.display = "none";
									                      var warn = d;

									                      warn = warn.split("|");
									                      if(warn[0]=="Success!") {
									                        var vtype = "Success";
									                        var text  = "Success!\nData: "+warn[1]+'/'+warn[2];
									                      } else {
									                        var vtype = "error";  
									                        var text  = warn[0];
									                      }
									                      // console.log(text);
									                      $('#table_data_teleupload').dataTable().fnPageChange(0);
									     //                  


									                      // swal({ title: "Save Data!", type: vtype,  text: text,   timer: 5000,   showConfirmButton: true });
									                      swal({
												            title: 'Delete Data Upload',
												            text: text,
												            type: 'Success',
												            buttons:{
												              confirm: {
												                text : 'OK',
												                className : 'btn btn-success'
												              }
												            }
												          }).then((Save) => {
												            if (Save) {
																	// viewFile("");
																	// window.open("public/teleupload/report/02Nov2023.pdf");
																	//start SFTP
															    	var param_report = "<?php echo $param_success; ?>";
															    	var param_report_fail = "<?php echo $param_failed; ?>";
																	if (param_report=='1' || param_report_fail=='1') {
																	var urlupdatepolo  = "service/teleupload/sftp_teleupload.php?v_agentid="+param_agent;
																	$.ajax({
																			url: urlupdatepolo,
																			type: "POST",
																			dataType: 'json',
																			success: function(data){ 
																				// document.getElementById('tempatLoading').style.display = "none";
																				var datares = data.responseMessage; 
																				var datares2 = data.errorMessage;
																				if (datares=='SUCCESS') {
																					// alert(datares);	            					
																	 																	 
																				}else{
																					// alert(datares);
																				}																		
																			}
																	});
																	}
													    // 			//end SFTP
												                    var param_date = "<?php echo $param_date; ?>";
																	var urlreport = "public/teleupload/report/"+param_date+".pdf";
																	var param_report = "<?php echo $param_success; ?>";
																	var param_report_fail = "<?php echo $param_failed; ?>";
																	if (param_report=='1') {
																		// var wopen2=window.open(urlreport,"report_pdf","width=600,height=500, toolbar=no,scrollbars=yes,resizable=yes");
																  //   	wopen2.focus();
																  		if (warn[1]!='') {
																  			window.open('view/report/his_teleupload_delete_succ_xlsx.php?v_agentid='+param_agent, "mywindow1", "width=1200,height=1200");
																  		}
																        
																	}
																	if (param_report_fail=='1') {
																		// var wopen2=window.open(urlreport,"report_pdf","width=600,height=500, toolbar=no,scrollbars=yes,resizable=yes");
																  //   	wopen2.focus();
																  		if (warn[2]!='') {
																        	window.open('view/report/his_teleupload_delete_fail_xlsx.php?v_agentid='+param_agent, "mywindow2", "width=1200,height=1200");
																        }
																	}
																	
												            		$('#loadpenawarandata').load('view/teleupload/get_cust_call3.php', function( response, status, xhr ) {
																	}); 
															        
																}
															})
									                      if(warn=="Success") {
									                        table = $('#table_data_teleupload').DataTable();
									                        
									                        // method = $('#assignment_method').val();
									                      } 
									                      // setTimeout('location.reload(true);', 1500);
									                    }
									                });          					
									 																	 
												// }else{
												// 	// alert(datares);
												// }//sukses attach																		
											// } //data attach
									// });//ajax attach
					    			//end new attach																		
								}
						});
						//end new param
		               
		              
		            } else {
		              swal.close();
		            }
		          });
		      }
		    return false;
        }
	</script>

<script type="text/javascript">
	function viewFile(filename){
		var urlpopup = "view/teleupload/teleupl_delete_view_file.php?f="+encodeURI(btoa(filename));
		var wopen2=window.open(urlpopup,"","width=1200,height=900, toolbar=no,scrollbars=yes,resizable=yes");
	}

	function countBulkDataTable(){
		var oTable = $('#table_data_teleupload').DataTable();
      var allPages = oTable.cells( ).nodes( );
		chklength = $(allPages).find('input[id="check_assign"]').length
      totaldata=0;
      // console.log(chklength)
		// for(k=0;k<chklength;k++) {
		// 	if($(allPages[k]).find('input[id="check_assign"]').is(":checked")){
  //             totaldata++;
  //        }else{
  //        	console.log("not cheked");
  //        }
			// document.getElementById('total_data_selected').value=param_totaldata;

		// } 
		values = $(allPages).find('input[id="check_assign"]:checked');
	    values.each(function( index, element ) {
              totaldata++;
	      // capture2 = capture2+ "," + element.value;     
	    });
		// console.log(totaldata);
   	return totaldata;
	}

	function checkBulkpage(){
		var row_bulk   = document.getElementsByName("check_assign");
		var chklength = row_bulk.length;             
		param_totaldata=0;
		param_totaldata_check=0;
		for(k=0;k<chklength;k++) {
			param_totaldata++;
			if(row_bulk[i].checked == true){
				param_totaldata_check++;
			}
		}

		// console.log(param_totaldata," - ",param_totaldata_check)
		if (param_totaldata==param_totaldata_check) {
			$("#checkall").prop("checked", true);
		}else{
			console.log("tidak masuk");
			$("#checkall").prop("checked", false);
		}
	}	
</script>
<?php
disconnectDB($condb);
?>

