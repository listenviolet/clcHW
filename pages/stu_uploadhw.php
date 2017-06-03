<?php 
	session_start(); 
	function isLoggedIn(){
		if(isset($_SESSION['username'])){
			return ture;
		}
		else{
			return false;
		}
	}

	if(isLoggedIn())
	{
		$classid=$_POST["classid"];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Student Homework</title>

	<!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="student upload homework">
    
    <link href="../css/stu_uploadhw.css" rel="stylesheet">

    <script src="../js/stu_uploadhw.js" type="text/javascript"></script>
    
</head>

<body>

	<?php if(isLoggedIn()): ?>
	<!-- Navigation -->
    <?php include("./share/navigator.php"); ?>

    <div class="stu-hw-details">
    	<input type="hidden" name="classid" id="classid" value=<?= $classid ?>>
    	<div class="container">	
	    	<h2>Upload Homework</h2>
            <div class="container">
    			<div id="hws" name="hws">
    			</div>
            </div>
            <button class="btn btn-default"><a href="./stu_classes.php"><i class="glyphicon glyphicon-backward"></i> Go Back To Classes List</button>
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