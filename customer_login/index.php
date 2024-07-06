<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}

// Database connection
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_employee";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all books by default
$sql = "SELECT * FROM books WHERE available = 1";

// Check if search form is submitted
if(isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    // Modify SQL query to include search criteria
    $sql = "SELECT * FROM books WHERE available = 1 AND (book_name LIKE '%$searchTerm%' OR isbn LIKE '%$searchTerm%' OR author_name LIKE '%$searchTerm%')";
}

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<link rel="stylesheet" href="style_e.css">

<style>
    /* Custom CSS for Books Available */
    .book-container {
        background-color: #f8f9fa;/* Light grey background*/
        /* background-color: #ffc107; Light blue background */
        /* background-color: #c6bdb1; Light blue background */
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
        padding: 20px;
        margin-bottom: 20px;
        display:flex;
        justify-content: center;
        align-items: center;
        flex-direction:column;
        width:100%;
    }
    .book-container img {
    max-width: 100%; 
    max-height: 200px; 
    height: auto; 
    width: auto; 
    object-fit: cover; 
    border-radius: 5px;
}

    /* .book-container img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    } */

    .book-details {
        margin-top: 10px;
    }

    .book-details h2 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .book-details p {
        margin-bottom: 5px;
    }

    .book-details h3 {
        color: #007bff; /* Blue price */
        margin-bottom: 10px;
    }

    .add-to-cart-btn {
        background-color: #007bff; /* Blue button */
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .add-to-cart-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }
</style>

<title>User Dashboard</title>
</head>
<body>
<div class="container">
    <h1>Welcome to User Dashboard</h1>
    <div class="contain">
    <a href="" class="btn btn-primary mb-4 mt-4">Books Available</a>
</div>
    <form method="post" action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search by Book Name, ISBN, or Author" name="searchTerm">
            <button class="btn btn-outline-secondary" type="submit" name="search">Search</button>
        </div>
    </form>
    <a href="logout.php" class="btn btn-warning">Logout</a>
</div>


<h1 class="text-center">Books Available</h1>
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        // $uploadDir = "uploads/";
        $uploadDir = "";  // Adjust the path as needed
        while($row = $result->fetch_assoc()) {
            echo "<div class='book-container'>";
            // echo "<img src='" . $row['book_image'] . "' alt='" . $row['book_name'] . "'>";
            // $imagePath = $row['book_image'];
            // $imagePath = "uploads/" . $row['book_image'];
            
            $imagePath = $uploadDir . $row['book_image'];
            // echo $imagePath;
            if (file_exists($imagePath)) {
                // echo "<img src='" . $imagePath . "' alt='" . $row['book_name'] . "'>";
            } else {
                // echo "<img src='placeholder.jpg' alt='Image Not Available'>"; // Placeholder image or default image
            }

            echo "<img src='" . $imagePath . "' alt='" . $row['book_name'] . "'>";

            echo "<div class='book-details'>";
            echo "<h2>" . $row['book_name'] . "</h2>";
            echo "<p>ISBN: " . $row['isbn'] . "</p>";
            echo "<p>Author: " . $row['author_name'] . "</p>";
            echo "<p>Quantity: " . $row['quantity'] . "</p>";
            echo "<h3>Price: " . $row['Price'] . "rs</h3>";
            // echo "<button class='add-to-cart-btn'>Add to Cart</button>";
            echo "<button class='add-to-cart-btn' onclick='addToCart(" . $row['Price'] . ")'>Add to Cart</button>";

            echo "</div>"; // Closing book-details div
            echo "</div>"; // Closing book-container div
        }
    } else {
        echo "No books available.";
    }
    
// echo "<button class='add-to-cart-btn' onclick='add-to-cart-btn(" . $row['Price'] . ")'>Add to Cart</button>";


// <script>
// function addToCart(price) {
//     alert("Pay $" + price + " to get the book.");
// }
// </script>




    ?>
    <script>
function addToCart(price) {
    var alphabet = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // Generate a random alphabet (A-Z)
    var section = Math.floor(Math.random() * 100).toString().padStart(2, '0'); // Generate a two-digit number

    var location = alphabet + section; // Combine alphabet and section number

    alert("Pay " + price + "rs to get the book.");

    setTimeout(function() {
        alert("Book is at: " + location);
    }, 100); // Delay the location alert by 100 milliseconds
}


 </script>
</div>
</body>
<footer class="footer mt-5">
    <div class="container text-center">
        <p>&copy; <?php echo date("Y"); ?> BookShop Automation System. All Rights Reserved.</p>
        <p>Designed with by <a href="../about.html" target="_blank">Group 12</a></p>
    </div>
</footer>

</html>
