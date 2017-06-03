<?php 
	session_start(); 
	$username=$_SESSION['username'];
	function isLoggedIn(){
		if(isset($_SESSION['username'])){
			return ture;
		}
		else{
			return false;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Set Up Homework</title>

	<!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="set homework">
    
    <link href="../css/setup_hws.css" rel="stylesheet">
    
    <script src="../js/setup_hw.js" type="text/javascript"></script>

</head>
<body>
	<?php if(isLoggedIn()): ?>
	<!-- Navigation -->
    <?php include("./share/navigator.php"); ?>

    <div class="hw-header">
	    <div class="container">
			<h1>Set Up Homework</h1>
            <div class="container" id="hw">
            </div>
            <div>
                <button class="btn btn-default"><a href="./set_collect.php"><i class="glyphicon glyphicon-backward"></i> Go Back To Operations</button>
            </div>
		</div>
	</div>

	<!-- Footer -->
    <?php include("./share/footer.html");?>

    <!-- jQuery -->
    <script src="../js/lib/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/lib/bootstrap.min.js"></script>



	<?php else:?>
	<script type="text/javascript">
		window.location.href="./login.html";
	</script>
	<?php endif; ?>


</body>
</html>