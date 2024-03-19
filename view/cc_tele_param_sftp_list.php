<?php
include "../../sysconf/con_reff.php";
include "../../sysconf/global_func.php";
include "../../sysconf/session.php";
include "../../sysconf/db_config.php";

$condb = connectDB();
$v_agentid      = get_session("v_agentid");
$v_agentlevel   = get_session("v_agentlevel");

$iddet 			= $library['iddet'];

$ffolder		= $library['folder'];
$fmenu_link		= $library['menu_link'];
$fdescription	= $library['description'];
$fmenu_id		= $library['menu_id'];
$ficon			= $library['icon'];
$fiddet			= $library['iddet'];
$fblist			= $library['blist'];

// echo "ini : ".$iddet;
$fmenu_link_back = "cc_tele_param_sftp_list";
    	
$blist 			= $library['blist'];
$strblist       = explode(";", $blist); 
$blist_date		= $strblist[0];
$blist_fcount	= $strblist[1];
$blist_csearch0	= $strblist[2];
$blist_tsearch0	= $strblist[3];
$blist_csearch1	= $strblist[4];
$blist_tsearch1	= $strblist[5];
$blist_csearch2	= $strblist[6];
$blist_tsearch2	= $strblist[7];
$blist_csearch3	= $strblist[8];
$blist_tsearch3	= $strblist[9];
$blist_csearch4	= $strblist[10];
$blist_tsearch4	= $strblist[11];
$no = 0;
$sqlv = "SELECT a.* FROM cc_parameter_acd a
            WHERE a.id > 0 "; //echo $sqlv;
$resv = mysqli_query($condb, $sqlv);
while($recv = mysqli_fetch_array($resv)) {
	$no++;
	$param_id[$no]       = $recv["id"];
	$parm_name[$no]      = $recv["parm_name"];
	$parm_value[$no]     = $recv["parm_value"]; 
} 

//file save data
$save_form = "view/system/cc_tele_param_sftp_save.php";

if($iddet  == "") {
	$desc_iddet = "Create New";
}else{
	$desc_iddet = "View";
}

if ($iddet == "") {
	$iddet=2;
}
$modul_code = "";
$modul_name = "";
$url 		= "";
$username 	= "";
$password	= "";
$sql = "SELECT * FROM cc_tele_parameter_sftp WHERE modul_code = $iddet";
$res = mysqli_query($condb, $sql);
if ($row = mysqli_fetch_array($res)) {
	$modul_code = $row['modul_code'];
	$modul_name = $row['modul_name'];
	$url 		= $row['sftp_url'];
	$username 	= $row['sftp_username'];
	$password	= $row['sftp_password'];
}

if ($modul_code=="") {
	$modul_code = 2;
	$modul_name = "Teleupload";
}

$select_modulcode1 = "";
$select_modulcode2 = "";
$select_modulcode3 = "";
switch ($modul_code) {
	case "1":
		$select_modulcode1 = "selected";
		break;	
	case "2":
		$select_modulcode2 = "selected";
		break;
	case "3":
		$select_modulcode3 = "selected";
}

?>

<form name="frmDataDet" id="frmDataDet" method="POST"><?php $idsec = get_session('idsec'); ?> <input type="hidden" name="idsec" id="idsec" value="<?php echo $idsec;?>">
<input type="hidden" name="iddet" id="iddet" value="<?php echo $iddet;?>">

