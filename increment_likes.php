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

// Get the post ID from the AJAX request
$postId = $_POST['post_id'];

// Retrieve active user ID from the login table
$request = mysqli_query($conn, "SELECT user_id FROM login");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];

    // Insert like into the likes table
    $insert_query = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("is", $postId, $active_user);

    if ($stmt_insert->execute()) {
        echo "Like inserted successfully!<br>";
    } else {
        echo "Error inserting like: " . $stmt_insert->error . "<br>";
    }

    // Call the MySQL procedure to increment likes for the given post ID
    $stmt_procedure = $conn->prepare("CALL incrementlikes(?)");
    $stmt_procedure->bind_param("i", $postId);

    if ($stmt_procedure->execute()) {
        echo "Post liked successfully!";
    } else {
        echo "Error calling procedure: " . $stmt_procedure->error;
    }

    // Close statement
    $stmt_insert->close();
    $stmt_procedure->close();
} else {
    // Display an error message if the query to retrieve the active user fails
    echo "Error: " . mysqli_error($conn);
}

// Close connection
$conn->close();
?>
