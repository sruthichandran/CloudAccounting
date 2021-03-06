<?php 

include '../breadcrumb.php';
makemodal_progress("modalprogress", "Loading...");
makemodal_alert("modalupdatecompany", "Alert", "Select company to edit");
makemodal_alert("modaldeletecompany", "Alert", "Select company to delete");
makemodal_alert("modaldisplaycompany", "Alert", "Select company to display"); 
makemodal_alert("modalgeneralerror","Error","<script>generalerror</script>");
makemodal_confirm("modaldeleteconfirm","Confirm Delete","Are you sure you want to delete?","deleteCompany");
makemodal_alert("modaldeletesuccess","Success","Company successfully deleted");
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Select Company</title>
	</head>
	<body>
		<div class="container">
		<div class="row show-grid">
  		<div class="col-lg-8">
			<div class="well">
			<form class="form-horizontal" method="post" action="#">
				<fieldset>
				
				<!-- Form Name -->
				<legend>Select Company</legend>
				<!-- Select Basic -->
				<div class="input-append">
				  <label for="selectbasic">Select Company </label>
				  	<select id="selectbasic" name="selectbasic" class="form-control" size="5">
				    </select>
				  </div>
				<br>
			
				<!-- Button -->
				<div align="center">
				  	<a href="#" class="btn btn-success btn-small"  onclick="local_selectcompany();">Select</a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
			 		 	$perm=$_SESSION["ca_permissions"];
			 		 	if($perm['company'][1]=='1')
						{
			 		 	?>
				    <a href="#" class="btn btn-primary btn-small"  onclick="local_gotodisplaycompany();">Info</a>
				    <?php
						}
			 		 	$perm=$_SESSION["ca_permissions"];
			 		 	if($perm['company'][2]=='1')
						{
			 		 	?>
				  	&nbsp;&nbsp;&nbsp;&nbsp;
				    <a href="#" class="btn btn-primary btn-small"  onclick="local_gotoupdatecompany();">Edit</a>
				    <?php
					}
					$perm=$_SESSION["ca_permissions"];
			 		 	if($perm['company'][3]=='1')
						{
			 		 	?>
				  	&nbsp;&nbsp;&nbsp;&nbsp;
				  	<a href="#" class="btn btn-primary btn-small"  onclick="local_gotodeletecompany();">Delete</a>
				  	<?php
						}
						?>
				</div>
				
				</fieldset>
			</form>
		</div>
		</div>
	 	<!--div class="col-lg-4">
			<div class="span3 sb-fixed">
		   		<div class="well sidebar-nav">
					<ul class="nav nav-pills nav-stacked">
			 		 	<li><a href="#" onclick="gotocreatecompany();">Create Company</a></li>
			 		 	<li><a href="#">Shut Company</a></li>
			 		 	<li><a href="#">Company Info</a></li>
			 		 	<li><a href="#">Features</a></li>
			 		 	<li><a href="#">Configure</a></li>
					</ul>   
				</div>
			</div>
	  	</div-->
	  	</div>
	  	<!-- test yamini -->
		<script>
		$('#modalprogress').modal();
		loadcompany();
			
			function loadcompany()
			{
				$.ajax({
						type: 'GET',
						contentType: 'application/json',
						url: apiurl+'company.php/companylist',
						dataType: "json",
						success: function(data){
							
						    $.each(data, function(i, option) {
						        $('#selectbasic').append($('<option/>').attr("value", option.id).text(option.name));
						    });
						    $('#modalprogress').modal('hide');
					
						},
						error: function(jqXHR, textStatus, errorThrown){
							
							$('#modalgeneralerror').modal();
						}
				});
			}	
			
			function local_gotoupdatecompany()
			{
				var selcomp=document.getElementById("selectbasic").value;
				
				if(selcomp == "")
				{
					$('#modalupdatecompany').modal();
				}
				else
				{
					gotoupdatecompany(selcomp);
				} 
				
			}
			function local_gotodeletecompany()
			{
				var selcomp=document.getElementById("selectbasic").value;
				if(selcomp == "")
				{
					$('#modaldeletecompany').modal();
				}
				else
				{
					$('#modaldeleteconfirm').modal();
				}
			}
			function local_gotodisplaycompany()
			{
				var selcomp=document.getElementById("selectbasic").value;
				if(selcomp == "")
				{
					$('#modaldisplaycompany').modal();
				}
				else
				{
					gotodisplaycompany(selcomp);
				}
			}
			function local_selectcompany()
			{
				
				var e=document.getElementById("selectbasic");
				var name=e.options[e.selectedIndex].text;
				var id = e.value;
				$.ajax({
					type: 'GET',
					url: apiurl+'company.php/selectcompany/'+id+'/'+name,
					dataType: "json",
					success: function(data){
						if(JSON.stringify(data)=='1')
						{
						$("#maindiv").load("entrypage.php");
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						$('#modalgeneralerror').modal();
					}
				});
				
			}
			
			function deleteCompany()
			{
				var id = document.getElementById('selectbasic').value;
				$.ajax({
					type: 'DELETE',
					url: apiurl+'company.php/deletecompany/'+id,
					dataType: "json",
					success: function(data){
						$('#modaldeletesuccess').modal();
						 document.getElementById('selectbasic').options.length = 0;
						loadcompany();
					},
					error: function(jqXHR, textStatus, errorThrown){
						
						$('#modalgeneralerror').modal();
					}
				});
			}
		</script>
	</body>
</html>
