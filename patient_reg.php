<?php


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "patient_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$edit_id = null;
$patient_data = array();
$filter_active = false; // Flag to track if filtering is active

// Edit (Retrieve data for editing)
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_sql = "SELECT * FROM patients WHERE id=$edit_id";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $patient_data = $edit_result->fetch_assoc(); // Store data in $patient_data
    } else {
        $error_msg = "Patient not found for editing.";
        $edit_id = null;
    }
}

// Save/insert
if (isset($_POST['create'])) {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $civil_status = $_POST['civil_status'];

    $sql = "INSERT INTO patients (firstname, middlename, lastname, age, dob, gender, address, civil_status) 
            VALUES ('$firstname', '$middlename', '$lastname', '$age', '$dob', '$gender', '$address', '$civil_status')";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "New record created successfully";
    } else {
        $error_msg = "Error: " . $sql . "<br>" . $conn->error;
    }
}


// Search
$search_term = "";
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
}

// Filter Logic
$where_clause = "";
$conditions = array();

// Combine Search and Filter
$search_conditions = array();
if (!empty($search_term)) {
  $search_conditions[] = "firstname LIKE '%$search_term%'";
  $search_conditions[] = "lastname LIKE '%$search_term%'";
  $search_conditions[] = "address LIKE '%$search_term%'"; // Add other fields if needed
}

if (!empty($search_conditions)) {
  $where_clause .= " WHERE (" . implode(" OR ", $search_conditions) . ")"; // Wrap search conditions
}



if (isset($_GET['filter_submit'])) {
    $filter_active = true;

    if (isset($_GET['filter_age_min']) && !empty($_GET['filter_age_min'])) {
        $age_min = $_GET['filter_age_min'];
        $conditions[] = "age >= $age_min";
    }

    if (isset($_GET['filter_age_max']) && !empty($_GET['filter_age_max'])) {
        $age_max = $_GET['filter_age_max'];
        $conditions[] = "age <= $age_max";
    }

    if (isset($_GET['filter_gender']) && !empty($_GET['filter_gender'])) {
        $gender = $_GET['filter_gender'];
        $conditions[] = "gender = '$gender'";
    }

    // ... (Add other filter conditions as needed)

    $operator = isset($_GET['filter_operator']) && $_GET['filter_operator'] == 'OR' ? "OR" : "AND";

    if (!empty($conditions)) {
      if (empty($where_clause)) {
        $where_clause .= " WHERE ";
      } else {
        $where_clause .= " AND "; // Combine with search
      }
        $where_clause .=  "(" . implode(" $operator ", $conditions) . ")"; // Wrap filter conditions
    }
}


$sql = "SELECT * FROM patients $where_clause";

$result = $conn->query($sql);



// Update (Handle the update operation)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $civil_status = $_POST['civil_status'];

    $sql = "UPDATE patients SET firstname='$firstname', middlename='$middlename', lastname='$lastname', age='$age', dob='$dob', gender='$gender', address='$address', civil_status='$civil_status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        
		
	$success_msg = "Record updated successfully";
	$sql = "Select * from patients";
	
    } else {
        $error_msg = "Error updating record: " . $conn->error;
    }
    $edit_id = null; // Reset edit mode after update
    $patient_data = array(); // Clear patient data
}



// Delete with confirmation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    ?>
    <script>
        if (confirm("Are you sure you want to delete this patient?")) {
            window.location.href = "patient_reg.php?confirm_delete=<?php echo $id; ?>";
        }
    </script>
    <?php
    exit();
}

// Confirm Delete (actual deletion happens here)
if (isset($_GET['confirm_delete'])) {
    $id = $_GET['confirm_delete'];
    $sql = "DELETE FROM patients WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_msg'] = "Record deleted successfully!"; // Store success message in session
    } else {
        $_SESSION['error_msg'] = "Error deleting record: " . $conn->error; // Store error message
    }
    header("Location: patient_reg.php"); // Redirect back to the list
    exit();
}



