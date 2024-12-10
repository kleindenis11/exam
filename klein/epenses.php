<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$dbname = "finestente";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$description = $_POST['description'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$category = $_POST['category'];
$user_id = $_POST['user_id'];

// Insert query
$sql = "INSERT INTO expenses (date,category,description, amount,   user_id)
        VALUES ('$date','$category','$description', '$amount',   '$user_id')";

if ($conn->query($sql) === TRUE) {
    echo "New expense added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense - FineSente</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #f1f1f1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            background-color: #1f1f1f;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
        }

        nav .nav-title {
            font-size: 1.5rem;
            color: #0d6efd;
        }

        nav .nav-links a {
            color: #f1f1f1;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        nav .nav-links a:hover {
            background-color: #0d6efd;
            color: white;
        }

        .main-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .form-section {
            background-color: #1f1f1f;
            padding: 20px;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #0d6efd;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            background-color: #444;
            color: #f1f1f1;
            font-size: 1rem;
        }

        button {
            background-color: #0d6efd;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #1f1f1f;
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-title">FineSente</div>
        <div class="nav-links">
            <a href="dash.html">Dashboard</a>
            <a href="userprofile.html">My Profile</a>
            <a href="budget.html">Budget</a>
            <a href="report.html">Reports</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-section">
            <h2>Add Expense</h2>
            <form>
                <label for="date">Date</label>
                <input type="date" id="date" required>

                <label for="category">Category</label>
                <select id="category" required>
                    <option value="">--Select Category--</option>
                    <option value="Food">Food</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Tuition">Tuition</option>
                    <option value="Others">Others</option>
                </select>

                <label for="description">Description</label>
                <input type="text" id="description" placeholder="e.g., Lunch at cafe" required>

                <label for="amount">Amount</label>
                <input type="number" id="amount" placeholder="e.g., 50" required>

                <button type="button" onclick="saveExpense()">Save Expense</button>
            </form>
        </div>

        <div class="form-section">
            <h2>Deposit</h2>
            <form>
                <label for="depositAmount">Amount</label>
                <input type="number" id="depositAmount" placeholder="Enter deposit amount" required>
                <button type="button" onclick="addDeposit('wallet')">Add to Wallet</button>
                <button type="button" onclick="addDeposit('savings')">Add to Savings</button>
            </form>
        </div>

        <div class="form-section">
            <h2>Withdraw from Savings</h2>
            <form>
                <label for="withdrawAmount">Amount</label>
                <input type="number" id="withdrawAmount" placeholder="Enter amount to withdraw" required>
                <button type="button" onclick="withdrawFromSavings()">Withdraw</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 FineSente. All Rights Reserved.
    </footer>

    <script>
        // Initialize data if not present
        if (!localStorage.getItem("walletBalance")) localStorage.setItem("walletBalance", "0");
        if (!localStorage.getItem("savingsBalance")) localStorage.setItem("savingsBalance", "0");
        if (!localStorage.getItem("expenses")) localStorage.setItem("expenses", JSON.stringify([]));

        function saveExpense() {
            const date = document.getElementById('date').value;
            const category = document.getElementById('category').value;
            const description = document.getElementById('description').value;
            const amount = parseFloat(document.getElementById('amount').value);

            if (date && category && description && amount > 0) {
                const expenses = JSON.parse(localStorage.getItem("expenses"));
                expenses.push({ date, category, description, amount });
                localStorage.setItem("expenses", JSON.stringify(expenses));

                let walletBalance = parseFloat(localStorage.getItem("walletBalance"));
                walletBalance -= amount;
                localStorage.setItem("walletBalance", walletBalance);

                alert("Expense added successfully!");
                location.href = "dash.html";
            } else {
                alert("Fill in all fields correctly.");
            }
        }

        function addDeposit(type) {
            const depositAmount = parseFloat(document.getElementById('depositAmount').value);

            if (depositAmount > 0) {
                const balanceKey = type === "wallet" ? "walletBalance" : "savingsBalance";
                const currentBalance = parseFloat(localStorage.getItem(balanceKey));
                localStorage.setItem(balanceKey, currentBalance + depositAmount);

                alert(`${depositAmount} added to ${type}!`);
                location.href = "dash.html";
            } else {
                alert("Invalid deposit amount.");
            }
        }

        function withdrawFromSavings() {
            const withdrawAmount = parseFloat(document.getElementById('withdrawAmount').value);
            const savingsBalance = parseFloat(localStorage.getItem("savingsBalance"));

            if (withdrawAmount > 0 && withdrawAmount <= savingsBalance) {
                const walletBalance = parseFloat(localStorage.getItem("walletBalance"));

                localStorage.setItem("savingsBalance", savingsBalance - withdrawAmount);
                localStorage.setItem("walletBalance", walletBalance + withdrawAmount);

                alert(`${withdrawAmount} withdrawn to wallet!`);
                location.href = "dash.html";
            } else {
                alert("Invalid or insufficient savings balance.");
            }
        }
    </script>
</body>
</html>
