<?php
	require_once('includes/common.php');
	require_once('includes/header.php');
	require_once('includes/nav.php');
	require_once('includes/database.php');

	$page = "WorkOrders";
	


	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if (empty($_POST["id"])) {
			newDBItem($_POST,$page);
		}else{
		setDBItem($_POST,$page);
	}

		foreach($_FILES as $file){

			$target_dir = "uploads/";
			$target_file = $target_dir . basename($file["name"]);

			if (move_uploaded_file($file["tmp_name"], $target_file)) {
		        //echo "The file ". basename( $file["name"]). " has been uploaded.";
		    } else {
		        //echo "Sorry, there was an error uploading your file.";
		    }
		}

	}

	$completedTabs = [];

	$dbItems = getDBItems($page);
	$fields = getFields($page);

	$tabs = array_keys($fields);

?>

<div id="main" class="container">
	<div class="row">
		<div class="col s12">
			<h3 class="center-align list-title"> Work Order Forms </h3>
		</div>
	</div>
	
		<div id="asset-list" class="col s12">
		<div class="row menu-row">
		<div class="newObject col s5"><a href="#modal1" class="addbutton waves-effect waves-light btn valign">New Work Order</a></div>
		<div class="col s6 QuickAdd hide-on-med-and-down"><form id="quickForm" action="" method="post" enctype="multipart/form-data">
			<div class="input-field col s8">
          <input placeholder="Quick Add" id="quickAdd" Name="Name" type="text" class="validate">
          <!-- <label for="quickAdd">Name</label> -->
          
        </div>
        <div class="col s3"><button type="submit" class="quickaddbutton waves-effect waves-light btn valign" value="submit" name="submit">Add</button></div>

		</form></div>
		</div>
<?php
	foreach ($dbItems as $dbItem) { 
?>
		<div class="row" id="item-<?php echo $dbItem->id; ?>">
			<div class="center-align asset-item" class="col s12">
				<div class="row asset-row">
					<div class="col s6 m6 l8 valign-wrapper full-height">
						<h5 class="left asset-label valign"> <?php echo $dbItem->name; ?></h5>
					</div>
					<div class="col s6 m6 l4 valign-wrapper full-height">
						<a href="#modal1" id="btn-item-<?php echo $dbItem->id; ?>"  class="waves-effect waves-light btn valign right-align asset-btn" onclick="getDBItem(<?php echo $dbItem->id . ",'" . $page . "'"; ?>)"><i class="material-icons right">visibility</i>View Form</a>
					</div>
				</div>	
			</div>
		</div>


<?php
	}
