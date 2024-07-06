<?php
session_start();

// Database connection
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_employee";

$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
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

// Check if "Add to Cart" button is clicked
if (isset($_POST['addToCart'])) {
    $bookId = $_POST['bookId'];
    // Add the book ID to the session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    array_push($_SESSION['cart'], $bookId);
}

// Check if "Generate Bill" button is clicked
if (isset($_POST['generateBill'])) {
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Bill");
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->AddPage();

    $content = '<h3 align="center">Bill Details</h3><br /><br />';
    $content .= '<table border="1" cellspacing="0" cellpadding="5">';
    $content .= '<tr><th width="5%">ID</th><th width="30%">Name</th><th width="10%">Price</th></tr>';

    foreach ($_SESSION['cart'] as $bookId) {
        $sql = "SELECT * FROM books WHERE id = $bookId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $content .= '<tr><td>' . $row["id"] . '</td><td>' . $row["book_name"] . '</td><td style="color: red;">' . $row["Price"] . '</td></tr>';
            }
        }
    }

    $content .= '</table>';
    $pdf->writeHTML($content);
    $pdf->Output('bill.pdf', 'D');

    // Clear the cart
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Management System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
</head>
<body>
    <div class="container">
        <h1>Books Available</h1>
        <form method="post" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search by Book Name, ISBN, or Author" name="searchTerm">
                <button class="btn btn-outline-secondary" type="submit" name="search">Search</button>
            </div>
        </form>
        <table class="table table-bordered">
            <tr>
                <th width="5%">ID</th>
                <th width="30%">Name</th>
                <th width="10%">Price</th>
                <th width="10%">Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr><td>' . $row["id"] . '</td><td>' . $row["book_name"] . '</td><td>' . $row["Price"] . '</td><td><button type="submit" name="addToCart" value="' . $row["id"] . '">Add to Cart</button></td></tr>';
                }
            } else {
                echo "No books available.";
            }
            ?>
        </table>
        <form method="post" action="">
            <input type="submit" name="generateBill" class="btn btn-danger" value="Generate Bill" />
        </form>
    </div>
</body>

</html>
