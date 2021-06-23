<?php
	$stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
	$stmt->execute([':prof' => $profile]);
	$positions = [];

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$positions[] = $row;
	}




?>