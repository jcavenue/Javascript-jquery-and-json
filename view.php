<?php
	session_start();
	require_once 'connect/pdo.php';

	if (!isset($_GET['profile_id']) ) {
		$_SESSION['error'] = "Missing Profile_id";
		header('Location: index.php');
		return;
	}

	$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id=:id');
	$stmt->execute([":id" => $_GET['profile_id']]);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt1 = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
	$stmt1->execute(array(":xyz" => $_GET['profile_id']));
	$position = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	if ( $rows === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
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
		<div class="container  mx-5 mt-4 px-5 small">
			<h1>Profile Information</h1>
			<?php foreach($rows as $row){?>
			<p>First Name: <?php echo $row['first_name'];?></p>
			<p>Last Name: <?php echo $row['last_name'];?></p>
			<p>Email: <?php echo $row['email'];?></p>
			<p>Headline:<br><?php echo $row['headline'];?></p>
			<p>Summary:<br><?php echo $row['summary'];?></p>
				<p>Position: <br/><ul>
			<?php
				foreach ($position as $rowp) {
					echo '<li>'. $rowp['year'] . ' : ' . $rowp['description'] . '</li>';
				} ?>
				</ul></p>
				<a href="index.php">Done</a>
			<?php } ?>
		</div>
	</body>
</html>