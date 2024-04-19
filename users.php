<!DOCTYPE html>
<html>
<head>
    <title>User IDs</title>
</head>
<body>
    <h2>User IDs</h2>

    <?php
    session_start(); // Start the session

    // Function to check if the user is already following a specific user
    function isFollowing($follow_user_id) {
        return isset($_SESSION['following'][$follow_user_id]);
    }

    // Function to update following status
    function toggleFollowing($follow_user_id) {
        if (isset($_SESSION['following'][$follow_user_id])) {
            unset($_SESSION['following'][$follow_user_id]);
        } else {
            $_SESSION['following'][$follow_user_id] = true;
        }
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "vedika"; // Update this with your MySQL password
    $database = "project";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Sorry we failed to connect: " . mysqli_connect_error());
    }

    // Check if the Follow button is clicked
    if (isset($_POST['follow'])) {
        // Get the user_id to follow from the submitted form
        $follow_user_id = $_POST['follow'];

        // Call the MySQL procedure follow with the selected user_id
        $sql = "CALL follow('$follow_user_id')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "You are now ";
            if (isFollowing($follow_user_id)) {
                echo "un";
            }
            echo "following user with ID: $follow_user_id";
            toggleFollowing($follow_user_id);
        } else {
            echo "Error following user: " . mysqli_error($conn);
        }
    }
    $request = mysqli_query($conn, "select user_id from login");

    // Check if the query was successful
    if($request) {
        // Fetch each row from the result set
        while($row = mysqli_fetch_assoc($request)) {
            // Access the 'user_id' column from each row and echo it
            $active_user = $row['user_id'] ;
        }
    } else {
        // If the query was not successful, display an error message
        echo "Error retrieving user IDs: " . mysqli_error($conn);
    }
    
    // Fetching user IDs from the user table
    $sql = "SELECT user_id FROM users where user_id<>'$active_user'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row["user_id"];
            // Form to submit the user_id to follow
            echo "<form method='post'>";
            echo "<p>$user_id <button type='submit' name='follow' value='$user_id'>" . (isFollowing($user_id) ? "Following" : "Follow") . "</button></p>";
            echo "</form>";
        }
    } else {
        echo "No user IDs found.";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <!-- Next button form -->
    <form method="get" action="homepage.php">
        <button type="submit">Next</button>
    </form>
</body>
</html>
