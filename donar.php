<?php
$servername = "localhost";
$username = "root"; // Change as per your database credentials
$password = ""; // Change as per your database credentials
$dbname = "blood_donation"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $age = isset($_POST['age']) ? trim($_POST['age']) : '';
    $bloodGroup = isset($_POST['bloodGroup']) ? trim($_POST['bloodGroup']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

    if(empty($name) || empty($age) || empty($bloodGroup) || empty($contact)) {
        die("Error: All fields are required.");
    }

    // Validate age as a number
    if (!is_numeric($age) || $age <= 0) {
        die("Error: Invalid age.");
    }

    // Validate blood group
    $validBloodGroups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
    if (!in_array($bloodGroup, $validBloodGroups)) {
        die("Error: Invalid blood group.");
    }

    // Validate contact number
    if (!preg_match("/^[0-9]{10}$/", $contact)) {
        die("Error: Invalid contact number.");
    }

    $sql = "INSERT INTO donors (name, age, blood_group, contact) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $name, $age, $bloodGroup, $contact);

    if ($stmt->execute()) {
        echo "Donor registration successful!";
        header("Location: index.html"); // Redirect to home page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>