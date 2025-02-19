<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "patient_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}
	
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    $sql = "SELECT * FROM patients WHERE id = $patient_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Display patient details
        echo "<h1>Patient Details</h1>";
        echo "<p><strong>First Name:</strong> " . $row['firstname'] . "</p>";
        echo "<p><strong>Middle Name:</strong> " . $row['middlename'] . "</p>";
		echo "<p><strong>Last Name:</strong> " . $row['lastname'] . "</p>";
        echo "<p><strong>Age:</strong> " . $row['age'] . "</p>";
		echo "<p><strong>Date of Birth:</strong> " . $row['dob'] . "</p>";
		echo "<p><strong>Sex:</strong> " . $row['gender'] . "</p>";
        echo "<p><strong>Address:</strong> " . $row['address'] . "</p>";
		echo "<p><strong>Civil Status:</strong> " . $row['civil_status'] . "</p>";
		
        // ...
    } else {
        echo "Patient not found.";
    }
} else {
    echo "Invalid patient ID.";
}

$conn->close();
?>