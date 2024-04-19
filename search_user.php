<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search</title>
</head>
<body>

<h2>User Search</h2>
<form method="post">
    <label for="username">Enter Username:</label>
    <input type="text" id="username" name="username" required>
    <button type="submit" name="search">Search</button>
</form>

<?php
// Database connection details
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

// Extract active user
$request = mysqli_query($conn, "SELECT user_id FROM login");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];
}

// Check if the search button is clicked
if(isset($_POST['search'])) {
    // Retrieve the username entered by the user
    $searchUsername = $_POST['username'];

    // Call the searchUser stored procedure
    $stmt = $conn->prepare("CALL searchUser(?)");
    $stmt->bind_param("s", $searchUsername); // Bind the parameter
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    // Check if there are any rows
    if ($result->num_rows > 0) {
        // Fetch each row from the result set
        while ($row = $result->fetch_assoc()) {
            // Process each row
            // For example, you can echo or use the values here
            $userId = $row['user_id'];

            // Display user profile
            echo "<p>User ID: " . $row['user_id'] . "</p>";
            echo "<p>Bio: " . $row['bio'] . "</p>";
            echo "<p>Followers: " . $row['num_followers'] . "</p>";
            echo "<p>Following: " . $row['num_following'] . "</p>";
            echo "<p>Posts: " . $row['num_posts'] . "</p>";

            // Add textbox for sending message
            echo "<form method='post'>";
            echo "<input type='hidden' name='receiver_id' value='$userId'>";
            echo "<input type='hidden' name='active_user' value='$active_user'>";
            echo "<label for='message'>Write a message:</label>";
            echo "<input type='text' id='message' name='message'>";
            echo "<button type='submit' name='send_message'>Send Message</button>";
            echo "</form>";
        }
        // Free the result set
        $result->free();
    } else {
        echo "No user found.";
    }
}

// Check if the send message button is clicked
if(isset($_POST['send_message'])) {
    // Retrieve the parameters
    $sender = $active_user;
    $receiver = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Call the sendMessage stored procedure
    $stmt = $conn->prepare("CALL sendMessage(?, ?, ?)");
    $stmt->bind_param("sss", $sender, $receiver, $message); // Bind the parameters
    $stmt->execute();
    $stmt->close(); // Close the statement
}

// Close the database connection
$conn->close();
?>

</body>
</html>
