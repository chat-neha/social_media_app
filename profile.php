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

    // Call the stored procedure to fetch user profile
    $stmt = $conn->prepare("CALL ViewUserProfile(?)");
    $stmt->bind_param("s", $active_user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any rows
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "User ID: " . $row['user_id'] . "<br>";
            echo "Bio: " . $row['bio'] . "<br>";
            echo "<a href='followerslist.php'>Followers: " . $row['num_followers'] . "</a><br>";
            echo "<a href='followinglist.php'>Following: " . $row['num_following'] . "</a><br>";
            echo "Posts: " . $row['num_posts'] . "<br>";
        }
        // Move to the next result set
        $stmt->next_result();
        $result = $stmt->get_result();

        // Output posts
        echo "<h2>Posts:</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "Creator: " . $row['creator'] . "<br>";
            echo "Text: " . $row['text'] . "<br>";
            echo "Likes: " . $row['num_likes'] . "<br>";
            echo "Comments: " . $row['num_comments'] . "<br>";
            echo "Timestamp: " . $row['timestamp'] . "<br>";
        }
    } else {
        echo "No results found.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the query was not successful, display an error message
    echo "Error retrieving user ID: " . mysqli_error($conn);
}
?>
