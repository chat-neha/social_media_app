<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "vedika";
$database = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the active user
$request = mysqli_query($conn, "SELECT user_id FROM login");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the text parameter is set
        if (isset($_POST['text'])) {
            // Get the text input from the form
            $text = $_POST['text'];
            
            // Call the stored procedure makePost
            $stmt = $conn->prepare("CALL makePost(?, ?)");
            $stmt->bind_param("ss", $active_user, $text);

            // Execute the stored procedure
            if ($stmt->execute()) {
                echo "Post created successfully!";
            } else {
                echo "Error creating post: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }
    }
} else {
    // If the query was not successful, display an error message
    echo "Error retrieving user ID: " . mysqli_error($conn);
}

// Close connection
$conn->close();
?>

<h2>Create a New Post</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="text">Post Text:</label><br>
    <input type="text" id="text" name="text"><br><br>
    <input type="submit" value="Submit">
</form>

</body>
</html>
