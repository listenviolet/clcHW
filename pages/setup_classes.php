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
    <title>Set Up</title> 
    
    <!--link and script-->
    <?php include("./share/lib.html"); ?>

    <meta name="description" content="setup classes">
    <link href="../css/setup_class.css" rel="stylesheet">    
    <script src="../js/setup_classes.js" type="text/javascript"></script>   
</head>

<body>
	<?php if(isLoggedIn()): ?>
    <!-- Navigation -->
	<?php include("./share/navigator.php"); ?>

	<div class="main-part">
        <div class="container">
            <h2>Set Up Classes</h2>
    		<div class="container">
                <div class="existed-part" id="existed">
                    <h4>The existed classes:</h4>
                    <div id="existed_classes" name="classes" class="container"></div>
                </div>
		
				<div class="new-part" id="new">
                    <h4>Add new classes:</h4>
					<input type="button" id="add" name="add" value="Add" class="btn btn-primary btn-lg"><br>
					<div id="add_classes" name="classes" class="container"></div>
				</div>
    		</div>

            <hr>
            <button class="btn btn-default" id="goback"><a href="./set_collect.php"><i class="glyphicon glyphicon-backward"></i> Go Back To Operations</button>
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


