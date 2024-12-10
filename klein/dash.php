
<?php
@include 'db.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finesente";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare response array
$response = [
    "wallet_balance" => 0,
    "savings_balance" => 0,
    "total_expenditure" => 0,
    "recent_expenses" => []
];

// Fetch wallet balance
$wallet_sql = "SELECT SUM(amount) AS total FROM wallet";
$wallet_result = $conn->query($wallet_sql);
$response["wallet_balance"] = $wallet_result->fetch_assoc()["total"] ?? 0;

// Fetch savings balance
$savings_sql = "SELECT SUM(amount) AS total FROM savings";
$savings_result = $conn->query($savings_sql);
$response["savings_balance"] = $savings_result->fetch_assoc()["total"] ?? 0;

// Fetch total expenditure
$expenses_sql = "SELECT SUM(amount) AS total FROM expenses";
$expenses_result = $conn->query($expenses_sql);
$response["total_expenditure"] = $expenses_result->fetch_assoc()["total"] ?? 0;

// Fetch recent expenses
$recent_sql = "SELECT date, category, description, amount FROM expenses ORDER BY date DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);
while ($row = $recent_result->fetch_assoc()) {
    $response["recent_expenses"][] = $row;
}

$conn->close();

// Pass data to JavaScript via JSON
echo json_encode(["success" => true, "data" => $response]);
?>


