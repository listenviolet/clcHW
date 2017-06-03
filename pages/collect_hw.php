<?php
	session_start();
	$username=$_SESSION['username'];
    $userid=$_SESSION['userid'];
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
        //echo $classid;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Collect Homeworks</title>

    <!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="collect homework">
    <link href="../css/collect_hws.css" rel="stylesheet">
    <script src="../js/collect_hw.js" type="text/javascript"></script>

</head>
<body>
	<?php if(isLoggedIn()): ?>
	<!-- Navigation -->
    <?php include("./share/navigator.php"); ?>

    <div class="hws">
        <input type="hidden" name="classid" id="classid" value=<?= $classid ?>> 
        <div class="container">
            <div id="class_name"></div>
            <div class="container">
                <div class="hw-lists" id="hw-lists">
                </div>
            </div>
            <button class="btn btn-default"><a href="./collect_classes.php"><i class="glyphicon glyphicon-backward"></i> Go Back To Classes List</button>
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