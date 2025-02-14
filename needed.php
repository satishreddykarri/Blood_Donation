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
    if (!isset($_POST['bloodGroup']) || empty(trim($_POST['bloodGroup']))) {
        die("Error: Blood group is required.");
    }

    $bloodGroup = trim($_POST['bloodGroup']);

    // Validate blood group
    $validBloodGroups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
    if (!in_array($bloodGroup, $validBloodGroups)) {
        die("Error: Invalid blood group.");
    }

    $sql = "SELECT name, age, blood_group, contact FROM donors WHERE blood_group = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bloodGroup);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Available Donors</h2>";
        echo "<table border='1'><tr><th>Name</th><th>Age</th><th>Blood Group</th><th>Contact</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . htmlspecialchars($row["age"]) . "</td><td>" . htmlspecialchars($row["blood_group"]) . "</td><td>" . htmlspecialchars($row["contact"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No donors found for blood group: " . htmlspecialchars($bloodGroup);
    }
    
    $stmt->close();
}

$conn->close();
?>
