
<?php
@include 'db.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finesente";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Initialize response array
$response = [
    "wallet_balance" => 0,
    "savings_balance" => 0,
    "total_expenditure" => 0,
    "recent_expenses" => []
];

// Fetch wallet balance
$wallet_sql = "SELECT SUM(amount) AS total FROM wallet";
$wallet_result = $conn->query($wallet_sql);
if ($wallet_result) {
    $response["wallet_balance"] = $wallet_result->fetch_assoc()["total"] ?? 0;
} else {
    echo json_encode(["success" => false, "message" => "Error fetching wallet balance: " . $conn->error]);
    exit;
}

// Fetch savings balance
$savings_sql = "SELECT SUM(amount) AS total FROM savings";
$savings_result = $conn->query($savings_sql);
if ($savings_result) {
    $response["savings_balance"] = $savings_result->fetch_assoc()["total"] ?? 0;
} else {
    echo json_encode(["success" => false, "message" => "Error fetching savings balance: " . $conn->error]);
    exit;
}

// Fetch total expenditure
$expenses_sql = "SELECT SUM(amount) AS total FROM expenses";
$expenses_result = $conn->query($expenses_sql);
if ($expenses_result) {
    $response["total_expenditure"] = $expenses_result->fetch_assoc()["total"] ?? 0;
} else {
    echo json_encode(["success" => false, "message" => "Error fetching total expenditure: " . $conn->error]);
    exit;
}

// Fetch recent expenses
$recent_sql = "SELECT date, category, description, amount FROM expenses ORDER BY date DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);
if ($recent_result) {
    while ($row = $recent_result->fetch_assoc()) {
        $response["recent_expenses"][] = $row;
    }
} else {
    echo json_encode(["success" => false, "message" => "Error fetching recent expenses: " . $conn->error]);
    exit;
}

$conn->close();
echo json_encode(["success" => true, "data" => $response]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FineSente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #212529; /* Dark theme background */
            color: #f8f9fa; /* Light text */
        }
        .header {
            background-color: #343a40;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            color: #0d6efd;
            font-size: 2.5rem;
            margin: 0;
        }
        .menu-buttons button {
            margin-right: 10px;
            background-color: #0d6efd;
            border: none;
            color: white;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .menu-buttons button:hover {
            background-color: #87ceeb;
            color: #212529;
            transform: scale(1.05);
        }
        .main-content {
            padding: 30px;
        }
        .dashboard-heading {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .wallet, .summary, .savings, .expense-list {
            background-color: #343a40;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .wallet:hover, .summary:hover, .savings:hover, .expense-list:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #495057;
        }
        footer {
            background-color: #343a40;
            color: #adb5bd;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>FineSente</h1>
        <div class="menu-buttons">
            <button class="btn" onclick="location.href='userprofile.html'">My Profile</button>
            <button class="btn" onclick="location.href='addexpense.html'">Add Expense</button>
            <button class="btn" onclick="location.href='budget.html'">Budget</button>
            <button class="btn" onclick="location.href='report.html'">Reports</button>
            <button class="btn" onclick="location.href='settings.html'">Settings</button>
            <button class="btn" onclick="location.href='help.html'">Help/Support</button>
            <button class="btn" onclick="location.href='logout.html'">Logout</button>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="dashboard-heading">Dashboard</h2>
        <p>Welcome back!</p>

        <div class="dashboard">
            <!-- Wallet Section -->
            <div class="wallet">
                <h3>Wallet Balance</h3>
                <p id="walletBalance">UGX 0</p>
            </div>

            <!-- Savings Section -->
            <div class="savings">
                <h3>Savings</h3>
                <p id="savingsBalance">UGX 0</p>
            </div>

            <!-- Summary Section -->
            <div class="summary">
                <h3>Total Expenditure</h3>
                <p id="totalExpenditure">UGX 0</p>
            </div>

            <!-- Expense List -->
            <div class="expense-list">
                <h3>Recent Expenses</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount (UGX)</th>
                        </tr>
                    </thead>
                    <tbody id="expenseList">
                        <!-- Dynamic Data -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 FineSente. All Rights Reserved.
    </footer>
    <script>
 function updateDashboard() {
    fetch('dash.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const { wallet_balance, savings_balance, total_expenditure, recent_expenses } = data.data;

                // Update Wallet Balance
                document.getElementById("walletBalance").textContent = `UGX ${wallet_balance.toLocaleString()}`;
                // Update Savings Balance
                document.getElementById("savingsBalance").textContent = `UGX ${savings_balance.toLocaleString()}`;
                // Update Total Expenditure
                document.getElementById("totalExpenditure").textContent = `UGX ${total_expenditure.toLocaleString()}`;
                // Populate Recent Expenses
                const expenseList = document.getElementById("expenseList");
                expenseList.innerHTML = recent_expenses.map(expense => `
                    <tr>
                        <td>${expense.date}</td>
                        <td>${expense.category}</td>
                        <td>${expense.description}</td>
                        <td>UGX ${parseFloat(expense.amount).toLocaleString()}</td>
                    </tr>
                `).join("");
            } else {
                console.error("Error fetching dashboard data:", data.message);
                alert("Failed to load dashboard data.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while fetching data.");
        });
}

// Call updateDashboard on page load
document.addEventListener("DOMContentLoaded", updateDashboard);

</script>


    
</body>
</html>
