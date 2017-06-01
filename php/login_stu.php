<?php
	session_start();
	require_once 'conn_db.php';

	$username=$password="";
	$usernameErrnull=$usernameErremail="";
	
	$username=$_POST["username"];
	echo $username;

	if(empty($_POST["username"])||empty($_POST["password"])){			
		echo " empty username or password. ";
	}
	else {
		$username=test_input($_POST["username"]);
		if (!filter_var($username,FILTER_VALIDATE_EMAIL)){
			$usernameErremail="Invaild email format";
		}
		$password=test_input($_POST["password"]);
	}
	
	$query="select * from student where "."'".$username."'"." = email";
	$result=$GLOBALS['db']->query($query);
	
	if(mysqli_num_rows($result)>0){
		$values=mysqli_fetch_object($result);
		$hash=$values->password;

		if(password_verify($password,$hash)){
			$userid=$values->id;
			if(isset($_SESSION['userid']) && strcmp($_SESSION['userid'],$userid)!=0){
				$url="../pages/index.html";
				echo "<script type='text/javascript'>";
				echo "alert('Sorry, another account from your machine is already signed in. Please sign out the previous account befor sign in as another account.');";
				echo "window.location.href='$url'";
				echo "</script>";
			}
			else {
				$_SESSION['username']=$username;
				$_SESSION['userid']=$userid;
				$url="../pages/stu_classes.php";
				echo "<script type='text/javascript'>";
				echo "window.location.href='$url'";
				echo "</script>";
			}
		}

		else{
			$url="../pages/login.html";
			echo "<script type='text/javascript'>";
			echo "alert('Username or password wrong.');";
			echo "window.location.href='$url'";
			echo "</script>";
		}
	}
	else{
		$url="../pages/login.html";
		echo "<script type='text/javascript'>";
		echo "alert('User does not exist.');";
		echo "window.location.href='$url'";
		echo "</script>";
	}
	
	echo $_SESSION['username'];

	function test_input($data){
		$data=trim($data);
		$data=stripslashes($data);
		$data=htmlspecialchars($data);
		return $data;
	}
?>