<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Comments</title>
    <!-- Add any necessary CSS or stylesheets here -->
</head>
<body>

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

// Check if post_id is provided in the URL
if(isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Call the procedure to fetch comments for the given post_id
    $stmt = "CALL viewcomments($post_id)";

    // Execute the query
    $result = $conn->query($stmt);
    
    if ($result && $result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "User: " . $row["user_id"] . "<br>";
            echo "Comment: " . $row["text"] . "<br>";
            echo "Number of likes: " . $row["num_likes"] . "<br>";
            echo "Number of replies: " . $row["num_replies"] . "<br>";
            echo "</div>";
        }
    } else {
        echo "No comments available.";
    }
} else {
    // Redirect or handle the case where post_id is not provided
}
?>

<!-- Add the label and text box for inserting a new comment -->
<form action="insertcomment.php" method="post">
    <label for="new_comment">Insert Comment:</label><br>
    <input type="text" id="new_comment" name="new_comment"><br>
    <input type="hidden" name="user_id" value="<?php echo $active_user; ?>"> <!-- Adding user_id as hidden input -->
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"> <!-- Adding post_id as hidden input -->
    <input type="submit" value="Submit">
</form>


</body>
</html>
