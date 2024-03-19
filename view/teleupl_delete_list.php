<?php
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";
include "global_func_teleupload.php";

$condb = connectDB();
$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");
$v_agentgroup   = get_session("v_agentgroup");

$iddet 			= $library['iddet'];

$ffolder		= $library['folder'];
$fmenu_link		= $library['menu_link'];
$menu_label		= $library['title'];
$fdescription	= $library['description'];
$fmenu_id		= $library['menu_id'];
$ficon			= $library['icon'];
$fiddet			= $library['iddet'];
$fblist			= $library['blist'];



$fmenu_link_back = "teleupload_test_data_list";
$menu_linkdet	= "cust_contact_det";

	$sqlv = "SELECT * FROM cc_teleupload_data_det a where a.update_by='$v_agentid'
			ORDER BY a.flag_upload DESC LIMIT 1";//echo "## $sqlv";
	$resv = mysqli_query($condb,$sqlv);
	while($recv = mysqli_fetch_array($resv)){
		$flag_upload			= $recv['flag_upload'];
		$parm_value[]		= $recv['parm_value'];
	}


	$sqlv = "SELECT a.create_time FROM cc_teleupload_data_det a 
			where a.update_by='$v_agentid' ORDER BY a.create_time DESC LIMIT 1";//echo "## $sqlv";
	$resv = mysqli_query($condb,$sqlv);
	if($recv = mysqli_fetch_array($resv)){
		$param_create_time			= $recv['create_time'];
	}

	//New
	$sqlv = "SELECT count(*) AS param_new FROM cc_teleupload_data_det a 
			where a.update_by='$v_agentid' AND a.flag_upload='$fblist' AND a.flag_status='1' AND a.create_time='$param_create_time'";//echo "## $sqlv";
	$resv = mysqli_query($condb,$sqlv);
	if($recv = mysqli_fetch_array($resv)){
		$param_new			= $recv['param_new'];
	}

	//Update
	$sqlv = "SELECT count(*) AS param_update FROM cc_teleupload_data_det a 
			where a.update_by='$v_agentid' AND a.flag_upload='$fblist' AND a.flag_status='3' AND a.create_time='$param_create_time'";//echo "## $sqlv";
	$resv = mysqli_query($condb,$sqlv);
	if($recv = mysqli_fetch_array($resv)){
		$param_update			= $recv['param_update'];
	}

	//Error
	$sqlv = "SELECT count(*) AS param_error FROM cc_teleupload_data_det a 
			where a.update_by='$v_agentid' AND a.flag_upload='$fblist' AND a.flag_status='2' AND a.create_time='$param_create_time'";//echo "## $sqlv";
	$resv = mysqli_query($condb,$sqlv);
	if($recv = mysqli_fetch_array($resv)){
		$param_error			= $recv['param_error'];
	}


$abandon   = $parm_value[0];
$answered  = $parm_value[1];
$idsla	   = $parm_value[2];
$idcal	   = $parm_value[3];
$sl_target = $parm_value[4];


//file save data
$save_form = "view/teleupload/teleupload_test_data_save.php";
$get_data = "view/teleupload/get_data_value.php";

if($iddet  == "") {
	$desc_iddet = "Detail";
}else{
	$desc_iddet = "View";
}


?>
<link rel="stylesheet" type="text/css" href="assets/css/pickers/daterange/daterangepicker.css">
<form name="frmDataDet" id="frmDataDet" method="POST">
<input type="hidden" name="iddet" id="iddet" value="<?php echo $iddet;?>">
<input type="hidden" name="param_check" id="param_check" value="<?php echo $param_check;?>">
<input type="hidden" name="flag_upload" id="flag_upload" value="<?php echo $flag_upload;?>">

