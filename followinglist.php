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


$request = mysqli_query($conn, "SELECT user_id FROM login");


// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];


    // Call the stored procedure to fetch users being followed by the provided user_id
    $stmt = $conn->prepare("CALL GetFollowingList(?)");
    $stmt->bind_param("s", $active_user); // Bind the parameter
    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        // Output users being followed by the provided user_id
        echo "Users followed by User ID $active_user:<br>";
        while ($row = $result->fetch_assoc()) {
            echo $row['user_id'] . "<br>";
        }
    } else {
        echo "No users being followed by User ID $active_user.";
    }


    // Free result set
    $result->free();


    // Close statement
    $stmt->close();
} else {
    echo "User ID parameter is missing.";
}


// Close connection
mysqli_close($conn);
?>


