

   <nav>
    <div class="nav-wrapper">
      <a href="#!" class="brand-logo"><img id="nav-logo" src="img/mcgraw_logo.png" alt=""></a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
      <!-- <ul class="right hide-on-med-and-down">
        <li><a class="nav-item" href="main.php">ASSET</a></li>
        <li><a class="nav-item" href="tenant.php">TENANT</a></li>
        <li><a class="nav-item" href="workorder.php">WORK ORDER</a></li>
      </ul> -->

      <!-- <div class="nav-content"> -->

      <!-- <ul class="tabs tabs-transparent right hide-on-med-and-down">
        <li class="tab"><a href="#test1">Test 1</a></li>
        <li class="tab"><a class="active" href="#test2">Test 2</a></li>
        <li class="tab disabled"><a href="#test3">Disabled Tab</a></li>
        <li class="tab"><a href="#test4">Test 4</a></li>
      </ul> -->

    <!-- </div> -->

      <div class="right user-display">
        <?php
        echo "Logged in as</br>" . $_SESSION["username"];
?>
      </div>

      <ul class="side-nav fixed lower-nav" id="mobile-demo">
        <li><a href="main.php"><div class="nav-option"><div class="medium material-icons nav-icon">home</div><div class="nav-text">ASSET</div><div class="clear"></div></div></a></li>
        <li><a href="tenant.php"><div class="nav-option"><div class="medium material-icons nav-icon">person</div><div class="nav-text">TENANT</div><div class="clear"></div></div></a></li>
        <li><a href="workorder.php"><div class="nav-option"><div class="medium material-icons nav-icon">assignment</div><div class="nav-text">WORK ORDER</div><div class="clear"></div></div></a></li>
	<li><a href="login.php?logout=y"><div class="nav-option"><div class="medium material-icons nav-icon">exit_to_app</div><div class="nav-text">LOGOUT</div><div class="clear"></div></div></a></li>
      </ul>
    </div>
  </nav>

<body>