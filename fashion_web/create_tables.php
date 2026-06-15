<?php
try {
    // Create initial connection without database
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Starting Database Creation</h2>";
    
    // Create and use the database
    $conn->exec("DROP DATABASE IF EXISTS fashion_db");
    $conn->exec("CREATE DATABASE fashion_db");
    $conn->exec("USE fashion_db");
    echo "Database created and selected<br><br>";
    
    // Create orders table
    $orders_sql_file = 'create_orders_table.sql';
    echo "Reading SQL from file: " . $orders_sql_file . "<br>";
    
    if (file_exists($orders_sql_file)) {
        $sql = file_get_contents($orders_sql_file);
        echo "<h3>Orders Table SQL:</h3>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre><br>";
        
        // Execute each statement separately
        $statements = explode(';', $sql);
        foreach($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $conn->exec($statement);
                echo "Executed: " . htmlspecialchars($statement) . "<br>";
            }
        }
        echo "Orders table created successfully!<br>";
        
        // Verify table structure
        $result = $conn->query("DESCRIBE orders");
        echo "<h3>Orders Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "ERROR: Orders SQL file not found!<br>";
    }
    
    // Create cart table
    $cart_sql_file = 'create_cart_table.sql';
    if (file_exists($cart_sql_file)) {
        $sql = file_get_contents($cart_sql_file);
        echo "<h3>Cart Table SQL:</h3>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre><br>";
        
        // Execute each statement separately
        $statements = explode(';', $sql);
        foreach($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $conn->exec($statement);
                echo "Executed: " . htmlspecialchars($statement) . "<br>";
            }
        }
        echo "Cart table created successfully!<br>";
        
        // Verify table structure
        $result = $conn->query("DESCRIBE cart");
        echo "<h3>Cart Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "ERROR: Cart SQL file not found!<br>";
    }
    
    // Create users table
    $users_sql_file = 'create_users_table.sql';
    if (file_exists($users_sql_file)) {
        $sql = file_get_contents($users_sql_file);
        echo "<h3>Users Table SQL:</h3>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre><br>";
        
        // Execute each statement separately
        $statements = explode(';', $sql);
        foreach($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $conn->exec($statement);
                echo "Executed: " . htmlspecialchars($statement) . "<br>";
            }
        }
        echo "Users table created successfully!<br>";
        
        // Verify table structure
        $result = $conn->query("DESCRIBE users");
        echo "<h3>Users Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "ERROR: Users SQL file not found!<br>";
    }
    
} catch(PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "</div>";
}
?>