<div class="page-inner">
	<div class="page-header"  style="margin-bottom:0px;margin-top:-15px;padding-left:0px;padding:0px;margin-left:-20px;">
		<ul class="breadcrumbs" style="border-left:0px;margin:0px;">
			<li class="nav-home">
				<a href="index.php">
					<i class="fas fa-home"></i>
				</a>
			</li>
			<li class="separator">
				<i class="fas fa-chevron-right"></i>
			</li>
			<?php
				$menu_tree = explode("|", $library['page']);
				for ($i=0; $i <count($menu_tree) ; $i++) { 
					if ($i != 0) {
						echo "<li class=\"separator\"><i class=\"fas fa-chevron-right\"></i></li>";
					}
					echo "<li class=\"nav-item\">".$menu_tree[$i]."</li>";;
				}
				echo "<li class=\"separator\"><i class=\"fas fa-chevron-right\"></i></li>";
				echo "<li class=\"nav-item\">".$desc_iddet."</li>";;				
			?>
		</ul>
	</div>
	<div style="height:100%;top:0px;left:0px;position: fixed;z-index: 999999;text-align: center;width:100%;display: none" id="tempatLoading">
	 <div style="width:400px;margin:auto;margin-top:200px;padding:10px;">
	 	 <img src="assets/img/elyphsoft.gif" width="140px" style="border: 0px;border-radius: 4px;padding: 1px;border-radius:150px">
	  	<h1 style="font-weight:bold;color: white; text-shadow: -2px -2px 0 <?php echo $dominant_mastercolor_darker ?>, 2px -2px 0 <?php echo $dominant_mastercolor_darker ?>, -2px 2px 0 <?php echo $dominant_mastercolor_darker ?>, 2px 2px 0 <?php echo $dominant_mastercolor_darker ?>;">Please Wait</h1>
	  	<h2 style="font-weight:bold;color: white; text-shadow: -2px -2px 0 <?php echo $dominant_mastercolor_darker ?>, 2px -2px 0 <?php echo $dominant_mastercolor_darker ?>, -2px 2px 0 <?php echo $dominant_mastercolor_darker ?>, 2px 2px 0 <?php echo $dominant_mastercolor_darker ?>;">While We're Parsing Your Data</h2>
	 </div>
	</div>
	<div class="content" style="margin-top: 10px;">
		<div class="row">

			<!-- table 3 start -->
		<div class="col-md-12">
			<div class="card">
				<div style="margin:10px 10px 10px 10px;">
					<div>

						
							<div class="form-body">		
								<?php
								
									$txttitle	= "Delete Data Upload";
		                    		$icofrm	  = "fas fa-list-ul";
		                    		echo title_form_det($txttitle,$icofrm);
									?>

                <div class="row">
                  <div class="col-md-6">
                    <?php                 
                      $x           = 0;
									
					  $temp  = "<select name=\"bucket_id\" id=\"bucket_id\" class=\"select2 form-control\" required>";//required='required'
          			  $temp .= "<option value=''>--Selected--</option>";
          			  	$sql_whr="";
          			  if ($v_agentlevel<3) {
          			  	$sql_whr=" AND a.spv_id = '".$v_agentid."' ";
          			  }
	  				  $sql_str   = " SELECT a.id, a.bucket_name FROM cc_teleupload_bucket a WHERE a.bucket_code LIKE '%MFI%' ";
    				  $sql_res   = mysqli_query($condb,$sql_str);
    				  while($sql_rec = mysqli_fetch_array($sql_res)) {
		       		  	if ($sql_rec['id'] == $bucket_id) {
		          	  		$temp .= "<option value=".$sql_rec['id']." selected>".$sql_rec['bucket_name']."</option>";
		          
		       		  	} else {
		          	  		$temp .= "<option value=".$sql_rec['id'].">".$sql_rec['bucket_name']."</option>";
		       		  	}
    				  }
	 				  $temp .= "</select>";
    
                      $txtlabel[$x]      = "Bucket";
                      $bodycontent[$x]   = $temp;
                      $x++;

                      $txtlabel[$x]      = "PIC Upload ";
                      $bodycontent[$x]   = get_select_pic_upload($condb, "pic_upload", "pic_upload[]", "", $pic_upload);
                      $x++;

                      $txtlabel[$x]      = "Upload Date ";
                      $bodycontent[$x]   = '<input type="text" class="form-control" id="date_period" name="date_period" >'.'</br></br></br></br>
									<button id="btn_view" type="button" class="btn btn-primary"> View</button> <button id="reset" type="button" class="btn btn-primary"> Reset</button>
									'.'
									</br>
									<div id="filelist"></div><div id="container"></div>';
                      $x++;
    
                      echo label_form_det($txtlabel,$bodycontent,$x);

                    ?>                  
                  </div>
                  <div class="col-md-6">
                    <?php 
                      $x           = 0;

                      $txtlabel[$x]      = "NIK ";
                      $bodycontent[$x]   = get_select_nik($condb, "get_nik", "get_nik[]", "required", $get_nik);
                      $x++;

                      $txtlabel[$x]      = "Name ";
                      $bodycontent[$x]   = get_select_name($condb, "get_name", "get_name[]", "required", $get_name);
                      $x++;

                      $txtlabel[$x]      = "No Telephone ";
                      $bodycontent[$x]   = get_select_phone($condb, "get_no", "get_no[]", "required", $get_no);
                      $x++;
									
					  $temp  = "<select name=\"status_id\" id=\"status_id\" class=\"select2 form-control\" >";//required='required'
          			  $temp .= "<option value=''>--Selected--</option>";
          			  	$sql_whr="";
          			  if ($v_agentlevel<3) {
          			  	$sql_whr=" AND a.spv_id = '".$v_agentid."' ";
          			  }
          			  $last_phonecall = '0';
					  $sql_str1 = "SELECT a.last_phonecall
                				FROM cc_teleupload_data a,
                				cc_teleupload_bucket b 
                				where a.mobile1 != '' AND b.bucket_code like '%MFI%' AND a.bucket_id=b.id
                				AND a.last_phonecall > 0
                				GROUP BY a.last_phonecall";
					  $sql_res1  = execSQL($conDB, $sql_str1);
					  while ($sql_rec1 = mysqli_fetch_array($sql_res1)) {
					    $last_phonecall .= ','.$sql_rec1['last_phonecall'];
					  }
	  				  $sql_str   = " SELECT a.id, a.call_status FROM cc_ts_call_status a 
	  				  				WHERE a.id IN ($last_phonecall)";
    				  $sql_res   = mysqli_query($condb,$sql_str);
    				  while($sql_rec = mysqli_fetch_array($sql_res)) {
		       		  	if ($sql_rec['id'] == $bucket_id) {
		          	  		$temp .= "<option value=".$sql_rec['id']." selected>".$sql_rec['call_status']."</option>";
		          
		       		  	} else {
		          	  		$temp .= "<option value=".$sql_rec['id'].">".$sql_rec['call_status']."</option>";
		       		  	}
    				  }
	 				  $temp .= "</select>";
    
                      $txtlabel[$x]      = "Status Call";
                      $bodycontent[$x]   = $temp;
                      $x++;


                      echo label_form_det($txtlabel,$bodycontent,$x);
                    ?>
                  </div>
                </div>
              
									<?php

		                    		echo "<div id='assignto_agnt' class='form-check' style='text-align: left; display:none;'>";
		                    		
		                    			//   if ($fblist>0) {
		                    			  	
			                    			$x 				   = 0;
			                    			$txtlabel[$x]      = "Summary";
			                    			$bodycontent[$x]   = "<table>
			                    								  	<tr>
			                    								  		<td style=\"text-align:left\">New</td>
			                    								  		<td><div id='div_success'></div></td>
			                    								  	</tr>
			                    								  	<tr>
			                    								  		<td style=\"text-align:left\">Update</td>
			                    								  		<td><div id='div_update'></div></td>
			                    								  	</tr>
			                    								  	<tr>
			                    								  		<td style=\"text-align:left\">Error</td>
			                    								  		<td><div id='div_error'></div></td>
			                    								  	</tr>
			                    								  </table>" ;
					                        $x++;

				                    		echo label_form_det($txtlabel,$bodycontent,$x);
			                    		// }
										echo "</div>";
	                    		
								?>	
								<div id="loadpenawarandata" class="col-md-12" ></div>

							</div>


					</div>
				</div>
			</div>
		</div>
		<!-- table 3 end -->		
		
		</div>
		
		
		
		
	</div>
