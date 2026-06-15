<?php
session_start();
include 'components/connect.php';

// Function to execute SQL file
function executeSQLFile($conn, $sqlFile) {
    try {
        // Read the SQL file
        $sql = file_get_contents($sqlFile);
        
        // Execute the SQL statements
        $conn->exec($sql);
        
        return ["success" => true, "message" => "Tables from $sqlFile created successfully!"];
    } catch(PDOException $e) {
        return ["success" => false, "message" => "Error creating tables from $sqlFile: " . $e->getMessage()];
    }
}

$messages = [];

// Process form submission
if (isset($_POST['create_tables'])) {
    // Create orders tables
    $result = executeSQLFile($conn, 'create_orders_tables.sql');
    $messages[] = $result;
    
    // Create cart table if needed
    $result = executeSQLFile($conn, 'create_cart_table.sql');
    $messages[] = $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swara Fashion Setup</title>
    <link rel="stylesheet" href="css/user_style.css">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .setup-heading {
            text-align: center;
            margin-bottom: 30px;
        }
        .setup-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .setup-section h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .message {
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <div class="setup-container">
        <div class="setup-heading">
            <h1>Swara Fashion Setup</h1>
            <p>Use this page to set up your database tables</p>
        </div>

        <?php if (!empty($messages)): ?>
            <div class="setup-section">
                <h3>Setup Results</h3>
                <?php foreach ($messages as $result): ?>
                    <div class="message <?= $result['success'] ? 'success' : 'error' ?>">
                        <?= $result['message'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="setup-section">
            <h3>Database Tables Setup</h3>
            <p>Click the button below to create or update the necessary database tables for the orders system:</p>
            <form method="post" action="">
                <div class="btn-container">
                    <button type="submit" name="create_tables" class="btn">Create/Update Tables</button>
                </div>
            </form>
        </div>

        <div class="setup-section">
            <h3>Next Steps</h3>
            <p>After setting up the database tables, you can:</p>
            <ul>
                <li>Go to the <a href="home.php">Home Page</a></li>
                <li>Browse the <a href="shop.php">Shop</a></li>
                <li>View your <a href="order.php">Orders</a></li>
            </ul>
        </div>
    </div>

    <?php include 'components/user_footer.php'; ?>
</body>
</html>
