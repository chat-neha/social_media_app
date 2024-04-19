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

// Retrieve active user ID from the login table
$request = mysqli_query($conn, "SELECT user_id FROM login");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];
    
    // Debug: Check the active user ID
    echo "Active User ID: $active_user<br>";

    // Check if the active user has liked any posts
    $query = "SELECT COUNT(*) AS count FROM likes WHERE user_id = '$active_user'";
    $result = mysqli_query($conn, $query);
    
    // Check if the count query was successful
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $num_likes = $row['count'];
        
        // Debug: Check the number of likes
        echo "Number of Likes: $num_likes<br>";

        if ($num_likes == 0) {
            // If the active user has not liked any posts
            $query = "SELECT * FROM posts ORDER BY num_likes";
        } else {
            // If the active user has liked posts
            $query = "SELECT post_id, text, num_likes, num_comments, timestamp 
                      FROM posts 
                      WHERE creator IN (
                          SELECT creator 
                          FROM posts 
                          WHERE post_id IN (
                              SELECT post_id 
                              FROM likes 
                              WHERE user_id = '$active_user'
                          )
                      )";
        }
        
        // Execute the query
        $result = mysqli_query($conn, $query);
        
        // Check if the posts query was successful
        if ($result) {
            // Display the results
            while ($row = mysqli_fetch_assoc($result)) {
                // Output the post details
                echo "Post ID: " . $row['post_id'] . "<br>";
                echo "Text: " . $row['text'] . "<br>";
                echo "Number of Likes: " . $row['num_likes'] . "<br>";
                echo "Number of Comments: " . $row['num_comments'] . "<br>";
                echo "Timestamp: " . $row['timestamp'] . "<br>";
                echo "<hr>";
            }
        } else {
            // Display an error message if the posts query fails
            echo "Error executing posts query: " . mysqli_error($conn);
        }
    } else {
        // Display an error message if the count query fails
        echo "Error executing count query: " . mysqli_error($conn);
    }
} else {
    // Display an error message if the query to retrieve the active user fails
    echo "Error retrieving active user: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