?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Registration</title>
<style>
  /* Basic styling for the form */
  form {
      width: 500px;
      margin: 10px;
  }
  label {
      display: block;
      margin-bottom: 2px;
  }
  input[type="text"], input[type="number"], input[type="date"], select {
      width: 100%;
      padding: 5px;
      margin-bottom: 5px;
      box-sizing: border-box;
  }
  input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      cursor: pointer;
  }
  table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
  }
  th, td {
      border: 1px solid #ddd;
      padding: 5px;
      text-align: center;
  }
  th {
    background-color: #f2f2f2;
  }
  .success {
    color: green;
  }

  .error {
    color: red;
  }
  .split {
	  height:100%;
	  width:50%;
	  position:fixed;
	  z-index:1;
	  top:0;
	  overflow-x:hidden;
	  padding-top:10px;
	}
	.left {
		left:0;
	}
	.right{
		right:0;
	}
	.filter-form { /* Style for the filter form */
            display: <?php echo $filter_active ? 'block' : 'none'; ?>; /* Initially hidden */
            margin-bottom: 5px;
            border: 1px solid #ccc;
            padding: 1px;
        }
	
</style>
</head>
<div class ="split left">

<body>
<h1>Patient Registration</h1>

<?php if (isset($success_msg)) { echo "<p class='success'>".$success_msg."</p>"; } ?>
<?php if (isset($error_msg)) { echo "<p class='error'>".$error_msg."</p>"; } ?>
<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>alert('" . $_SESSION['success_msg'] . "');</script>";
    unset($_SESSION['success_msg']); // Remove message after displaying it
}

