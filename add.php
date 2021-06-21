<?php
	session_start();
	require_once 'connect/pdo.php';

	if( !isset($_SESSION['name']) &&  !isset($_SESSION['user_id'])) {
   		die('ACCESS DENIED');
	}

	if(isset($_POST['cancel'])){
		header('Location: index.php');
	}

	if(isset($_POST['add'])){
		if(!$_POST['first_name'] || !$_POST['last_name']|| !$_POST['email']|| !$_POST['headline']|| !$_POST['summary']){
			$_SESSION['error'] = 'All fields are required';
			header('Location: add.php');
			return;
		}

		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$_SESSION['error'] = 'Email address must contain @';
			header('Location: add.php');
			return;
		}

		$stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary) 
			VALUES ( :uid, :fn, :ln, :em, :he, :su)');
		$stmt->execute(array(
			':uid' => $_SESSION['user_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary'])
		);

		$_SESSION['success'] = 'Profile added';
			header('Location: index.php');
		return;
	}
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
			<h1>Adding Profile for <?php echo htmlentities($_SESSION['name']); ?></h1>
			<?php
				if(isset($_SESSION['error'])){
					echo '<p class="text-danger">' . $_SESSION['error'] . "</p>";
					unset($_SESSION['error']);
				}
			?>
			<form method="post" class="small">
				<p>First Name: <input type="text" name="first_name" style="width:40%"></p>
				<p>Last Name: <input type="text" name="last_name" style="width:40%"></p>
				<p>Email: <input type="text" name="email"></p>
				<p>Headline:<br>
					<input type="text" name="headline" style="width:40%">
				</p>
				<p>Summary:<br>
					<textarea name="summary" rows="8" cols="80" spellcheck="false"></textarea>
				</p>
				<button type="submit" name="add" value="Add"class="btn btn-secondary btn-sm">Add</button>
				<button type="submit" name="cancel" class="btn btn-secondary btn-sm">cancel</button>
			</form>
		</div>
	</body>
</html>