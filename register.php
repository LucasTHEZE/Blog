<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<?php 
    require "dbcon.php";
    
    if(isset($_POST['inscription'])) {
        $mail = $_POST['mail'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $lastname = strtolower($_POST['Nom']);
        $firstname = strtolower($_POST['Prénom']);
        try {
            $query = $db->prepare("INSERT INTO user(mail,password,lastname,firstname) VALUES(:mail,:password,:lastname,:firstname)");
        $query->execute([
            'mail' => $mail,
            'password' => $password,
            'lastname' => $lastname,
            'firstname' => $firstname
        ]);
        echo "inscription réussie";
        header('Location: login.php');
        }
        catch (Exception $e){
            echo "mail déja utilisé";
        }
        
    }

?>
<body>

<form method="post">
    <label for="Nom">Nom</label>
    <input type="Nom" id="Nom" name="Nom" placeholder="" required>

    <label for="Prénom">Prénom</label>
    <input type="Prénom" id="Prénom" name="Prénom" placeholder="" required>
        
    <label for="mail">adresse mail</label>
    <input type="mail" id="mail" name="mail" placeholder="" required>
        
        
    <label for="password">mot de passe</label>
    <input type="password" id="password" name="password" placeholder="" required>

    <button type="submit" name="inscription">inscription</button>

</form>
    
</body>
</html>