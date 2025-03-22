<?php   
// Include the database connection script
require 'includes/database-connection.php';

/*
 * TO-DO: Define a function that retrieves ALL customer and order info from the database based on values entered into the form.
 * - Write an SQL query to retrieve ALL customer and order info based on form values
 * - Execute the SQL query using the PDO function and fetch the result
 * - Return the order info
 */
function get_order_details($pdo, $email, $orderNum) {
    // Look up custnum using the provided email
    $custStmt = $pdo->prepare("SELECT custnum FROM customer WHERE email = :email");
    $custStmt->bindParam(":email", $email, PDO::PARAM_STR);
    $custStmt->execute();
    $cust = $custStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cust) {
        return null; // No customer found with that email
    }

    // Prepare SQL query to get order details using custnum
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE custnum = :custnum AND ordernum = :orderNum");
    $stmt->bindParam(":custnum", $cust['custnum'], PDO::PARAM_INT);
    $stmt->bindParam(":orderNum", $orderNum, PDO::PARAM_STR);
    $stmt->execute();
    
    // Return the fetched data
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialize order variable
$order = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $orderNum = $_POST['orderNum'] ?? '';

    if (!empty($email) && !empty($orderNum)) {
        $order = get_order_details($pdo, $email, $orderNum);
    }
}

?>

<!DOCTYPE html>
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
        <div class="order-lookup-container">
            <h1>Order Lookup</h1>
            <form action="order.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="orderNum">Order Number:</label>
                    <input type="text" id="orderNum" name="orderNum" required>
                </div>
                <button type="submit">Lookup Order</button>
            </form>
	 	<!-- 
        -- TO-DO: Check if variable holding order is not empty. Make sure to replace null with your variable!
        -->
            <?php if (!empty($order)) : ?>
                <div class="order-details">
                    <h2>Order Details</h2>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['custnum'] ?? 'N/A'); ?></p>
                    <p><strong>Order Number:</strong> <?= htmlspecialchars($order['ordernum'] ?? 'N/A'); ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity'] ?? 'N/A'); ?></p>
                    <p><strong>Date Ordered:</strong> <?= htmlspecialchars($order['date_ordered'] ?? 'N/A'); ?></p>
                    <p><strong>Delivery Date:</strong> <?= htmlspecialchars($order['date_deliv'] ?? 'N/A'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>