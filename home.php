<?php
session_start();

if (isset($_SESSION['email'])) {

?>
<!DOCTYPE html>
<html>

<head>
    <title>HOME</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
    <h1>Hello, <?php echo $_SESSION['firstName']; ?></h1>
    <h2 class='mt-2'>Your user details are:</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col" class='text-center'>MySQL field name</th>
                <th scope="col" class='text-center'>MySQL field value</th>
            </tr>
        </thead>
        <tbody>
            <?php
    $fields = array('email', 'firstName', 'lastName', 'dateOfBirth', 'registrationDate', 'userNumber');
    foreach ($fields as $dataField)

        echo "<tr>
                <td class='text-center'>$dataField</td>
                <td class='text-center'>$_SESSION[$dataField]</td>
            </tr>"
                ?>

        </tbody>
    </table>

    <a href="logout.php" class='logout'>Logout</a>
</body>

</html>

<?php
} else {
    header("Location: login.php");
    exit();
}
?>