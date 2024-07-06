<?php
// Database connection parameters
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = ''; // Your database password
$dbname = 'login_employee'; // Your database name

// Create a new mysqli object
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the isbn parameter is set
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM books WHERE isbn = ?";
    $stmt = $mysqli->prepare($sql);

    // Bind the isbn parameter to the SQL statement
    $stmt->bind_param("s", $isbn);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Book deleted successfully.";
    } else {
        echo "Error deleting book: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
} else {
    echo "No ISBN provided.";
}
?>
