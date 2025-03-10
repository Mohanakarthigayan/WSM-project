<?php
// Database credentials
$host = 'localhost';
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$database = 'maintenance'; // Updated database name

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// Function to add a new student
function addStudent($conn, $student_name, $register_number, $department, $age, $gender, $address, $phone_number) {
    // SQL query to insert data into the students table
    $sql = "INSERT INTO students (student_name, register_number, department, age, gender, address, phone_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $student_name, $register_number, $department, $age, $gender, $address, $phone_number);
    
    // Execute the query
    if ($stmt->execute()) {
        echo "New student added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<?php
// Function to update student information
function updateStudent($conn, $id, $student_name, $register_number, $department, $age, $gender, $address, $phone_number) {
    // SQL query to update student data
    $sql = "UPDATE students SET student_name=?, register_number=?, department=?, age=?, gender=?, address=?, phone_number=? WHERE id=?";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi', $student_name, $register_number, $department, $age, $gender, $address, $phone_number, $id);
    
    // Execute the query
    if ($stmt->execute()) {
        echo "Student updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<?php
// Function to get a student by ID
function getStudentById($conn, $id) {
    // SQL query to fetch the student data
    $sql = "SELECT * FROM students WHERE id = ?";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // 'i' stands for integer type
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // Fetch the student as an associative array
}
?>
<?php
// Include database connection and functions
include('db_connection.php');
include('functions.php');

// Handle the creation of a new student
if (isset($_POST['create'])) {
    // Get the form values
    $student_name = $_POST['student_name'];
    $register_number = $_POST['register_number'];
    $department = $_POST['department'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Call the addStudent function to store the data
    addStudent($conn, $student_name, $register_number, $department, $age, $gender, $address, $phone_number);
}

// Handle student update
if (isset($_POST['update'])) {
    // Get the form values
    $id = $_GET['edit']; // The student ID to be updated
    $student_name = $_POST['student_name'];
    $register_number = $_POST['register_number'];
    $department = $_POST['department'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Call the updateStudent function to update the data
    updateStudent($conn, $id, $student_name, $register_number, $department, $age, $gender, $address, $phone_number);
}
?>

<?php
// Handling the creation of a new student
if (isset($_GET['create'])) {
    ?>
    <div class="form-container">
        <form method="POST" action="">
            <input type="text" name="student_name" placeholder="Student Name" required>
            <input type="text" name="register_number" placeholder="Register Number" required>  
            <input type="text" name="department" placeholder="Department" required>
            <input type="number" name="age" placeholder="Age" required>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <textarea name="address" placeholder="Address"></textarea>
            <input type="tel" name="phone_number" placeholder="Phone Number">
            <button type="submit" name="create">Create Student</button>
        </form>
    </div>
    <?php
} elseif (isset($_GET['edit'])) {
    // Handling editing of an existing student
    $id = $_GET['edit'];
    $student = getStudentById($conn, $id);
    ?>
    <div class="form-container">
        <form method="POST" action="">
            <input type="text" name="student_name" placeholder="Student Name" required value="<?php echo $student['student_name']; ?>">
            <input type="text" name="register_number" placeholder="Register Number" required value="<?php echo $student['register_number']; ?>">  
            <input type="text" name="department" placeholder="Department" required value="<?php echo $student['department']; ?>">
            <input type="number" name="age" placeholder="Age" required value="<?php echo $student['age']; ?>">
            <select name="gender" required>
                <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $student['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <textarea name="address" placeholder="Address"><?php echo $student['address']; ?></textarea>
            <input type="tel" name="phone_number" placeholder="Phone Number" value="<?php echo $student['phone_number']; ?>">
            <button type="submit" name="update">Update Student</button>
        </form>
    </div>
    <?php
}
?>
