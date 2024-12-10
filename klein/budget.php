<?php
// Database connection
@include 'db.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finesente";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$expense_name = $_POST['expenseName'] ?? '';
$expense_amount = $_POST['expenseAmount'] ?? '';
$user_id = $_POST['userId'] ?? ''; // Ensure this value is dynamically passed or hardcoded for now

// Validate inputs
if (empty($expense_name) || empty($expense_amount) || empty($user_id)) {
    die("Error: All fields are required.");
}

// Insert data into the `budget` table
$sql = "INSERT INTO budgets (user_id, expense_name, amount, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("isd", $user_id, $expense_name, $expense_amount);
    if ($stmt->execute()) {
        echo "Budget added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
