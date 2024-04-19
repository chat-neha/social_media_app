<?php
// Assuming you have the necessary database connection code here
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

// Retrieve user_id from the login table
$login_query = $conn->query("SELECT user_id FROM login LIMIT 1");
if ($login_query->num_rows > 0) {
    $row = $login_query->fetch_assoc();
    $user_id = $row['user_id'];
} else {
    die("No user found in the login table.");
}

// Check if form data is submitted
if(isset($_POST['new_comment']) && isset($_POST['post_id'])) {
    // Retrieve form data
    $comment_text = $_POST['new_comment'];
    $post_id = $_POST['post_id'];

    // Check if the comment text is not empty
    if(!empty($comment_text)) {
        // Call the procedure to insert the comment into the database
        $stmt = $conn->prepare("CALL insertcomment(?, ?, ?)");
        
        if ($stmt === FALSE) {
            echo "Prepare statement error: " . $conn->error;
        } else {
            // Binding parameters
            $stmt->bind_param("sss", $user_id, $comment_text, $post_id);

            // Execute the query
            if ($stmt->execute() === TRUE) {
                echo "Comment inserted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "Comment text cannot be empty.";
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
