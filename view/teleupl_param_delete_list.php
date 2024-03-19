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


//file save data
$save_form = "view/teleupload/teleupl_param_delete_save.php";
// $get_data = "view/teleupload/get_data_value.php";

if($iddet  == "") {
	$desc_iddet = "Detail";
}else{
	$desc_iddet = "View";
}


$success_checked = "";
$failed_checked = "";
$sql = "SELECT param_success, param_failed FROM cc_teleupload_param_report_delete_log ORDER BY id DESC LIMIT 1";
$res = mysqli_query($condb, $sql);
if ($row = mysqli_fetch_array($res)) {
	$row["param_success"] == 1 ? $success_checked = "checked":0;
	$row["param_failed"] == 1 ? $failed_checked = "checked":0;
}
mysqli_free_result($res);


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
								
									$txttitle	= "Parameter Generate Report Delete Data Upload";
              		$icofrm	  = "fas fa-list-ul";
              		echo title_form_det($txttitle,$icofrm);
								?>

                <div class="row">
                  <div class="col-md-12">
                  	<div class="card-body">
                  		<h4>Deleted Status</h4>
                  	
	                  	<div class="form-inline">
		                  	<div class="form-group">
		                  		<input type="checkbox" name="par_success" id="par_success" class="form-control" style="margin-right: 10px;" <?php echo $success_checked; ?>>
		                  		<label for="par_success">Success</label>
		                  	</div>
	                  	</div>

	                  	<div class="form-inline">
		                  	<div class="form-group">
		                  		<input type="checkbox" name="par_failed" id="par_failed" class="form-control" style="margin-right: 10px;" <?php echo $failed_checked; ?>>
		                  		<label for="par_failed">Failed</label>
		                  	</div>
	                  	</div>

	                  </div>
	                  <div class="card-footer">
											<button id="btnSaveForm" type="button" class="btn btn-primary"> Save</button>
	                  </div>

                  </div>
								</div>
								

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
	});

</script>

<script type="text/javascript">
var form = $( "#frmDataDet" );
form.validate();

  $("#btnSaveForm").click(function(){ 
		var fvalid = form.valid();
		if(fvalid==true){

  		swal({
					title: 'Are you sure want to save?',
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
					      url: "<?php echo $save_form; ?>",
					      type: "post",
					      data: data,
						    processData: false,
						    contentType: false,
					        success: function(d) {
					        	var warn = d;
				            if(warn=="Success!") {
				            	var vtype = "success";
				            } else {
									    var vtype = "error";	
				            }
					          if(warn=="Success") {
					            swal({ title: "Save Data!", type: vtype,  text: warn,   timer: 1000,   showConfirmButton: false });
					            	//setTimeout(function(){history.back();}, 1500);
					          } 
					        }
						  });
					} else {
						swal.close();
					}
				});
		
	}else{
		swal({ title: "Info!", type: "error",  text: "Please fill in all mandatory",   timer: 1000,   showConfirmButton: false });
	}
      return false;
})
</script>