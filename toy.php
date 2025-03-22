<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	// Retrieve the value of the 'toynum' parameter from the URL query string
	//		i.e., ../toy.php?toynum=0001
	$toy_id = $_GET['toynum'];


	/*
	 * TO-DO: Define a function that retrieves ALL toy and manufacturer info from the database based on the toynum parameter from the URL query string.
	 		  - Write SQL query to retrieve ALL toy and manufacturer info based on toynum
	 		  - Execute the SQL query using the pdo function and fetch the result
	 		  - Return the toy info

	 		  Retrieve info about toy from the db using provided PDO connection
	 */
	function get_toy_details(PDO $pdo, string $toy_id) {
		$sql = "SELECT toy.*, 
					   manuf.name AS manufacturer_name, 
					   manuf.Street, 
					   manuf.City, 
					   manuf.State, 
					   manuf.ZipCode, 
					   manuf.phone, 
					   manuf.contact
				FROM toy 
				JOIN manuf ON toy.manid = manuf.manid
				WHERE toy.toynum = :toy_id";
		
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['toy_id' => $toy_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	// Fetch toy details
	$toy = get_toy_details($pdo, $toy_id);
	
	if (!$toy) {
        die("Toy not found!");
    }


// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="imgs/logo.png" alt="Toy R URI Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="index.php">Toy Catalog</a></li>
	      				<li><a href="about.php">About</a></li>
			        </ul>
			    </nav>
		   	</div>

		    <div class="header-right">
		    	<ul>
		    		<li><a href="order.php">Check Order</a></li>
		    	</ul>
		    </div>
		</header>

		<main>
			<!-- 
			  -- TO DO: Fill in ALL the placeholders for this toy from the db
  			  -->
				<section class="toy-details">
				<div class="toy-container">
					<div class="toy-image">
						<img src="<?= htmlspecialchars($toy['imgSrc']) ?>" alt="<?= htmlspecialchars($toy['name']) ?>">
					</div>
					<div class="toy-info">
						<h1><?= htmlspecialchars($toy['name']) ?></h1>
						<p><strong>Description:</strong> <?= htmlspecialchars($toy['description']) ?></p>
						<p><strong>Price:</strong> $<?= htmlspecialchars($toy['price']) ?></p>
						<p><strong>Age Range:</strong> <?= htmlspecialchars($toy['agerange']) ?></p>
						<p><strong>Number In Stock:</strong> <?= htmlspecialchars($toy['numinstock']) ?></p>

						<h2>Manufacturer Information</h2>
						<p><strong>Name:</strong> <?= htmlspecialchars($toy['manufacturer_name']) ?></p>
						<p><strong>Address:</strong> <?= htmlspecialchars($toy['Street']) ?>, <?= htmlspecialchars($toy['City']) ?>, <?= htmlspecialchars($toy['State']) ?> <?= htmlspecialchars($toy['ZipCode']) ?></p>
						<p><strong>Phone:</strong> <?= htmlspecialchars($toy['phone']) ?></p>
						<p><strong>Contact:</strong> <?= htmlspecialchars($toy['contact']) ?></p>
					</div>
				</div>
			</section>
		</main>
	</body>
</html>
