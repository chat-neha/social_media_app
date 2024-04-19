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

// Check if sender_id, recipient_id, and reply are set in the POST data
if(isset($_POST['sender_id'], $_POST['recipient_id'], $_POST['reply'])) {
    // Retrieve sender_id, recipient_id, and reply from POST data
    $sender_id = $_POST['sender_id'];
    $recipient_id = $_POST['recipient_id'];
    $reply = $_POST['reply'];

    // Call the stored procedure to send the message
    $stmt = $conn->prepare("CALL sendmessage(?, ?, ?)");
    $stmt->bind_param("sss", $sender_id, $recipient_id, $reply);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error sending message: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    // If sender_id, recipient_id, or reply are missing in the POST data, display an error message
    echo "Missing parameters.";
}

// Close connection
mysqli_close($conn);
?>
