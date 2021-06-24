<?php
	session_start();
	require_once 'connect/pdo.php';

	// if someone try to go here without permission
	if( !isset($_SESSION['name']) &&  !isset($_SESSION['user_id'])) {
		die('ACCESS DENIED');
	}

	// Go to index.php
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}

	if (!isset($_GET['profile_id']) ) {
		$_SESSION['error'] = "Missing Profile_id";
		header('Location: index.php');
		return;
	}

	// execute if true
	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
		
		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
			$_SESSION['error'] = 'All fields are required';
			header('Location: edit.php?profile_id='. $_POST['profile_id']);
			return;
		}
		
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$_SESSION['error'] = 'Email address must contain @';
			header('Location: edit.php?profile_id='. $_POST['profile_id']);
			return;
		}

		for($i=0; $i<=9; $i++) {
			if ( ! isset($_POST['year'.$i]) ) continue;
			if ( ! isset($_POST['desc'.$i]) ) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];
			if ( strlen($year) == 0 || strlen($desc) == 0 ) {
				$_SESSION['error'] = "All fields are required";
				header('Location: edit.php?profile_id='. $_POST['profile_id']);
				return;
			}

			if ( ! is_numeric($year) ) {
				$_SESSION['error'] = "Position year must be numeric";
				header('Location: edit.php?profile_id='. $_POST['profile_id']);
				return;
			}
		}

		$stmt = $pdo->prepare('UPDATE Profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su WHERE profile_id=:pid');
		$stmt->execute(array(
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary'],
			':pid' => $_POST['profile_id'])
		);
		

		$stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
		$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

		$rank = 1;
        for ($i = 0; $i <= 9; $i++) {
            if (!isset($_POST['year' . $i])) continue;
            if (!isset($_POST['desc' . $i])) continue;

            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];
            $stmt = $pdo->prepare('INSERT INTO Position
    (profile_id, rank, year, description)
    VALUES ( :pid, :rank, :year, :desc)');

            $stmt->execute(array(
                    ':pid' => $_REQUEST['profile_id'],
                    ':rank' => $rank,
                    ':year' => $year,
                    ':desc' => $desc)
            );

            $rank++;
        }

		$_SESSION['success'] = 'Profile Updated';
		header('Location: index.php');
		return;
	}

	$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :id");
	$stmt->execute(array(":id" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id']));
	$rowOfPosition = $stmt->fetchAll();

	if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
	}

	$fname = $row['first_name'];
	$lname = $row['last_name'];
	$email = $row['email'];
	$headline = $row['headline'];
	$summary = $row['summary'];
	$id = $row['profile_id'];

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
			<h1>Adding Profile for <?php echo htmlentities($_SESSION['name']); ?></h1>
			<?php
				if(isset($_SESSION['error'])){
					echo '<p class="text-danger">' . $_SESSION['error'] . "</p>";
					unset($_SESSION['error']);
				}
			?>
			
			<form method="post" class="small">
				<p>First Name: <input type="text" name="first_name" style="width:40%" value="<?php echo $fname; ?>"></p>
				<p>Last Name: <input type="text" name="last_name" style="width:40%" value="<?php echo $lname; ?>"></p>
				<p>Email: <input type="text" name="email" value="<?php echo $email; ?>"></p>
				<p>Headline:<br>
					<input type="text" name="headline" style="width:40%" value="<?php echo $headline; ?>">
				</p>
				<p>Summary:<br>
					<textarea name="summary" rows="8" cols="80" spellcheck="false"><?php echo $summary; ?></textarea>
				</p>
				<p>Position: <input type="submit" id="addPos" value="+"></p>
				<div id="position_fields">
				<?php
					$countps = 1;
					foreach ($rowOfPosition as $row) {
						echo "<div id=\"position" . $countps . "\">
							<p>Year: <input type=\"text\" name=\"year$countps\" value=\"".$row['year']."\">
							<input type=\"button\" value=\"-\" onclick=\"$('#position". $countps ."').remove();return false;\"></p>
							<textarea name=\"desc". $countps ."\"').\" rows=\"8\" cols=\"80\">".$row['description']."</textarea>
							</div>";
						$countps++;
					} 
				?>
				</div>
				<input type="hidden" name="profile_id" value="<?php echo $id; ?>">
				<button type="submit" name="save" value="Save" class="btn btn-secondary btn-sm">Save</button>
				<button type="submit" name="cancel" class="btn btn-secondary btn-sm">cancel</button>
			</form>
		</div>
		<script>
			countPos = <?php echo $countps; ?>;
			// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
			$(document).ready(function(){
				window.console && console.log('Document ready called');
				$('#addPos').click(function(event){
					// http://api.jquery.com/event.preventdefault/
					event.preventDefault();
					if ( countPos >= 9 ) {
						alert("Maximum of nine position entries exceeded");
						return;
					}
					countPos++;
					window.console && console.log("Adding position "+countPos);
					$('#position_fields').append(
						'<div id="position'+countPos+'"> \
						<p>Year: <input type="text" name="year'+countPos+'" value="" /> \
						<input type="button" value="-" \
						onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
						<textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
						</div>');
				});
			});
		</script>
	</body>
</html>