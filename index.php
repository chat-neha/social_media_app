<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="public_account">Public Account:</label><br>
        <input type="checkbox" id="public_account" name="public_account"><br>
        <input type="submit" value="Login">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uname = $_POST['username'];
        $passwd = $_POST['password'];
        $pvt = isset($_POST['public_account']) ? 1 : 0;

        $servername = "localhost";
        $username = "root";
        $password = "vedika";
        $database = "project";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Failed to connect: " . mysqli_connect_error());
        }

        // Attempt to insert into the database
        $insert_sql = "INSERT INTO account VALUES (CURDATE(), '$passwd', '$uname', $pvt)";
        $login_sql="Delete from login";
        $insertlogin_sql="Insert into login values('$uname','$passwd')";
        mysqli_query($conn,$login_sql);
        mysqli_query($conn,$insertlogin_sql);
        if (mysqli_query($conn, $insert_sql)) {
            echo "Account created successfully.";
            // Redirect to homepage
            header("Location: users.php");
            exit(); // Make sure to exit after redirection
        } else {
            // Check if the error is a duplicate entry error
            if (mysqli_errno($conn) == 1062) { // MySQL error code for duplicate entry
                // Check if the entered password matches the stored password
                $check_password_sql = "SELECT password FROM account WHERE user_name='$uname'";
                $password_result = mysqli_query($conn, $check_password_sql);
                $row = mysqli_fetch_assoc($password_result);
                $stored_password = $row['password'];

                if ($passwd == $stored_password) {
                    // Passwords match, insert the user into the login table
                    $delete_query="Delete from login";
                    $insert_login_sql = "INSERT INTO login VALUES ('$uname', '$passwd')";
                    mysqli_query($conn, $delete_query);
                    if (mysqli_query($conn, $insert_login_sql)) {
                        echo "Login created successfully.";
                        // Redirect to homepage
                        header("Location: homepage.php");
                        exit(); // Make sure to exit after redirection
                    } else {
                        echo "Error creating login: " . mysqli_error($conn);
                    }
                } else {
                    echo "Incorrect password.";
                }
            } else {
                // Other error, display error message
                echo "Error: " . mysqli_error($conn);
            }
        }

        mysqli_close($conn);
    }
    ?>
</body>
</html>