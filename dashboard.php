<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT firstname, lastname, email, address, pin_code, country, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $address, $pin_code, $country, $profile_picture);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        img.profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
        }
        .top-right {
            position: absolute;
            top: 60px;
            right: 60px;
        }
        .top-right a {
            margin-left: 10px;
            text-decoration: none;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
        }
        .top-right a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-right">
            <a href="edit_profile.php">Edit Profile</a>
            <a href="delete_profile.php">Delete Profile</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <h2>Welcome, <?php echo htmlspecialchars($firstname) . " " . htmlspecialchars($lastname); ?></h2>
        <img class="profile-pic" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
        
        <table>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($firstname); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($lastname); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($email); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($address); ?></td>
            </tr>
            <tr>
                <th>Pin Code</th>
                <td><?php echo htmlspecialchars($pin_code); ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo htmlspecialchars($country); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
