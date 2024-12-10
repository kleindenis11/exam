<?php
@include 'reglogin.php'; 

if (isset($_POST['submit_department'])) {
    
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $head = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['password']);
    

    $sql = "INSERT INTO department (username, email, password) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            echo "<p style='text-align: center; color: green;'>Department registered successfully!</p>";
        } else {
            echo "<p style='text-align: center; color: red;'>Error: " . $stmt->error . "</p>";
        }
        
        $stmt->close();
    } else {
        echo "<p style='text-align: center; color: red;'>Error preparing statement: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Finances Tracker - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #000000; /* Dark background */
            color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 0 20px; /* Padding added to ensure content does not touch edges */
            position: relative;
            height: auto; /* Change height to auto to allow scrolling */
            overflow: auto; /* Allow scrolling */
        }


        /* App Branding */
        .app-branding {
            text-align: center;
            margin-bottom: 40px;
        }

        .app-branding img {
            width: 120px; /* Logo size */
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .app-branding img:hover {
            transform: scale(1.1); /* Subtle hover effect on logo */
        }

        .app-branding h1 {
            font-size: 2.5rem;
            color: #ff8c00;
            margin: 0;
            font-weight: 500;
        }

        .app-branding p {
            font-size: 1rem;
            color: #a1a1a1;
            margin-top: 8px;
            font-weight: 400;
        }

        /* Form Container */
        .form-container {
            background-color: #2d2d2d;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            border-left: 6px solid #ff8c00;
            border-top: 6px solid #ff8c00;
            transition: box-shadow 0.3s ease;
            text-align: center;
        }

        .form-container:hover {
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2); /* Hover effect on form */
        }

        /* Form Header */
        .form-container h2 {
            color: #ff8c00;
            font-size: 1.8rem;
            margin-bottom: 25px;
            font-weight: 500;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #dcdcdc;
            display: block;
            margin-bottom: 8px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #444;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #ff8c00;
            background-color: #555;
            outline: none;
        }

        input[type="submit"] {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e77f00;
        }

        /* Divider Style */
        .divider {
            margin: 20px 0;
            border-top: 2px solid #ff8c00;
        }

        /* Link Styles */
        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #ff8c00;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #e77f00;
            text-decoration: underline;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 600px) {
            .form-container {
                padding: 30px 20px;
            }

            .app-branding h1 {
                font-size: 2rem;
            }

            .form-container h2 {
                font-size: 1.5rem;
            }

            input[type="email"], input[type="password"] {
                font-size: 14px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="app-branding">
        <img src="logo.png" alt="My Finances Tracker Logo">
        <h1>My Finances Tracker</h1>
        <p>Manage your finances with ease and precision.</p>
    </div>

    <div class="form-container">
        <h2>SignIn Successful</h2>
        <div class="register-link">
            <a href="login.php">Log in to your Account </a>
        </div>
    </div>

    <!-- Footer placed at the bottom of the page -->
    <p>&copy; 2024 My Finances Tracker. All Rights Reserved.</p>

</body>
</html>
