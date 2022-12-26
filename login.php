<?php
session_start();
include "connection.php";
if (isset($_SESSION['firstName'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['email']) && isset($_POST['password'])) {

    function formatInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $email = formatInput($_POST['email']);
    $password = formatInput($_POST['password']);

    if (empty($email)) {
        header("Location: login.php?error=Email is required", false);
        exit();
    } else if (empty($password)) {
        header("Location: login.php?error=Password is required", false);
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['email'] === $email && $row['password'] === $password) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['dateOfBirth'] = $row['dateOfBirth'];
                $_SESSION['registrationDate'] = $row['registrationDate'];
                $_SESSION['userNumber'] = $row['userNumber'];
                header("Location: home.php");
                exit();
            } else {
                header("Location: login.php?email=" . $email . "&password=" . $password . "&error=Invalid credentials", false);
                exit();
            }
        } else {
            header("Location: login.php?email=" . $email . "&password=" . $password . "&error=Invalid credentials", false);
            exit();
        }
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">


</head>

<body>
    <form action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email"
            value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>">

        <label>Password</label>
        <div>
            <input type="password" name="password" placeholder="Password"
                value="<?php echo isset($_GET['password']) ? $_GET['password'] : '' ?>">
        </div>
        <button type="submit">Login</button>
        <p class='redirectP'>Don't have an account? <a href="register.php">Register here</a></p>

    </form>
</body>

</html>