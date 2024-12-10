<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "finesente";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']); // Assuming user_id is sent via POST
    $expense_name = $conn->real_escape_string($_POST['expense_name']);
    $amount = floatval($_POST['amount']);

    // Insert the budget data into the database
    $sql = "INSERT INTO budgets (user_id, expense_name, amount) VALUES ('$user_id', '$expense_name', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Budget added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense - FineSente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212; /* Bootstrap dark theme background */
            color: #f1f1f1;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: #1f1f1f; /* Darker sidebar */
            width: 250px;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar h2 {
            color: #0d6efd; /* Bootstrap primary color */
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #f1f1f1;
            text-decoration: none;
            font-size: 1.1rem;
            display: block;
            transition: color 0.3s ease;
        }

        .sidebar ul li a:hover, .sidebar ul li a.active {
            color: #0d6efd; /* Highlight active links */
        }

        .logout {
            margin-top: 30px;
            display: block;
            color: #0d6efd;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .logout:hover {
            color: #0a58ca;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #0d6efd;
        }

        .budget-form {
            background-color: #1f1f1f; /* Match sidebar theme */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }

        .budget-form input, .budget-form button {
            margin-bottom: 10px;
            color: #f1f1f1;
        }

        .budget-form button {
            background-color: #0d6efd;
            border: none;
        }

        .budget-form button:hover {
            background-color: #0a58ca;
        }

        /* Footer Styles */
        footer {
            background-color: #1f1f1f;
            color: #a1a1a1;
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>FineSente</h2>
        <ul>
            <li><a href="dash.html">Dashboard</a></li>
            <li><a href="userprofile.html">My Profile</a></li>
            <li><a href="addexpense.html" class="active">Add Expense</a></li>
            <li><a href="report.html">Reports</a></li>
            <li><a href="settings.html">Settings</a></li>
            <li><a href="help.html">Help/Support</a></li>
        </ul>
        <a href="logout.html" class="logout">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Weekly budget</h1>
        </div>

        <!-- Budget Form -->
        <div class="budget-form">
            <h3>Set Weekly Budget</h3>
            <form id="budgetForm">
                <div class="mb-3">
                    <input type="text" id="expenseName" class="form-control" placeholder="Enter Expense Name (e.g., Food, Entertainment)" required>
                </div>
                <div class="mb-3">
                    <input type="number" id="expenseAmount" class="form-control" placeholder="Enter Amount (UGX)" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Expense</button>
            </form>
        </div>

        <!-- Expenses Table -->
        <div class="expenses-table">
            <h3>Weekly Expenses</h3>
            <table id="expensesTable" class="table table-dark table-hover mt-3">
                <thead>
                    <tr>
                        <th>Expense Name</th>
                        <th>Amount (UGX)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic content will be added here -->
                </tbody>
            </table>
        </div>
    </div>

   

    <!-- Bootstrap Bundle JS -->
    <script>
    const budgetForm = document.getElementById('budgetForm');
    const expensesTable = document.getElementById('expensesTable').querySelector('tbody');

    budgetForm.addEventListener('submit', function(event) {
        event.preventDefault();

        // Get input values
        const expenseName = document.getElementById('expenseName').value;
        const expenseAmount = document.getElementById('expenseAmount').value;

        // Send data to PHP via AJAX
        fetch('budget.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                user_id: 1, // Replace with actual user ID
                expense_name: expenseName,
                amount: expenseAmount,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add row to table
                const newRow = document.createElement('tr');
                newRow.innerHTML = `<td>${expenseName}</td><td>${Number(expenseAmount).toLocaleString()}</td>`;
                expensesTable.appendChild(newRow);

                // Clear form inputs
                budgetForm.reset();
                alert(data.message);
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    });
</script>

</body>
</html>
