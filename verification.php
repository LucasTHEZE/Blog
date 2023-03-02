<?php 
   require "dbcon.php";
        

    session_start();
    $_SESSION['is_connected']=false;

    if(isset($_POST['connexion'])) {
        
        $query = $db->prepare("SELECT iduser,mail,password,role FROM user WHERE mail='" . $_POST['mail'] . "'");
        $query->execute();
        $user = $query->fetch();
    
        
        try {
            if ($user) {
                if (password_verify($_POST['password'],$user['password'])) {
                    $_SESSION['is_connected']=true;
                    $_SESSION['iduser']=$user['iduser'];
                    $_SESSION['is_admin']=false;
                    if ($user['role']=="admin"){
                        $_SESSION['is_admin']=true;
                        header("Location: admin.php");
                    }
                    else{
                        header('Location: home.php');
                    }
                    
        }
    }
        } catch (Exception $e) {
            
        }
        if ($_SESSION['is_connected']==false) {
            echo "mot de passe ou adresse mail éroné";
        }
        }

?>