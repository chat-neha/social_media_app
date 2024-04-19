<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        /* Add some basic styling to buttons */
        button {
            margin: 5px;
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
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

// Fetch the user ID from the login table
$request = mysqli_query($conn, "SELECT user_id FROM login");

// Check if the query was successful
if ($request) {
    $row = mysqli_fetch_assoc($request);
    $active_user = $row['user_id'];

    // Call the stored procedure to fetch posts for the active user
    $stmt = "CALL homepage('$active_user')";
    if ($conn->multi_query($stmt)) {
        // Process each result set
        do {
            // Get result set
            if ($result = $conn->store_result()) {
                // Check if there are any posts
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "Post-id: " . $row["post_id"] . "<br>";
                        echo "Creator: " . $row["creator"] . "<br>";
                        echo "Text: " . $row["text"] . "<br>";
                        echo "Likes: " . $row["num_likes"] . "<button onclick='likePost(" . $row['post_id'] . ")'>Like</button><br>";
                        echo "Comments: <a href='viewcomments.php?post_id=" . $row['post_id'] . "'>View Comments</a><br>";
                        echo "Timestamp: " . $row["timestamp"] . "<br>";
                        echo "</li>";
                    }
                } else {
                    echo "No posts found.";
                }
                // Free result set
                $result->free();
            }
            // Move to next result set
        } while ($conn->more_results() && $conn->next_result());
    } else {
        // If the stored procedure call was not successful, display an error message
        echo "Error calling stored procedure for user ID: $active_user - " . mysqli_error($conn);
    }
} else {
    // If the query was not successful, display an error message
    echo "Error retrieving user ID: " . mysqli_error($conn);
}

// Close connection
$conn->close();
?>

<div style="float: center;">
    <!-- Button to open explore.php -->
    <form action="explore.php" method="get">
        <button type="submit">Explore</button>
    </form>
   
    <!-- Button to open message.php -->
    <form action="message.php" method="get">
        <button type="submit">Messages</button>
    </form>
   
    <!-- Button to open profile.php -->
    <form action="profile.php" method="get">
        <button type="submit">Profile</button>
    </form>
   
    <!-- Button to open search_user.php -->
    <form action="search_user.php" method="get">
        <button type="submit">Search User</button>
    </form>

    <!-- Button to create a post -->
    <form action="post.php" method="get">
        <button type="submit">Create Post</button>
    </form>
</div>

<script>
    function likePost(postId) {
        // Send an AJAX request to call the MySQL procedure incrementlikes with the post ID as input
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "increment_likes.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText); // Display response from server (if any)
            }
        };
        xhr.send("post_id=" + postId);
    }
</script>

</body>
</html>
