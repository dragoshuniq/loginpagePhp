<?php
session_start();
include "connection.php";

if (isset($_SESSION['firstName'])) {
    header("Location: home.php");
    exit();
}

$fields = array('email', 'password', 'confirmPassword', 'firstName', 'lastName', 'dateOfBirth');

function checkNotNull()
{
    global $fields;
    $filtered = array_filter($fields, function ($field) {
        return isset($_POST[$field]);
    });
    return count($filtered) == count($fields);

}


if (checkNotNull()) {

    function formatInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    function checkEmptyField()
    {

        global $fields;
        $isError = false;
        $errorField = '';
        $query = "";
        foreach ($fields as $field) {
            $formattedField = formatInput($_POST[$field]);

            if (!$isError && empty($formattedField)) {
                $isError = true;
                $errorField = $field;
            }
            $query = $query . "&" . $field . "=" . $formattedField;

        }
        $password = formatInput($_POST['password']);
        $confirmPassword = formatInput($_POST['confirmPassword']);

        if (!$isError && (strlen($confirmPassword) < 6 || strlen($password) < 6)) {
            $errorField = 'Password length min 6';
            $isError = true;
        }
        if (!$isError && ($confirmPassword !== $password)) {
            $errorField = 'Password match';
            $isError = true;
        }

        if ($isError) {
            header("Location: register.php?error=" . ucfirst($errorField) . " is required" . $query, false);
            exit();
        }

    }

    checkEmptyField();
    $email = formatInput($_POST['email']);
    $password = formatInput($_POST['password']);
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dateOfBirth = $_POST['dateOfBirth'];


    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['email'] === $email) {
            header("Location: register.php?error=User with this email already exists!", false);
            exit();
        }
    } else {
        $insertSql = "INSERT INTO USERS (`firstName`, `lastName`, `dateOfBirth`, `email`, `password`) VALUES ('$firstName', '$lastName', '$dateOfBirth', '$email', '$password')";
        $insertResult = mysqli_query($conn, $insertSql);

        if (mysqli_affected_rows($conn) > 0) {
            $getQuery = "SELECT * FROM users WHERE email = '$email'";
            $getResult = mysqli_query($conn, $getQuery);
            $row = mysqli_fetch_assoc($getResult);
            $_SESSION['email'] = $row['email'];
            $_SESSION['firstName'] = $row['firstName'];
            $_SESSION['lastName'] = $row['lastName'];
            $_SESSION['dateOfBirth'] = $row['dateOfBirth'];
            $_SESSION['registrationDate'] = $row['registrationDate'];
            $_SESSION['userNumber'] = $row['userNumber'];
            header("Location: home.php");
        } else {
            header("Location: register.php?error=mysql error" . mysqli_error($conn), false);
        }
        exit();
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>REGISTER</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<body>
    <form action="register.php" method="post">
        <h2>REGISTER</h2>
        <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email"
            value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>">
        <div class='row'>
            <div class='col'>
                <label>Password</label>
                <input type="password" name="password" placeholder="Password"
                    value="<?php echo isset($_GET['password']) ? $_GET['password'] : '' ?>">
            </div>
            <div class='col'>
                <label>Confirm password</label>
                <input type="password" name="confirmPassword" placeholder="Password"
                    value="<?php echo isset($_GET['confirmPassword']) ? $_GET['confirmPassword'] : '' ?>">
            </div>


        </div>
        <div class='row'>
            <div class='col'>
                <label>First name</label>
                <input type="text" name="firstName" placeholder="First name"
                    value="<?php echo isset($_GET['firstName']) ? $_GET['firstName'] : '' ?>">
            </div>
            <div class='col'>
                <label>Last name</label>
                <input type="text" name="lastName" placeholder="Last name"
                    value="<?php echo isset($_GET['lastName']) ? $_GET['lastName'] : '' ?>">
            </div>

        </div>

        <label>Date of birth</label>
        <input type="date" name="dateOfBirth" placeholder="Date of birth"
            max="<?php echo date('Y-m-d', strtotime('-10 years')); ?>"
            value="<?php echo isset($_GET['dateOfBirth']) ? $_GET['dateOfBirth'] : '' ?>">


        <button type="submit" name="submit">Register</button>

        <p class='redirectP'>Have an account? <a href="login.php">Login here</a></p>

    </form>
</body>

</html>