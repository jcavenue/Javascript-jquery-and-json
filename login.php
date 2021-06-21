<?php
	session_start();
	date_default_timezone_set('Asia/Manila');
	require_once 'connect/pdo.php';

	if(isset($_POST['cancel'])){
		header('Location: index.php');
	}

	if(isset($_POST['Login'])){
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
			$_SESSION['error'] = 'This is an invalid email';
			header("Location: login.php");
			return;
		}

		$salt = 'XyZzy12*_';
		$check = hash('md5', $salt.$_POST['pass']);
		$stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
		$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ( $row !== false ) {
			$_SESSION['name'] = $row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			// Redirect the browser to index.php
			header("Location: index.php");
			return;
		} else {
			$_SESSION['error'] = 'Incorrect password';
			header("Location: login.php");
			return;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>a203ea3c</title>
		<!-- Bootstrap 5 Beta Minified CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap 5 Beta Minified JS -->
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container-fluid mx-5 mt-4 px-5">
			<h2>Please Log in</h2>
			<?php
				if ( isset($_SESSION['error']) ) {
					echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>";
					unset($_SESSION['error']);
				}
			?>
			<form action="login.php" method="post" class="form">
				<table>
					<tr>
						<td><label class="form-label small" for="email">Email</label></td>
						<td><input type="text" name="email" id="email"></td>
					</tr>
					<tr>
						<td><label class="form-label small"for="pass">Password</label></td>
						<td><input type="password" name="pass" id="pass"></td>
					</tr>
				</table><br>
				<input type="submit" name="Login" value="Log In" onclick="return doValidate();" class="btn btn-primary btn-sm">
				<input type="submit" name="cancel" value="Cancel" class="btn btn-secondary btn-sm">
			</form>
			<script src="validate.js" type="text/javascript"></script>
		</div>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>