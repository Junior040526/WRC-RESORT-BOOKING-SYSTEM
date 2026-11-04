<?php
session_start();

$error = "";

$admin_username = "admin";
$admin_password = "WRC@2026";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if (
        hash_equals($admin_username, $username) &&
        hash_equals($admin_password, $password)
    ) {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;

        header("Location: admin.php");
        exit;
    } else {
        $error = "Incorrect username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WRC Haven Admin Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6f2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 320px;
            border-radius: 10px;
            box-shadow: 0 4px 15px #ccc;
        }

        h2 {
            color: #164b35;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #164b35;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #246b4a;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <h2>WRC Haven Admin</h2>

        <?php if ($error !== ""): ?>
            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">Login</button>

        </form>

    </div>

</body>
</html>