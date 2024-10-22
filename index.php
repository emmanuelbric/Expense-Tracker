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

// Fetch the savings data from the database
$sql = "SELECT current_balance, target_balance, weekly_income, deadline_date FROM savings LIMIT 1";
$result = $conn->query($sql);

// Initialize variables for displaying data
$current_balance = $target_balance = $weekly_income = $weeks_remaining = $deadline_date = $projected_balance = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_balance = $row['current_balance'];
    $target_balance = $row['target_balance'];
    $weekly_income = $row['weekly_income'];
    $deadline_date = $row['deadline_date'];

    // Calculate weeks remaining from today's date to the deadline date
    $current_date = new DateTime();
    $deadline = new DateTime($deadline_date);
    $interval = $current_date->diff($deadline);
    $weeks_remaining = floor($interval->days / 7);

    // Calculate the projected balance
    $projected_balance = $current_balance + ($weekly_income * $weeks_remaining);
} else {
    echo "No savings data found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
            body {
        font-family: 'Roboto', Arial, sans-serif;
        background-color: #f1e3c3;
        color: #333;
    }
    
    h1, h2 {
        color: #d94f5d;
        text-transform: uppercase;
    }
    
    .container {
        margin-top: 20px;
    }
    
    .btn-primary {
        background-color: #d94f5d;
        border-color: #d94f5d;
    }
    
    .btn-primary:hover {
        background-color: #c46970;
        border-color: #c46970;
    }
    
    .btn-success {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }
    
    .btn-success:hover {
        background-color: #45a049;
        border-color: #45a049;
    }
    
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Expense Tracker</h2>
        <div class="row">
            <div class="col-md-8">
                <a href="enter-details.html" class="btn btn-custom">Start tracking your expenses</a>
            </div>
            <div class="col-md-4">
                <div class="data-display">
                    <h3>Financial Overview</h3>
                    <p><strong>Current Balance:</strong> ₹<?php echo number_format($current_balance, 2); ?></p>
                    <p><strong>Target Balance:</strong> ₹<?php echo number_format($target_balance, 2); ?></p>
                    <p><strong>Weekly Income:</strong> ₹<?php echo number_format($weekly_income, 2); ?></p>
                    <p><strong>Weeks Remaining:</strong> <?php echo $weeks_remaining; ?> weeks</p>
                    <p><strong>Deadline Date:</strong> <?php echo date("F j, Y", strtotime($deadline_date)); ?></p>
                    <p><strong>Projected Balance by Deadline:</strong> ₹<?php echo number_format($projected_balance, 2); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
