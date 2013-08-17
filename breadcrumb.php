<?php
include 'userauthenticate.php';
require_once 'newmodal.php';
$company=isset($_COOKIE['ca_companyname'])?$_COOKIE['ca_companyname']:"Company Not Selected";
$period=isset($_COOKIE['ca_period'])?$_COOKIE['ca_period']:"Period Not Selected";
makemodal_btnclose("mymodalid", "Error", "generalerror");
?>
<html>
	<body>
		<div style="width: 100%">
			<ul class="nav nav-pills pull-left">
			  <li><a href="#"><?php echo $company; ?></a></li>
			  <li><a href="#"><?php echo $period; ?></a></li>
			</ul>
			<ul class="nav nav-pills pull-right">
			  <li><a href="#" onclick="logout();"><?php echo $_SESSION["ca_loginusername"]." (logout)";?></a></li>
			</ul>
		</div>
<script>
	function logout()
			{
				$.ajax({
				type: 'GET',
				contentType: 'application/json',
				url: apiurl+'user.php/clearsession',
				success: function(data){
					document.location=home + "/login/userlogin.php";
				},
				error: function(jqXHR, textStatus, errorThrown){
					$('#mymodalid').modal();
				}
			});
			}
</script>
	<!--test --->
</body></html>
