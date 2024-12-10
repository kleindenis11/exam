<?php
@include 'db.php'; 

if (isset($_POST['users'])) {
    
    
    $head = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['password']);
    

    $sql = "INSERT INTO users(, email, password) VALUES ( ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        
        $stmt->bind_param("ss", $email, $password);
        
        if ($stmt->execute()) {
            echo "<p style='text-align: center; color: green;'>user registered successfully!</p>";
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
    <title>FineSente - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #000000;
            color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 0 20px;
            position: relative;
            height: auto;
            overflow: auto;
        }

        /* App Branding */
        .app-branding {
            text-align: center;
            margin-bottom: 40px;
        }

        .app-branding img {
            width: 120px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .app-branding img:hover {
            transform: scale(1.1);
        }

        .app-branding h1 {
            font-size: 2.5rem;
            color: #007bff;
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
            transition: box-shadow 0.3s ease;
            text-align: center;
        }

        .form-container:hover {
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2);
        }

        /* Form Header */
        .form-container h2 {
            color: #007bff;
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
            border-color: #007bff;
            background-color: #555;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bff;
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
            background-color: #0056b3;
        }

        .divider {
            margin: 20px 0;
            border-top: 2px solid #007bff;
        }

        .register-link {
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .forgot-password {
            margin: 10px 0 20px;
        }

        .forgot-password a {
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

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
        <img src="th.jpg" alt="FineSente Logo">
        <h1>FineSente</h1>
        <p>Your partner in financial growth.</p>
    </div>

    <div class="form-container">
        <h2>Login here</h2>
        <form id="loginForm" method="POST" onsubmit="loginUser(event)">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
           
            <input type="submit" value="Login">
            
            <div class="forgot-password">
                <a href="passwordReco.html">Forgot Password?</a>
            </div>
        </form>
        <div class="divider"></div>
        <div class="register-link">
            <p>Don't have an account? <a href="register.html">Register here</a></p>
        </div>
    </div>

    <p>&copy; 2024 FineSente. All Rights Reserved.</p>

    <script>
        // Function to handle the login process
        function loginUser(event) {
            event.preventDefault(); // Prevent the form from submitting
            
            // Assuming login credentials are correct, redirect to dashboard
            window.location.href = 'dash.html';  // Redirect to dashboard
        }
    </script>
</body>
</html>