</div>


</form>
<?php
disconnectDB($condb);
?>

<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/upload/plupload.full.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	
	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
	<!-- Bootstrap Toggle -->
	<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Select2 -->
	<script src="assets/js/plugin/select2/select2.full.min.js"></script>
	<!-- jQuery Validation -->
	<script src="assets/js/plugin/jquery.validate/jquery.validate.min.js"></script>
	<!-- Bootstrap Tagsinput -->
	<script src="assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>
	<script src="assets/js/setting.js"></script>

	<script src="assets/js/plugin/moment/moment.min.js"></script>
	<script src="assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/plugin/pickers/daterange/daterangepicker.js" type="text/javascript"></script>
	
	<script type="text/javascript">
// Custom example logic

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass an id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : 'view/teleupload/upload.php',
	flash_swf_url : '../js/Moxie.swf',
	silverlight_xap_url : '../js/Moxie.xap',
	multipart : false,
	multi_selection: false,
	multipart_params : {
        "act" : "upload"
    },
	filters : {
		max_file_size : '100mb',
		mime_types: [
			{title : "Excel files", extensions : "xlsx"}
		]
	},

	init: {
		PostInit: function() {
			// document.getElementById('filelist').innerHTML = '';
			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},

		// FilesAdded: function(up, files) {
		// 	var i = up.files.length,
		// 	maxCountError = false;
		// 	plupload.each(files, function (file) {

		// 		if(uploader.settings.max_file_count && i >= uploader.settings.max_file_count){
		// 			maxCountError = true;
		// 			// setTimeout(function(){ up.removeFile(file); }, 50);
		// 		} else {
		// 			document.getElementById('filelist').innerHTML = '';
		// 			// setTimeout(function(){ up.removeFile(file); }, 50);
		// 			// up.removeFile('20220105141511%7CTemplate Uplaod - CRM Telemarketing (asap) - jatim.xlsx');
		// 			document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
		// 		}

		// 		i++;
		// 	});

		// 	if(maxCountError){ 
		// 		alert('Too many files uploaded, do something');
		// 		// 
		// 	}
		// },

		BeforeUpload: function (up, files) {
			// destroy the uploader and init a new one
			document.getElementById('tempatLoading').style.display = "block";
			var parm = document.getElementById('bucket_id').value;
			uploader.settings.multipart_params.bucket_id = parm;
		},

		FileUploaded: function (up, file, res) {
			// console.log(res);
			var res1 = res.response.replace('"{', '{').replace('}"', '}');
			var objResponse = JSON.parse(res1);
			var status = objResponse.status;
			var success = objResponse.totl_success;
			var update = objResponse.totl_update;
			var error = objResponse.totl_error;

			if(status == "success") { //alert(objResponse.status);
				document.getElementById('assignto_agnt').style.display = 'block';
				document.getElementById('div_success').innerHTML = success;
				document.getElementById('div_update').innerHTML = update;
				document.getElementById('div_error').innerHTML = error;
			} else { alert(objResponse.status);
				document.getElementById('assignto_agnt').style.display = 'none';
			}
			// alert(objResponse.success);
		},

		UploadComplete: function (up, files) {
			// destroy the uploader and init a new one
			document.getElementById('tempatLoading').style.display = "none";
			var warn = "Success!";
			if(warn=="Success!") {
				var vtype = "success";
			} else {
				var vtype = "error";	
			}
			swal({ title: "Save Data!", type: vtype,  text: warn,   timer: 4000,   showConfirmButton: false });
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});

