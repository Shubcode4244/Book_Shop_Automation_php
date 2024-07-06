<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}

// Check if ISBN number is provided in the URL
if(isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    
    // Retrieve book details from the database based on ISBN
    $conn = new mysqli("localhost", "root", "", "login_employee");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM books WHERE isbn='$isbn'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Populate form fields with retrieved book details
        // $book_image = $row['book_image'];
        // echo "hi".$book_image;
        $book_name = $row['book_name'];
        $isbn = $row['isbn'];
        $author_name = $row['author_name'];
        $quantity = $row['quantity'];
        $available = $row['available'];
        $Price = $row['Price'];
        // echo $row['Price'];
        
    } else {
        echo "Book not found.";
    }
    
    
    $conn->close();
} else {
    echo "ISBN number not provided.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="style_edit.css">
</head>
<body>
    <h1>Edit Book</h1>
    <form action="update_book.php" method="post" enctype="multipart/form-data">
        <!-- <label for="book_image">Book Image:</label> -->
        <!-- <input type="file" name="book_image" id="book_image" value="<?php echo $book_image; ?>"><br> -->
        <label for="book_name">Book Name:</label>
        <input type="text" name="book_name" id="book_name" value="<?php echo $book_name; ?>" ><br>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" value="<?php echo $isbn; ?>" required readonly><br>
        <label for="author_name">Author Name:</label>
        <input type="text" name="author_name" id="author_name" value="<?php echo $author_name; ?>" ><br>
        <label for="quantity">Quantity:</label>
        
        <input type="number" name="quantity" id="quantity" value="<?php echo $quantity; ?>"><br>
        <label for="available">Available:</label>
        <label for="Price">Price:</label>
        <input type="number" name="Price" id="Price" value="<?php echo $Price; ?>" ><br>

        <input type="checkbox" name="available" id="available" <?php echo $available ? 'checked' : ''; ?>><br>
        
        <input type="submit" value="Update Book">
    </form>
    


</body>
</html>
