<?php
// Database connection details
$servername = "localhost"; // Change if needed
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "expense_tracker"; // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for savings
$current_balance = isset($_POST['current_balance']) ? $_POST['current_balance'] : 0;
$target_balance = 0; // Initialize target_balance to avoid undefined variable warning
$weekly_income = isset($_POST['weekly_income']) ? $_POST['weekly_income'] : 0;
$deadline_date = '';

// Handle savings balance update or insert
if (isset($_POST['set_balances'])) {
    // Check if record exists in the savings table
    $check_sql = "SELECT * FROM savings LIMIT 1";
    $result = $conn->query($check_sql);

    $weekly_income = isset($_POST['weekly_income']) ? $_POST['weekly_income'] : 0;

    // Calculate weeks_remaining if target amount and deadline are provided
    if (!empty($_POST['target_amount']) && !empty($_POST['deadline_date'])) { 
        $target_balance = $_POST['target_amount'];
        $deadline_date = $_POST['deadline_date'];

        // Calculate weeks remaining from today's date to the deadline date
        $current_date = new DateTime();
        $deadline = new DateTime($deadline_date);
        $interval = $current_date->diff($deadline);
        $weeks_remaining = floor($interval->days / 7);
    } else {
        // Set weeks_remaining to 0 if not provided
        $weeks_remaining = 0;
    }

    // Update or insert into the savings table
    if ($result->num_rows > 0) {
        // Record exists, perform update
        $sql = "UPDATE savings SET 
                    current_balance = '$current_balance',
                    target_balance = '$target_balance',
                    weekly_income = '$weekly_income',
                    weeks_remaining = '$weeks_remaining',
                    deadline_date = '$deadline_date'";
    } else {
        // No record exists, perform insert
        $sql = "INSERT INTO savings (current_balance, target_balance, weekly_income, weeks_remaining, deadline_date) 
                VALUES ('$current_balance', '$target_balance', '$weekly_income', '$weeks_remaining', '$deadline_date')";
    }

    // Execute the SQL query for savings
    if ($conn->query($sql) === TRUE) {
        echo "Savings record updated successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle transactions (expenses and income)
if (isset($_POST['expense_amount']) && !empty($_POST['expense_amount'])) {
    // Retrieve current balance from savings
    $current_balance_query = "SELECT current_balance FROM savings LIMIT 1";
    $balance_result = $conn->query($current_balance_query);
    if ($balance_result->num_rows > 0) {
        $current_balance = $balance_result->fetch_assoc()['current_balance'];
    }

    $amount = $_POST['expense_amount'];
    $new_balance = $current_balance - $amount;

    // Insert the expense transaction
    $sql = "INSERT INTO transactions (type, amount, balance_after, created_at) 
            VALUES ('expense', '$amount', '$new_balance', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Expense recorded successfully.<br>";
        // Update the current balance in the savings table
        $update_savings_sql = "UPDATE savings SET current_balance = '$new_balance' WHERE id = (SELECT id FROM savings LIMIT 1)";
        if ($conn->query($update_savings_sql) === TRUE) {
            echo "Savings balance updated successfully.<br>";
        } else {
            echo "Error updating savings balance: " . $conn->error . "<br>";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['income_amount']) && !empty($_POST['income_amount'])) {
    // Retrieve current balance from savings
    $current_balance_query = "SELECT current_balance FROM savings LIMIT 1";
    $balance_result = $conn->query($current_balance_query);
    if ($balance_result->num_rows > 0) {
        $current_balance = $balance_result->fetch_assoc()['current_balance'];
    }

    $amount = $_POST['income_amount'];
    $new_balance = $current_balance + $amount;

    // Insert the income transaction
    $sql = "INSERT INTO transactions (type, amount, balance_after, created_at) 
            VALUES ('income', '$amount', '$new_balance', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Income recorded successfully.<br>";
        // Update the current balance in the savings table
        $update_savings_sql = "UPDATE savings SET current_balance = '$new_balance' WHERE id = (SELECT id FROM savings LIMIT 1)";
        if ($conn->query($update_savings_sql) === TRUE) {
            echo "Savings balance updated successfully.<br>";
        } else {
            echo "Error updating savings balance: " . $conn->error . "<br>";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
