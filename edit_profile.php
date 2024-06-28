<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $pin_code = trim($_POST['pin_code']);
    $country = trim($_POST['country']);

    // Profile picture upload
    $profile_picture = $_FILES['profile_picture'];
    $profile_picture_path = '';

    if ($profile_picture['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture['name']);
        if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
            $profile_picture_path = "http://localhost/loginsystem/" . $target_file;
        } else {
            $errors[] = "Failed to upload profile picture";
        }
    }

    if (empty($firstname)) {
        $errors[] = "First name is required";
    }

    if (empty($lastname)) {
        $errors[] = "Last name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($pin_code)) {
        $errors[] = "Pin code is required";
    }

    if (empty($country)) {
        $errors[] = "Country is required";
    }

    if (count($errors) == 0) {
        // Check if a new profile picture was uploaded, otherwise retain the existing path
        if (!empty($profile_picture_path)) {
            $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, address = ?, pin_code = ?, country = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $firstname, $lastname, $email, $address, $pin_code, $country, $profile_picture_path, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, address = ?, pin_code = ?, country = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $address, $pin_code, $country, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $stmt = $conn->prepare("SELECT firstname, lastname, email, address, pin_code, country, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname, $email, $address, $pin_code, $country, $profile_picture);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        img {
            margin-top: 10px;
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-list {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <?php
        if (!empty($errors)) {
            echo '<ul class="error-list">';
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul>';
        }
        ?>
        <form method="post" action="edit_profile.php" enctype="multipart/form-data">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>"><br><br>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>"><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($address); ?></textarea><br><br>

            <label for="pin_code">Pin Code:</label>
            <input type="text" id="pin_code" name="pin_code" value="<?php echo htmlspecialchars($pin_code); ?>"><br><br>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>"><br><br>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture"><br><br>

            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture"><br><br>

            <input type="submit" value="Update Profile">
        </form>
    </div>
</body>
</html>
