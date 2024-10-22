<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the transaction data from the database
$sql = "SELECT id, type, amount, balance_after, created_at FROM transactions ORDER BY created_at DESC";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense-Income Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f1e3c3;
            color: #333;
        }
        h1, h2, h3 {
            color: #d94f5d;
            text-transform: uppercase;
        }
        .custom-table {
            background-color: #e7ad96;
        }
        .custom-table th, .custom-table td {
            color: #333;
        }
        .btn-custom-primary {
            background-color: #d08082;
            border-color: #d08082;
            height: 38px;
            color: white;
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-custom-primary:hover {
            background-color: #c46970;
            border-color: #c46970;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-custom-secondary {
            background-color: #d94f5d;
            border-color: #d94f5d;
            height: 38px;
            color: white;
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-custom-secondary:hover {
            background-color: #b0404d;
            border-color: #b0404d;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        .custom-input {
            border: 1px solid #d94f5d;
            height: 38px;
            border-radius: 20px;
            padding-left: 15px;
            width: 100%;
        }
        .custom-input:focus {
            border-color: #d08082;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
        .navbar-custom {
            background-color: #d94f5d;
        }
        .navbar-brand-custom {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1.5em;
            color: white !important;
        }
        .input-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Expense Tracker</h2>
            <form method="POST" action="db.php">
                <div class="form-group">
                    <label for="expense-input">Expense Amount:</label>
                    <input type="number" step="0.01" class="form-control" id="expense-input" name="expense_amount">
                </div>
                <button type="submit" class="btn btn-danger" name="add_expense">Add Expense</button>

                <div class="form-group mt-3">
                    <label for="income-input">Income Amount:</label>
                    <input type="number" step="0.01" class="form-control" id="income-input" name="income_amount">
                </div>
                <button type="submit" class="btn btn-success" name="add_income">Add Income</button>
            </form>
        <h2>Expense-Income Tracker</h2>
        <div class="transaction-table">
            <h3>Transaction History</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance After</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . ucfirst($row['type']) . "</td>";
                            echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                            echo "<td>₹" . number_format($row['balance_after'], 2) . "</td>";

                            echo "<td>" . date("F j, Y, g:i a", strtotime($row['created_at'])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No transactions found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="enter-details.html" class="btn btn-secondary mt-3">Back to enter details</a>
        <a href="index.php" class="btn btn-secondary mt-3">Back to home</a>

    </div>
</body>
</html>

