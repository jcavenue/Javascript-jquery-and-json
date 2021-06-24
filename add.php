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

		for($i=1; $i<=9; $i++) {
			if ( ! isset($_POST['year'.$i]) ) continue;
			if ( ! isset($_POST['desc'.$i]) ) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];
			if ( strlen($year) == 0 || strlen($desc) == 0 ) {
				$_SESSION['error'] = "All fields are required";
				header('location: add.php');
				return;
			}

			if ( ! is_numeric($year) ) {
				$_SESSION['error'] = "Position year must be numeric";
				header('location: add.php');
				return;
			}
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

		$profile_id = $pdo->lastInsertId();

		$rank = 1;
		for($i = 1; $i <= 9; $i++){
			if ( !isset($_POST['year'.$i]) ) continue;
			if ( !isset($_POST['desc'.$i]) ) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['year'.$i];

			$stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description) 
			VALUES ( :pid, :rank, :year, :desc)');
			$stmt->execute(array(
				':pid' => $profile_id,
				':rank' => $rank,
				':year' => $year,
				':desc' => $desc)
			);
			$rank++;
		}
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
		<!-- jquery -->
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container  mx-5 mt-4 px-5">
			<h1>Adding Profile for <?php echo htmlentities($_SESSION['name']) . htmlentities($_SESSION['user_id']); ?></h1>
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
				<p>Position: <input type="submit" id="addPos" value="+"></p>
				<div id="position_fields"></div>
				<button type="submit" name="add" value="Add"class="btn btn-secondary btn-sm">Add</button>
				<button type="submit" name="cancel" class="btn btn-secondary btn-sm">cancel</button>
			</form>
		</div>
		<script src="js/addPosition.js"></script>
	</body>
</html>