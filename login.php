<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "<redacted>";
$dbname = "spgv";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process signup form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST["uname"]);
    $password = password_hash(sanitize_input($_POST["pword"]), PASSWORD_DEFAULT);
    $email = filter_var(sanitize_input($_POST["mail"]), FILTER_VALIDATE_EMAIL);
    $nnid = empty($_POST["nnid"]) ? null : sanitize_input($_POST["nnid"]);

    // Insert user data into the database using prepared statement
    $stmt = $conn->prepare("INSERT INTO user_info (username, password, email, nnid) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $nnid);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>

<h2>Signup Form</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="uname" required><br>
    Password: <input type="password" name="pword" required><br>
    Email: <input type="email" name="mail" required><br>
    Nintendo Network ID (optional): <input type="text" name="nnid"><br>
    <input type="submit" value="Signup">
</form>

</body>
</html>