if (isset($_SESSION['error_msg'])) {
    echo "<script>alert('" . $_SESSION['error_msg'] . "');</script>";
    unset($_SESSION['error_msg']); // Remove message after displaying it
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php if ($edit_id != null) : ?>
        <input type="hidden" name="id" value="<?php echo $patient_data['id']; ?>">
    <?php endif; ?>

    <label for="firstname">First Name:</label>
    <input type="text" name="firstname" id="firstname" value="<?php echo isset($patient_data['firstname']) ? $patient_data['firstname'] : ''; ?>" required><br><br>

    <label for="middlename">Middle Name:</label>
    <input type="text" name="middlename" id="middlename" value="<?php echo isset($patient_data['middlename']) ? $patient_data['middlename'] : ''; ?>"><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" name="lastname" id="lastname" value="<?php echo isset($patient_data['lastname']) ? $patient_data['lastname'] : ''; ?>" required><br><br>

    <label for="age">Age:</label>
    <input type="number" name="age" id="age" value="<?php echo isset($patient_data['age']) ? $patient_data['age'] : ''; ?>" required><br><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" id="dob" value="<?php echo isset($patient_data['dob']) ? $patient_data['dob'] : ''; ?>" required><br><br>

    <label for="gender">Gender:</label>
    <select name="gender" id="gender" required>
        <option value="Male" <?php if (isset($patient_data['gender']) && $patient_data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if (isset($patient_data['gender']) && $patient_data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if (isset($patient_data['gender']) && $patient_data['gender'] == 'Other') echo 'selected'; ?>>Other</option>
    </select><br><br>

    <label for="address">Address:</label>
    <input type="text" name="address" id="address" value="<?php echo isset($patient_data['address']) ? $patient_data['address'] : ''; ?>" required><br><br>

    <label for="civil_status">Civil Status:</label>
    <select name="civil_status" id="civil_status" required>
        <option value="Single" <?php if (isset($patient_data['civil_status']) && $patient_data['civil_status'] == 'Single') echo 'selected'; ?>>Single</option>
        <option value="Married" <?php if (isset($patient_data['civil_status']) && $patient_data['civil_status'] == 'Married') echo 'selected'; ?>>Married</option>
        <option value="Divorced" <?php if (isset($patient_data['civil_status']) && $patient_data['civil_status'] == 'Divorced') echo 'selected'; ?>>Divorced</option>
        <option value="Widowed" <?php if (isset($patient_data['civil_status']) && $patient_data['civil_status'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
    </select><br><br>

    <?php if ($edit_id == null) : ?>
        <input type="submit" name="create" value="Submit">
    <?php else : ?>
        <input type="submit" name="update" value="Update">
        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">Cancel Edit</a>
    <?php endif; ?>
</form>
</div>


<div class="split right">

<h2>Patient List</h2>

<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search" placeholder="Search...">
    <input type="submit" value="Search">
	
<input type ="submit" Value="Clear"> <a href="patient_reg.php"></a>
</form>



<button id="filter-toggle">Show/Hide Filter</button>
<div class="filter-form" id="filter-form">
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="filter_age_min">Age (Min):</label>
        <input type="number" name="filter_age_min" id="filter_age_min" value="<?php echo isset($_GET['filter_age_min']) ? $_GET['filter_age_min'] : ''; ?>"><br><br>

        <label for="filter_age_max">Age (Max):</label>
        <input type="number" name="filter_age_max" id="filter_age_max" value="<?php echo isset($_GET['filter_age_max']) ? $_GET['filter_age_max'] : ''; ?>"><br><br>

        <label for="filter_gender">Gender:</label>
        <select name="filter_gender" id="filter_gender">
            <option value="">All</option>
            <option value="Male" <?php if (isset($_GET['filter_gender']) && $_GET['filter_gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if (isset($_GET['filter_gender']) && $_GET['filter_gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if (isset($_GET['filter_gender']) && $_GET['filter_gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select><br><br>

        <label for="filter_operator">Filter Operator:</label>
        <select name="filter_operator" id="filter_operator">
            <option value="AND" <?php if (!isset($_GET['filter_operator']) || $_GET['filter_operator'] == 'AND') echo 'selected'; ?>>AND</option>
            <option value="OR" <?php if (isset($_GET['filter_operator']) && $_GET['filter_operator'] == 'OR') echo 'selected'; ?>>OR</option>
        </select><br><br>

        <input type="submit" name="filter_submit" value="Apply Filter">
        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">Clear Filter</a>
    </form>

</div>
<table>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Date of Birth</th>
        <th>Gender</th>
        <th>Address</th>
        <th>Civil Status</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"]. "</td>";
            echo "<td>" . $row["firstname"]. "</td>";
            echo "<td>" . $row["middlename"]. "</td>";
            echo "<td>" . $row["lastname"]. "</td>";
            echo "<td>" . $row["age"]. "</td>";
            echo "<td>" . $row["dob"]. "</td>";
            echo "<td>" . $row["gender"]. "</td>";
            echo "<td>" . $row["address"]. "</td>";
            echo "<td>" . $row["civil_status"]. "</td>";
            echo "<td><a href='?delete=" . $row["id"] . "'>Delete</a> | <a href='?edit=" . $row["id"] . "'>Edit</a></td>"; 
			echo "<td><a href='patient_details.php?id=" . $row["id"] . "'>View</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>0 results</td></tr>";
    }
    ?>
</table>
<script>
    const filterToggle = document.getElementById('filter-toggle');
    const filterForm = document.getElementById('filter-form');

    filterToggle.addEventListener('click', () => {
        filterForm.style.display = filterForm.style.display === 'block' ? 'none' : 'block';
    });
</script>



<script>
$(document).ready(function() {
    $('.view-patient').click(function() {
        var patientId = $(this).data('patient-id');

        $.ajax({
            url: 'get_patient_details.php?id=' + patientId, // Create this PHP file
            type: 'GET',
            success: function(response) {
                $('#patientDetails').html(response); // Load details into modal body
                $('#patientModal').modal('show'); // Show the modal
            }
        });
    });
});
</script>

</div>
</body>
</html>

<?php
$conn->close();
?>