<input type="hidden" name="blist_date" id="blist_date" value="<?php echo $blist_date;?>">
<input type="hidden" name="blist_fcount" id="blist_fcount" value="<?php echo $blist_fcount;?>">
<input type="hidden" name="blist_csearch0" id="blist_csearch0" value="<?php echo $blist_csearch0;?>">
<input type="hidden" name="blist_tsearch0" id="blist_tsearch0" value="<?php echo $blist_tsearch0;?>">
<input type="hidden" name="blist_csearch1" id="blist_csearch1" value="<?php echo $blist_csearch1;?>">
<input type="hidden" name="blist_tsearch1" id="blist_tsearch1" value="<?php echo $blist_tsearch1;?>">
<input type="hidden" name="blist_csearch2" id="blist_csearch2" value="<?php echo $blist_csearch2;?>">
<input type="hidden" name="blist_tsearch2" id="blist_tsearch2" value="<?php echo $blist_tsearch2;?>">
<input type="hidden" name="blist_csearch3" id="blist_csearch3" value="<?php echo $blist_csearch3;?>">
<input type="hidden" name="blist_tsearch3" id="blist_tsearch3" value="<?php echo $blist_tsearch3;?>">
<input type="hidden" name="blist_csearch4" id="blist_csearch4" value="<?php echo $blist_csearch4;?>">
<input type="hidden" name="blist_tsearch4" id="blist_tsearch4" value="<?php echo $blist_tsearch4;?>">


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
	<div class="content" style="margin-top: 10px;">
		<div class="row">
		
		<!-- table 1 start -->
		<div class="col-md-12">
			<div class="card">
				<div style="margin:10px 10px 10px 10px;">
					<div>

						
							<div class="form-body">		
								<div class="form-group">
									<label>Modul Tele</label>
									<select class="form-control" id="modul_code" name="modul_code" readonly>
										<option value="1" <?php echo $select_modulcode1; ?>>Telesales</option>
										<option value="2" <?php echo $select_modulcode2; ?>>Teleupload</option>
										<option value="3" <?php echo $select_modulcode3; ?>>Telecollection</option>
									</select>
									<input type="hidden" class="form-control" id="modul_name" name="modul_name" value="<?php echo $modul_name ?>">
								</div>

								<div class="form-group">
									<label>URL</label>
									<input type="text" class="form-control" name="sftp_url" id="sftp_url" value="<?php echo $url ?>" readonly>
								</div>

								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control" name="sftp_username" id="sftp_username" value="<?php echo $username ?>">
								</div>

								<div class="form-group">
									<label>Password</label>
									<input type="text" class="form-control" name="sftp_password" id="sftp_password" value="<?php echo $password ?>">
								</div>

							</div>


					</div>
				</div>
			</div>
		</div>
		<!-- table 1 end -->
		
		</div>
		
		
		<div class="card-action">
			<?php
			echo button_priv_header('1');
			?>
		</div>
		
	</div>
</div>


</form>
<?php
disconnectDB($condb);
?>

<script src="assets/js/core/jquery.3.2.1.min.js"></script>
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

<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/chart.js/chart.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script type="text/javascript" src="assets/js/plugin/jqvmap/maps/jquery.vmap.world.js" charset="utf-8"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/plugin/moment/moment.min.js"></script>
<script src="assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/atlantis.min.js"></script>
<script src="assets/prism.js"></script>
<script src="assets/prism-normalize-whitespace.min.js"></script>
<script src="assets/js/validation.js"></script>


<script lang="javascript">
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
					            	warn = d
					            	if(warn=="Success") {
					            		var vtype = "success";
					            	} else {
										var vtype = "error";	
					            	}
						            swal({ title: "Save Data!", type: vtype,  text: vtype,   timer: 1000,   showConfirmButton: false });
						            if(warn=="Success") {
						            	//setTimeout(function(){history.back();}, 1500);
					            		// reload_imgheader();
					            		// location.reload(); 
					            		modulcode = $("#modul_code").val();
					            		var alink= "<?php echo $ffolder;?>|<?php echo $fmenu_link_back;?>|<?php echo $fdescription;?>|<?php echo $fmenu_id;?>|<?php echo $ficon;?>|"+modulcode+"|";
					            		alink = btoa(alink);
										var link = "index.php?v="+alink;
										window.location.href = link;
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

<script type="text/javascript">
	$("#modul_code").on("change", function(){
		modul_name = $("#modul_code option:selected").text();
		$("#modul_name").val(modul_name);
	})

</script>