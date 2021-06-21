<?php
	session_start();
	require_once 'connect/pdo.php';

	if( !isset($_SESSION['name']) &&  !isset($_SESSION['user_id'])) {
   		die('ACCESS DENIED');
	}

	if (!isset($_GET['profile_id']) ) {
		$_SESSION['error'] = "Missing Profile_id";
		header('Location: index.php');
		return;
	}

	if(isset($_POST['cancel'])){
		header('Location: index.php');
	}

	if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
		$sql = "DELETE FROM Profile WHERE profile_id = :id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':id' => $_POST['profile_id']));
		$_SESSION['success'] = 'Profile deleted';
		header( 'Location: index.php' ) ;
		return;
	}

	$stmt = $pdo->prepare("SELECT profile_id, first_name, last_name FROM Profile where profile_id = :id");
	$stmt->execute(array(":id" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for user_id';
		header( 'Location: index.php' ) ;
		return;
	}

	$id = $row['profile_id'];
	$fname = $row['first_name'];
	$lname = $row['last_name'];
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>e5688b71</title>
		<!-- Bootstrap 5 Beta Minified CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap 5 Beta Minified JS -->
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container  mx-5 mt-4 px-5">
			<h1>Deleting Profile</h1>
			<p>First Name:  <?php echo $fname; ?></p>
			<p>Last Name: <?php echo $lname; ?></p>
			<form method="post" class="small">
				<input type="hidden" name="profile_id" value="<?php echo $id; ?>">
				<input type="submit" value="Delete" name="delete">
				<input type="submit" value="Cancel" name="cancel">
			</form>
		</div>
	</body>
</html>