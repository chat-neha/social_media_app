<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
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

// Fetch the user ID from the first entry of the login table
$request = mysqli_query($conn, "SELECT user_id FROM login LIMIT 1");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];

    // Call the stored procedure to fetch messages for the active user
    $stmt = "CALL viewmessages('$active_user')";
    $result = mysqli_query($conn, $stmt);

    if ($result === false) {
        // If the query was not successful, print "No messages yet"
        echo "No messages yet.";
    } else {
        // Check if there are any rows
        if (mysqli_num_rows($result) > 0) {
            // Output data of each row in an HTML table
            echo "<table>";
            echo "<tr><th>Sender</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                // Create a hyperlink for the sender name
                $sender = $row["sender_name"];
                // Encode sender's name to ensure proper URL formatting
                $encoded_sender = urlencode($sender);
                // Include sender's name as a query parameter in the URL
                echo "<tr><td><a href='msgtext.php?sender=$encoded_sender'>$sender</a></td></tr>";
            }
            echo "</table>";
        } else {
            echo "No messages found.";
        }
        // Free result set
        mysqli_free_result($result);
    }
} else {
    // If the query was not successful, display an error message
    echo "Error retrieving user ID: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

</body>
</html>
