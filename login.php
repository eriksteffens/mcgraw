<?php
	require_once('includes/common.php');
	require_once('includes/header.php');
	// require_once('includes/nav.php');
	require_once('includes/database.php');
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	    //if request method is post
	    //now validate input
	    $username = $_POST['username'];
	    $password = $_POST['password'];
	    $error = null;
	    if(empty($username) || empty($password)){
		$error = "Please enter both a Username and a Password";
	    }else{
		try{
		    $login = login($username, $password);
		}catch(Exception $e){
		    $error = "Invalid Username or Password";
		}
	    }
	    if($error == "" && $login->userName == $username){
		//echo "<div class='success-text'>Login Successful!!</div>";
		$_SESSION["logged_in"] = "true";
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		redirectRequest("/");
	    }else{
		//echo "<div class='error-text'>" . $error . "</div>";
	    }
	}elseif(!empty($_GET["logout"])){
	    session_destroy();
	    
	}
 ?>


<!-- body tags included in this layout due to lack of nav and footer -->
 <body id="login">
	<div class="container">
		<div class="valign-wrapper">
			<div class="valign" id="login-div">
			 	<div class="row">
			 		<div class="col s12 center-align">
			 			<img id="login-photo" src="img/mcgraw_logo.png" alt="McGraw Group Real Estate Investment Company Logo">
			 		</div>
			 	</div>
			    <div class='error-text'><?php echo $error; ?></div>
			    <form class="col s12" action="login.php" method="post">
				 	<div class="row">
					    
				        <div class="row">
						    <div class="input-field col s12">
						        <i class="material-icons icon-white prefix">account_circle</i>
						        <input name='username' id="icon_prefix" type="text" class="validate">
						        <label for="icon_prefix">Username</label>
					        </div>
					        <div class="input-field col s12">
						        <i class="material-icons icon-white prefix">vpn_key</i>
						          <input name='password' id="password" type="password" class="validate">
						          <label class="login-label" for="password">Password</label>
					        </div>
				        </div>
					</div>
					<div class="row">
						<div class="center col s12">
							<button class="btn waves-effect waves-light" type="submit" name="action">Login
						    	<i class="center material-icons right">send</i>
						 	</button>
						</div>
					</div>
			     
				</form>
			</div>
		</div>
	</div>

 <?php
	require_once('includes/footer.php');
?>