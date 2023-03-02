
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<?php 
require "verification.php";
?>
<body>

<form method="post">
    <label for="mail">adresse mail</label>
    <input type="mail" id="mail" name="mail" placeholder="" required>
        
        
    <label for="password">mot de passe</label>
    <input type="password" id="password" name="password" placeholder="" required>


    <button type="submit" name="connexion">connexion</button>
</form>        
    
</body>
</html>