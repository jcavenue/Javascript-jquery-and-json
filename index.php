<?php 
	session_start();
	require_once 'connect/pdo.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>c168b600</title>
		<!-- Bootstrap 5 Beta Minified CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap 5 Beta Minified JS -->
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container  mx-5 mt-4 px-5">
			<h1>John Carlo Fababeir's Resume Registry</h1>
			<!-- Flash Message by Session -->
			<?php 
				if(isset($_SESSION['error'])){
					echo '<p class="text-danger">' . htmlentities($_SESSION['error']) . "</p>";
					unset($_SESSION['error']);
				}
				if(isset($_SESSION['success'])){
					echo '<p class="text-success">' . htmlentities($_SESSION['success']) . "</p>";
					unset($_SESSION['success']);
				}
				if(!isset($_SESSION['name']) && !isset($_SESSION['user_id'])){
					$stmt = $pdo->query('SELECT profile_id, first_name, last_name, headline FROM Profile');
					$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			?>
			<!-- If Session name is not set return this -->
			<p class="small"><a href="login.php">Please log in</a></p>
				<?php if($rows) { ?>
					<div class="row">
						<div class="col-6">
							<table class="table table-dark table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th>Headline</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($rows as $row){ ?>
										<tr class="table-info">
											<td><a href="view.php?profile_id=<?php echo $row['profile_id'];?>"><?php echo htmlentities($row['first_name']) . " " . htmlentities($row['last_name']);?></a></td>
											<td><?php echo htmlentities($row['headline']);?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
	
				<?php }
			} else {
				$stmt = $pdo->query('SELECT profile_id, first_name, last_name, headline FROM Profile');
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			?>
			<!-- If Session name is set Display this -->	
			<p class="small"><a href="logout.php">Logout</a></p>
				<!-- If there are rows print it -->
				<?php if($rows) { ?>
					<div class="row">	
						<div class="col-8">
							<table class="table table-dark table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>Headline</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($rows as $row){ ?>
									<tr class="table-info">
										<td><a href="view.php?profile_id=<?php echo $row['profile_id'] ;?>"><?php echo htmlentities($row['first_name'])  . " " . htmlentities($row['last_name']); ?></a></td>
										<td><?php echo $row['headline'] ?></td>
										<td>
											<a href="edit.php?profile_id=<?php echo htmlentities($row['profile_id']); ?>">Edit</a> 
											<a href="delete.php?profile_id=<?php echo htmlentities($row['profile_id']); ?>">Delete</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						</div>	
					</div>
				<?php }	?>
			<p class="small"><a href="add.php">Add New Entry</a></p>
			<?php } ?>
		</div>
	</body>
</html>