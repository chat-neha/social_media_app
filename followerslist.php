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


// Fetch the user ID from the login table
$request = mysqli_query($conn, "SELECT user_id FROM login");


// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];


    // Call the stored procedure to fetch followers for the active user
    $stmt = $conn->prepare("CALL GetFollowers(?)");
    $stmt->bind_param("s", $active_user); // Bind the parameter
    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        // Output followers of the active user
        echo "Followers of User ID $active_user:<br>";
        while ($row = $result->fetch_assoc()) {
            echo $row['follower_id'] . "<br>";
        }
    } else {
        echo "No followers found for User ID $active_user.";
    }


    // Free result set
    $result->free();


    // Close statement
    $stmt->close();
} else {
    // If the query was not successful, display an error message
    echo "Error retrieving user ID: " . mysqli_error($conn);
}


// Close connection
mysqli_close($conn);
?>
