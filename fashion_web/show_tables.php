<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=fashion_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Current Database Tables</h2>";
    
    // Show all tables
    $result = $conn->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in fashion_db:</h3>";
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
        echo "</li>";
    }
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "</div>";
}
?>
