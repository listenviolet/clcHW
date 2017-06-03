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
<html lang="en-US">
<head>
	<title>Set Up Homework</title>

	<!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="setup_hw_detail">

    <link href="../css/setup_hw_detail.css" rel="stylesheet">

    <script src="../js/setup_hwDetail.js" type="text/javascript"></script>

</head>
<body lang="en">
	<?php if(isLoggedIn()): ?>
	<!-- Navigation -->
    <?php include("./share/navigator.php"); ?>

    <div class="hw-detail">
    <input type="hidden" name="classid" id="classid" value=<?= $classid ?>>
    	<div class="container">
		    <div id="class_name"></div>
		    <div class="container">
			    <div class="hw-detail-existed">
			    	
			    </div>
			    <div class="hw-detail-add">
			    	<h4>Add new homework:</h4>
				    <div>
						<input type="button" class="btn btn-primary btn-lg" id="addhw" name="addhw" value="Add Homework" />
					</div>
					<div class="container">
						<div id="homeworks" name="homeworks">
						</div>
					</div>
				</div>
			</div>
            <button class="btn btn-default"><a href="./setup_hw.php"><i class="glyphicon glyphicon-backward"></i> Go Back To Classes List</button>
            <button class="btn btn-default"><a href="./set_collect.php"><i class="glyphicon glyphicon-forward"></i> Go To Operations</button>
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