uploader.init();

</script>

	<script type="text/javascript">
		$('.date_dist').datetimepicker({
			format: 'YYYY-MM-DD',
		});

		$('.time_dist').datetimepicker({
			format: 'HH:mm:ss',
		});


		$('#date_period').daterangepicker({
		    locale: {
		      format: 'YYYY-MM-DD'
		    }
		    // ,
		    // 	startDate: '<?php //echo $startdate;?>',
		    // 	endDate: '<?php //echo $enddate;?>'
		});

	</script>

    <script lang="javascript">
    	//document.getElementById('assignto_agnt').parentNode.parentNode.style.display = "none";
    	document.getElementById('assignto_agnt').style.display = 'none';
    	function assignPilih (distribusi){
			  if (distribusi == 1) {
				document.getElementById('assignto_bckt').style.display = 'block';
				document.getElementById('assignto_agnt').style.display = 'none';
				$('#agt_agent_wskill').html("");
				document.getElementById('agentsearch').style.display = 'none';

				document.getElementById("agt_skill_id").value = "";
			  } else if (distribusi == 2) {
				document.getElementById('assignto_bckt').style.display = 'none';
    			document.getElementById('assignto_agnt').style.display = 'block';
				$('#bck_agent_wskill').html("");
				document.getElementById("bck_skill_id").value = "";
				document.getElementById('agentsearch2').style.display = 'none';
			  } else {
				document.getElementById('assignto_bckt').style.display = 'none';
    			document.getElementById('assignto_agnt').style.display = 'none';
			  }

		 };

		 $("#agt_skill_id").change(function() {
			var skill = document.getElementById("agt_skill_id").value;
            var grpid = "<?php echo $v_agentgroup;?>";
		 	if(skill !== "") {
			  var dStr  = "v=wskill&skillid="+skill+"&grpid="+grpid;

				$.ajax({ 
				      type: 'POST', 
				      url: "<?php echo $get_data; ?>",
				      data: dStr,
				      dataType:'json', 
				      success: function (data) {
				      	$('#agt_agent_wskill').html(data.arr_agent);
				      	document.getElementById('agentsearch').style.display = 'block';
				      }
				  });
			} else {
				$('#agt_agent_wskill').html("");
				document.getElementById('agentsearch').style.display = 'none';
			}
		 });

		 $("#bck_skill_id").change(function() {
			var skill = document.getElementById("bck_skill_id").value;
            var grpid = "<?php echo $v_agentgroup;?>";
		 	if(skill !== "") { 
			  var dStr  = "v=wskill&skillid="+skill+"&grpid="+grpid;

				$.ajax({ 
				      type: 'POST', 
				      url: "<?php echo $get_data; ?>",
				      data: dStr,
				      dataType:'json', 
				      success: function (data) {
				      	$('#bck_agent_wskill').html(data.arr_agent);
						document.getElementById('agentsearch2').style.display = 'block';
				      }
				  });
			} else {
				$('#bck_agent_wskill').html("");
				document.getElementById('agentsearch2').style.display = 'none';
			}
		 });

		 // Listen for click on toggle checkbox

		 function checkstatus(){
		 	var totalCheckboxes = $('input[name="fomni_id[]"]').length;
		 	var totalCheck = parseInt(totalCheckboxes);
		 	var inputElems = $('input[name="fomni_id[]"]');

		 	if(document.getElementById("fomni_0").checked){
		 		$('input[name="fomni_id[]"]').prop("checked", true);
		 		var listcek = "";
		 		for (var i=0; i<inputElems.length; i++) { 
		 			if (inputElems[i].type == "checkbox" && inputElems[i].checked == true){

		 				if(listcek == "") {
		 					listcek += inputElems[i].value;
		 				} else {
		 					listcek += "|"+inputElems[i].value;

		 				}
		 			}
		 		}
		 		document.getElementById("param_check").value = listcek;
			} else {
		 		$('input[name="fomni_id[]"]').prop("checked", false);
		 		document.getElementById("param_check").value = "";
			}
		 }

		 function check_insert(val_check){ 
		 	var param_check = document.getElementById("param_check").value;
		 	if (param_check == "") {
		 		param_check = val_check;
		 		var param_check = document.getElementById("param_check").value=param_check;
		 	}else{
		 		var cari = param_check.search(val_check);
		 		
		 		if (cari == "-1") {	
			 		param_check = param_check + "|" + val_check;
			 		param_check = param_check.replace("||", "|");
			 		var param_check = document.getElementById("param_check").value=param_check;
		 		}else {//alert(cari);
			 		param_check = param_check.replace(val_check, "");
			 		param_check = param_check.replace("||", "|");
			 		var param_check = document.getElementById("param_check").value=param_check;
		 		}
		 	}

		 	var dStr  = "v=wsearch&searchid="+val_check+"&paramcheck="+param_check;

		 	$.ajax({ 
				      type: 'POST', 
				      url: "<?php echo $get_data; ?>",
				      data: dStr,
				      dataType:'json', 
				      success: function (data) {

				      }
				  });

		 }

		 function search_funct(val_search){
		 	var paramcheck2 = document.getElementById("param_check").value;
		 	var skill = document.getElementById("agt_skill_id").value;
		 	document.getElementById("param_search").value = val_search;
		 	var grpid = "<?php echo $v_agentgroup;?>";

		 	var dStr  = "v=wskill&skillid="+skill+"&val_search="+val_search+"&paramcheck="+paramcheck2+"&grpid="+grpid;
		 	
				$.ajax({ 
				      type: 'POST', 
				      url: "<?php echo $get_data; ?>",
				      data: dStr,
				      dataType:'json', 
				      success: function (data) {
				      	$('#agt_agent_wskill').html(data.arr_agent);
				      }
				  });
		 }

		function search_funct2(val_search){
		 	var paramcheck3 = document.getElementById("param_check").value;
		 	var skill = document.getElementById("bck_skill_id").value;
		 	document.getElementById("param_search").value = val_search;

		 	var dStr  = "v=wskill&skillid="+skill+"&val_search="+val_search+"&paramcheck="+paramcheck3;
		 	
				$.ajax({ 
				      type: 'POST', 
				      url: "<?php echo $get_data; ?>",
				      data: dStr,
				      dataType:'json', 
				      success: function (data) {
				      	$('#bck_agent_wskill').html(data.arr_agent);
				      }
				  });
		 }

	function check_form() {
	    var stt = 0;
	    stt = $('input[name="fomni_id[]"]:checked').length;
	    
	    return stt;
	}

	var form = $( "#frmDataDet" );
	form.validate();

    $("#btn_view").click(function(){ 
    	var fvalid = form.valid();
    	var param_warm = "Please fill in all mandatory";
		var oks = check_form();
		var pic_upload = document.getElementById('pic_upload').value;
		var get_nik = document.getElementById('get_nik').value;
		var get_name = document.getElementById('get_name').value;
		var get_no = document.getElementById('get_no').value;
		var date_period = document.getElementById('date_period').value;
		date_period = date_period.replace(" ", "|");
		// alert(date_period);
		var status_id = document.getElementById('status_id').value;

    	if(fvalid==true){
    	document.getElementById('tempatLoading').style.display = "block";
		var data = new FormData();
		var form_data = $('#frmDataDet').serializeArray();
		$.each(form_data, function (key, input) {
		   data.append(input.name, input.value);
		   // console.log(input.name+' - '+input.value);
		});

		data.append('key', 'value'); 

		//start new param		    
		var urldeleteparam  = "view/teleupload/teleupload_delete_param_save.php";
		$.ajax({
				url: urldeleteparam,
				type: "POST",
		        data: data,
				processData: false,
				contentType: false,
				// dataType: 'json',
				success: function(data){ 
					// document.getElementById('tempatLoading').style.display = "none";
					var datares = data.responseMessage; 
					var datares2 = data.errorMessage;
					if (data=='Success') {
						// alert(datares);	

    					    $('#loadpenawarandata').load('view/teleupload/get_cust_call3.php', function( response, status, xhr ) {
								// alert('xxx');
								document.getElementById('tempatLoading').style.display = "none";
							});            					
						 																	 
					}else{
						// alert(datares);
					}																		
				}
		});
		//end new param

        // return false;
        if (get_nik !='') {
        	var params  = "view/teleupload/get_teleupload_data.php";
			$.ajax({
				url: params,
				type: "POST",
				dataType: 'json',
				success: function(data){ 
	                $('#table_data_teleupload').dataTable().fnPageChange(0);
			// document.getElementById('tempatLoading').style.display = "none";
				}
			});
        }
    }
		else{
			swal({ title: "Info!", type: "error",  text: param_warm,   timer: 1000,   showConfirmButton: false });
		}
		return false;
	});
	$("#reset").click(function(){ 
		$('#bucket_id').val(null).trigger('change');
		$('#pic_upload').val(null).trigger('change');
		$('#get_nik').val(null).trigger('change');
		$('#get_name').val(null).trigger('change');
		$('#get_no').val(null).trigger('change');
		$('#status_id').val(null).trigger('change');
		document.getElementById('date_period').value='';

    	$('#loadpenawarandata').load('view/teleupload/get_cust_call3.php?param_reset=1', function( response, status, xhr ) {
								// alert('xxx');
							});
		return false;
	})

	 $("#btnErrorForm").click(function(){
    	// swal({
					// 	title: 'Are you sure to return to the previous page?',
					// 	text: "",
					// 	type: 'warning',
					// 	buttons:{
					// 		confirm: {
					// 			text : 'Yes',
					// 			className : 'btn btn-success'
					// 		},
					// 		cancel: {
					// 			visible: true,
					// 			className: 'btn btn-danger'
					// 		}
					// 	}
					// }).then((Save) => {
					// 	if (Save) {
							// var alink= "<?php echo $ffolder;?>|<?php echo $fmenu_link_back;?>|<?php echo $fdescription;?>|<?php echo $fmenu_id;?>|<?php echo $ficon;?>|<?php echo $fiddet;?>|<?php echo $fblist;?>"
							// var link = "index.php?v="+encodeURI(btoa(alink));
							// window.location.href = link;
							var fblist   = "<?php echo $fblist; ?>";
							var v_agentid   = "<?php echo $v_agentid; ?>";
							document.location = "view/report/convert/convert_excel_error22.php?v_agentid=" + v_agentid;
							//window.history.back();
					// 	} else {
					// 		swal.close();
					// 	}
					// });
        return false;
	})
	
    $("#btnProssesForm").click(function(){
    	var fvalid = true;

		// var fblist = "<?php echo $fblist; ?>";
		// if (fblist=="") {
		// 	fvalid=false;
		// }
    	 if(fvalid==true){
    				swal({
						title: 'Are you sure want to prosses?',
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
							 var data = new FormData();
							 var form_data = $('#frmDataDet').serializeArray();
							 $.each(form_data, function (key, input) {
							    data.append(input.name, input.value);
							 });

							 data.append('key', 'value');	
							
							 $.ajax({
						        url: "<?php echo $save_form; ?>?v=prosses",
						        type: "post",
						        data: data,
							    processData: false,
							    contentType: false,
						        success: function(d) {
						        	var warn = d;
					            	if(warn=="Success") {
					            		var vtype = "success";
					            	} else {
										var vtype = "error";	
					            	}
						            swal({ title: "Save Data!", type: vtype,  text: warn,   timer: 1000,   showConfirmButton: false });
						            if(warn=="Success") {
						            	setTimeout(function(){history.back();}, 1500);
						            } 
						        }
							  });
						} else {
							swal.close();
						}
					});
				}
		// } else {
		// 	swal({ title: "Info!", type: "error",  text: "Please generate data",   timer: 1000,   showConfirmButton: false });
		// }
        return false; 
	}) 

	function progressHandler(event) {
	  _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
	  var percent = (event.loaded / event.total) * 100;
	  _("progressBar").value = Math.round(percent);
	  _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
	}

	function completeHandler(event) {
	  _("status").innerHTML = event.target.responseText;
	  _("progressBar").value = 0; //wil clear progress bar after successful upload
	}

	function errorHandler(event) {
	  _("status").innerHTML = "Upload Failed";
	}

	function abortHandler(event) {
	  _("status").innerHTML = "Upload Aborted";
	}
    </script>
<script language="javascript">
function edit(param){
   var idIndex = param; 
   var link;

	<?php
	// $menuact  = $modfolder."|".$menu_linkdet."|".$menu_label."|".$menu_id."|".$menu_icon."|";
	$menuact  = $ffolder."|".$menu_linkdet."|".$menu_label."|".$fmenu_id."|".$ficon."|";
	?>
	var link = "index.php?v="+encodeURI(btoa('<?php echo $menuact ?>'+idIndex));
    
    window.location = link;
	}
</script>
