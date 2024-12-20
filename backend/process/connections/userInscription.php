<?php
include_once("../../dataBase/connectionDataBase.php");


// Regarde que le server soit bien en mode post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: ../../../frontend/pages/inscription.php');
    return;
}


// Regarde que tout les inputs existent bien
if (
    !isset(
        $_POST['email'],
        $_POST['confirmEmail'],
        $_POST['password'],
        $_POST['confirmPassword'],
        $_POST['username']
    )
) {
    header('location: ../../../frontend/pages/inscription.php?err=1');
    return;
}


// Regarde que les inputs ne soient pas vides
if (
    empty($_POST['email']) ||
    empty($_POST['confirmEmail']) ||
    empty($_POST['password']) ||
    empty($_POST['confirmPassword']) ||
    empty($_POST['username'])
) {
    header('location: ../../../frontend/pages/inscription.php?err=2');
    return;
}

// input sanitization
$email = htmlspecialchars(trim($_POST['email']));
$confirmEmail = htmlspecialchars(trim($_POST['confirmEmail']));
$password = htmlspecialchars(trim($_POST['password']));
$confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));
$username = htmlspecialchars(trim($_POST['username']));


// Regarde que les inputs ne soient pas trop longs
if (
    strlen($email) > 50 ||
    strlen($confirmEmail) > 50 ||
    strlen($password) > 50 ||
    strlen($confirmPassword) > 50 ||
    strlen($username) > 50
) {
    // redirection si c'est pas bon
    header('location: ../../../frontend/pages/inscription.php?err=3');
    return;
}

//  Si des champs sont différents
if (
    $email != $confirmEmail || $password != $confirmPassword
) {
    header('location: ../../../frontend/pages/inscription.php?err=4');
    return;
}



// Regarde en regex si l'email est bien conforme
if (!preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]/', $email)) {
    header('location: ../../../frontend/pages/inscription.php?err=5');
    return;
}



// Inserer user info dans la BDD


$sql = $sql = "INSERT INTO user (name, password, email)
VALUES (:name, :password, :email);";

try {
    $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare($sql);
    $users = $stmt->execute([
        ':email' => $_POST["email"],
        ':password' => $hashedPassword,
        ':name' => $_POST["username"]
    ]);
} catch (PDOException $error) {
    echo "Erreur lors de la requete : ";
    header('location: ../../../frontend/pages/connexion.php');
}

header('location: ../../../frontend/pages/home.php');
