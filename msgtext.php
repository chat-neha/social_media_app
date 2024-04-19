<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Text</title>
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

// Check if 'sender' parameter exists in the URL
if(isset($_GET['sender'])) {
    // Retrieve sender name from query parameter
    $sender = $_GET['sender'];

    // Fetch the user ID from the first entry of the login table
    $request = mysqli_query($conn, "SELECT user_id FROM login LIMIT 1");

    // Check if the query was successful
    if ($request) {
        $row = mysqli_fetch_assoc($request);
        $active_user = $row['user_id'];

        // Call the stored procedure to display the single message
        $stmt = "CALL displaysinglemessage('$sender', '$active_user')";
        $result = mysqli_query($conn, $stmt);

        if ($result === false) {
            // If the query was not successful, print an error message
            echo "Error displaying message.";
        } else {
            // Output the result with reply textbox
            echo "<h1>Message Text</h1>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>" . $row['sender_name'] . ": " . $row['text'] . "</p>";
            }
            // Add reply textbox outside the loop
            echo "<form method='post' action='sendmessage.php'>";
            echo "<label for='reply'>Reply:</label>";
            echo "<input type='text' id='reply' name='reply' placeholder='Your reply here'>";
            echo "<input type='hidden' name='sender_id' value='$active_user'>"; // Hidden field to store sender ID
            echo "<input type='hidden' name='recipient_id' value='$sender'>"; // Hidden field to store recipient ID
            echo "<input type='submit' value='Send'>";
            echo "</form>";
            echo "<br>";
        }
    } else {
        // If the query was not successful, display an error message
        echo "Error retrieving user ID: " . mysqli_error($conn);
    }
} else {
    // If 'sender' parameter is not found in the URL, display an error message
    echo "Sender parameter is missing.";
}

// Close connection
mysqli_close($conn);
?>
</body>
</html>