?>
			

				<!-- Use these echos if you feel like it -->
			<?php
				// echo '<div class="row">';
				// echo 	'<div class="row asset-row">';
				// echo 		'<div class="col s7 m7 l7 valign-wrapper full-height">';
				// echo 			'<h5 class="left asset-label valign"> DBItem 1 </h5>';
				// echo 		'</div>';
				// echo 		'<div class="col s5 m5 l5 valign-wrapper full-height">';
				// echo 			'<a class="waves-effect waves-light btn valign right-align"><i class="material-icons right">visibility</i>View Form</a>';
				// echo 		'</div>';
				// echo 	'</div>';
				// echo '</div>';
			?>
		
		  <!-- Modal Structure -->
		 <div id="modal1" class="modal">
		 <form id="itemForm" action="" method="post" enctype="multipart/form-data">
		 	<input id="id" name="id" type="hidden" />
			 <div class="nav-content">
		      <ul class="tabs">
		      <?php foreach($tabs as $tab) { ?>

		        <li class="tab"><a class="" href='<?php echo "#" . friendly($tab); ?>' ><?php echo $tab; ?></a></li>
		        <?php } ?>
		        <li class="tab"><a class="" href='#checklist' >Check List</a></li>
		      </ul>
		    </div>


		      <?php foreach($tabs as $tab) { ?>
					<div id="<?php echo friendly($tab); ?>" class="modal-content">
		      	 		<div class="row">
				    		<div class="col s12">

				      			<div class="row">


				      				<?php foreach ($fields[$tab] as $field){ 




				      					if((is_array($field))){
				      						//this meens that we have found subtabs list grab them.
				      					if(!in_array($tab, $completedTabs)){
				      						$completedTabs[] = $tab;
				      						$subTabs = array_keys($fields[$tab]);

				      						//var_dump($subTabs);
				      						?>
				      						<div class="nav-content">
										      <ul class="tabs">
										      <?php foreach($subTabs as $subTab) { ?>

										        <li class="tab"><a class="" href='<?php echo "#" . friendly($subTab); ?>' ><?php echo $subTab; ?></a></li>
										        
										        <?php } ?>
										      </ul>
										    </div>

										    <?php foreach($subTabs as $subTab) { ?>
												<div id="<?php echo friendly($subTab); ?>" >
										    		<div class="col s12">
										      			<div class="row">
											<?php 

												foreach ($fields[$tab][$subTab] as $field){

													if($field->type=="File"){ 
				      							?>
				      								<div class="input-field ">
												    <a class="btn downbtn" id="<?php echo friendly($field->name) . "_get"; ?>" href="" ><i class="material-icons">file_download</i></a>
				      								<div class="file-field mcfile">
												      <div class="btn">
												        <span>File</span>
												        <input name="<?php echo friendly($field->name) . "_file"; ?>" id="<?php echo friendly($field->name) . "_file"; ?>" type="file">
												      </div>

												      <div class="file-path-wrapper">
												        <input name="<?php echo friendly($field->name) . ""; ?>" id="<?php echo friendly($field->name) . ""; ?>" class="border-field file-path validate" type="text">
												      </div>
												    </div>
												    <div class="clear"></div>
												    </div>

				      							<?php
				      						}elseif($field->type=="Select"){
				      								?>

				      						<div class="input-field col <?php  
				      						if($field->width === "Full"){
				      							echo "s12";
				      						}elseif($field->width === "Half"){
				      							echo "m6 s12";
				      						}else{
				      							echo "m4 s12";
				      						}
				      						?>">
				      						<?php if($field->related != false){ ?>

				      						<a class="btn downbtn" id="<?php echo friendly($field->name) . "_go"; ?>" href="<?php if($field->related = "Assets"){echo 'main.php#item-[id]';}; ?>" ><i class="material-icons">search</i></a>
				      						
				          					<select name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" class="border-field validate mcfile">
				          					<?php }else{ ?>
				          						<select name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" class="border-field validate">

				          					<?php	} ?>
				          						<?php
				          						foreach ($field->choices as $key=>$choice) {
				          							?>   <option value="<?php echo $key; ?>"><?php echo $choice; ?></option>   <?php
				          						}
				          						?>
				          					</select>
				          					<label for="<?php echo friendly($field->name); ?>"><?php echo $field->name; ?></label>

				        					</div>


				        


				      				<?php 
				      						}


				      						else{

				      					?>

				      					<div class="input-field col <?php  if($field->width === "Full"){
				      							echo "s12";
				      						}elseif($field->width === "Half"){
				      							echo "m6 s12";
				      						}else{
				      							echo "m4 s12";
				      						} ?>">
				          					<input name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" type="text" class="validate border-field">
				          					<label for="<?php echo friendly($field->name); ?>"><?php echo $field->name; ?></label>
				        					</div>
				        


				      				<?php }



													

												}

											
												
											 ?>






											 </div>
											 </div>
											 </div>

										    <?php
										}
										}


				      					}else{

				      						if($field->type=="File"){ 
				      							?>
				      								<div class="input-field col s11">
												    <a class="btn downbtn" id="<?php echo friendly($field->name) . "_get"; ?>" href="" ><i class="material-icons">file_download</i></a>
				      								<div class="file-field mcfile">
												      <div class="btn">
												        <span>File</span>
												        <input name="<?php echo friendly($field->name) . "_file"; ?>" id="<?php echo friendly($field->name) . "_file"; ?>" type="file">
												      </div>

												      <div class="file-path-wrapper">
												        <input name="<?php echo friendly($field->name) . ""; ?>" id="<?php echo friendly($field->name) . ""; ?>" class="border-field file-path validate" type="text">
												      </div>
												    </div>
												    <div class="clear"></div>
												    </div>

				      							<?php
				      						}elseif($field->type=="Select"){
				      								?>

				      						<div class="input-field col <?php  
				      						if($field->width === "Full"){
				      							echo "s12";
				      						}elseif($field->width === "Half"){
				      							echo "m6 s12";
				      						}else{
				      							echo "m4 s12";
				      						}
				      						?>">
				      						<?php if($field->related != false){ ?>

				      						<a class="btn downbtn" id="<?php echo friendly($field->name) . "_go"; ?>" href="<?php if($field->related = "Assets"){echo "main.php#item-[id]";}; ?>" ><i class="material-icons">search</i></a>
				      						
				          					<select name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" class="border-field validate mcfile">
				          					<?php }else{ ?>
				          						<select name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" class="border-field validate">

				          					<?php	} ?>
				          						<?php
				          						foreach ($field->choices as $key=>$choice) {
				          							?>   <option value="<?php echo $key; ?>"><?php echo $choice; ?></option>   <?php
				          						}
				          						?>
				          					</select>
				          					<label for="<?php echo friendly($field->name); ?>"><?php echo $field->name; ?></label>

				        					</div>


				        


				      				<?php 
				      						}


				      						else{

				      					?>

				      					<div class="input-field col <?php  if($field->width === "Full"){
				      							echo "s12";
				      						}elseif($field->width === "Half"){
				      							echo "m6 s12";
				      						}else{
				      							echo "m4 s12";
				      						} ?>">
				          					<input name="<?php echo friendly($field->name); ?>" id="<?php echo friendly($field->name); ?>" type="text" class="validate border-field">
				          					<label for="<?php echo friendly($field->name); ?>"><?php echo $field->name; ?></label>
				        					</div>
				        


				      				<?php }	}

				      				 } ?>
				      			</div>
				      		</div>
				      	</div>
				     </div>


		      <?php }

		      //here is where I need to create the checklist portion



		      ?>
		      <div id="checklist" class="modal-content">
		      	 		<div class="row">
				    		<div class="col s12"></div>

				    			<?php foreach($tabs as $tab) { ?>
				    			<div class="col s12 checklist-title"><?php echo $tab; ?></div>
				    			<hr/>

				    			<?php foreach ($fields[$tab] as $field){ 
				      					if((is_array($field))){ 
				      						foreach( $field as $subField){
				      							?>
				      								<div class="col m4 s12" id="check-<?php  echo friendly($subField->name); ?>">
				      									<?php echo $subField->name; ?>
				      									
				      								</div>
				      							<?php

				      						}
												?>

				      					<?php }else { ?>


				      						<div class="col m4 s12" id="check-<?php  echo friendly($field->name); ?>"><?php echo $field->name; ?></div>


				      					<?php	} ?>

				    			<?php }
				    			 ?><div class="clear"></div>
				    			<?php } ?>




				    	</div>
			</div>



		    <div class="modal-footer">
		     	 <a href="" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
		     	 <button type="submit" class="modal-action modal-close waves-effect waves-green btn-flat" value="submit" name="submit">Submit</button>
		    </div>
		    </form>
		 </div>

		</div>
	</div>
</div>


<?php
	require_once('includes/footer.php');
?>

<script>
$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('#modal1').modal();
  });
</script>