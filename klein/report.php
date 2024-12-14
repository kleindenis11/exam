<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finesente"; // Update with your actual DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expenseName = $_POST['expenseName'];
    $expenseAmount = $_POST['expenseAmount'];
    $userId = $_POST['userId'];  // You may need to dynamically get this value

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO expenses (expense_name, amount, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $expenseName, $expenseAmount, $userId); // "ssi" for string, string, integer

    if ($stmt->execute()) {
        // Successful insertion
        echo json_encode(['success' => true]);
    } else {
        // Error handling
        echo json_encode(['success' => false, 'message' => 'Failed to add expense.']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
