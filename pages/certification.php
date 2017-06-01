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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Certification</title>

    <!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="certification">
    <link href="../css/certification.css" rel="stylesheet">
</head>
<body>
    <?php if(isLoggedIn()): ?>
        
    <!-- Navigation -->
    <?php include("./share/navigator.php"); ?>
    <div class="congratulation">
        <div class="container">
            <h2>Congratulations!</h2>
            <div class="show">
                <p class="success">You have handed the homeworks successfully.</p>
                <a class="success" href="../php/certification.php" target="_blank">Click here to view your certification.</a>
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