<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
$uploadDir = 'uploads/';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    // $book_image = $_FILES['book_image']['name'];
    // $book_image = $_FILES['book_image']['name'];
    $book_name = $_POST['book_name'];
    $isbn = $_POST['isbn'];
    $author_name = $_POST['author_name'];
    $quantity = $_POST['quantity'];
    $available = isset($_POST['available']) ? 1 : 0;
    $Price = $_POST['Price']; // Collect the price from the form
    // echo $book_image;
    // echo $imagePath;
    // Validate form data (e.g., check if required fields are not empty)

    // Update book details in the database
    $conn = new mysqli("localhost", "root", "", "login_employee");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // $imagePath = $uploadDir . $book_image;
    // echo $imagePath;
    // Include the price in the SQL UPDATE statement
    // $sql = "UPDATE books SET book_image='$imagePath', book_name='$book_name', author_name='$author_name', quantity=$quantity, available=$available, Price=$Price WHERE isbn='$isbn'";
    $sql = "UPDATE books SET  book_name='$book_name', author_name='$author_name', quantity=$quantity, available=$available, Price=$Price WHERE isbn='$isbn'";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully";
    } else {
        echo "Error updating book: " . $conn->error;
    }

    $conn->close();
}
?>
