<?php
try {
    // Create initial connection without database
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Setting up fashion e-commerce database...</h2>";
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS fashion_db");
    $conn->exec("USE fashion_db");
    echo "Database created and selected<br><br>";
    
    // Read and execute the SQL file
    $sql = file_get_contents('database_tables.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach($statements as $statement) {
        if (!empty($statement)) {
            try {
                $conn->exec($statement);
                echo "<div style='color: green;'>Successfully executed: " . substr($statement, 0, 50) . "...</div>";
            } catch(PDOException $e) {
                echo "<div style='color: red;'>Error executing: " . substr($statement, 0, 50) . "...</div>";
                echo "<div style='color: red;'>Error message: " . $e->getMessage() . "</div><br>";
            }
        }
    }
    
    // Show all tables
    $result = $conn->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Created Tables:</h3>";
    echo "<ul>";
    foreach($tables as $table) {
        echo "<li><strong>$table</strong>";
        
        // Show table structure
        $describe = $conn->query("DESCRIBE `$table`");
        echo "<table border='1' style='margin: 10px;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $describe->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // Show foreign keys
        $show_create = $conn->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        if (preg_match_all("/CONSTRAINT `([^`]+)` FOREIGN KEY/", $show_create['Create Table'], $matches)) {
            echo "<p>Foreign Keys: " . implode(", ", $matches[1]) . "</p>";
        }
        
        echo "</li>";
    }
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "</div>";
}
?>
