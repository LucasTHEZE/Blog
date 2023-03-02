<?php
require "dbcon.php";
session_start();
if ($_SESSION['is_admin']==false) {
    header('Location: index.php');
}

if(isset($_POST["deconnexion"])) {
    $_SESSION['is_connected']=false;
    $_SESSION['is_admin']==false;
    header('Location: index.php');
}


if(isset($_POST['formsend'])) {
    $content = $_POST['content'];
    $title = $_POST['title'];

    
    $query = $db->prepare("INSERT INTO articles(title,content,iduserfk) VALUES(:title,:content,:iduserfk)");
    $query->execute([
        'title' => $title,
        'content' => $content,
        'iduserfk' => $_SESSION['iduser']
    ]);
}

$query = $db->prepare("SELECT articles.idarticles,articles.title,articles.content,user.firstname,user.lastname,articles.iduserfk FROM articles INNER JOIN user ON articles.iduserfk=user.iduser");
$query->execute();
$list_articles = $query->fetchAll();

$query = $db->prepare("SELECT comments.idcomments,comments.content,user.firstname,user.lastname,comments.idarticlesfk,comments.iduserfk FROM comments INNER JOIN user ON comments.iduserfk=user.iduser");
$query->execute();
$list_comment = $query->fetchall();

$query = $db->prepare("SELECT * FROM user");
$query->execute();
$list_user = $query->fetchall();

if(isset($_POST['b_s_comment'])){


    $query = $db->prepare("INSERT INTO comments(content,iduserfk,idarticlesfk) VALUES(:content,:iduserfk,:idarticlesfk)");
    $query->execute([
        'content' => $_POST['comment'],
        'iduserfk' => $_SESSION['iduser'],
        'idarticlesfk' => $_POST['id_article']
    ]);
    header('Location: admin.php');

}
if(isset($_POST['b_d_comm'])){
    $query = $db->prepare("DELETE FROM comments WHERE idcomments = ".$_POST['id_com']);
    $query->execute();
    header('Location: admin.php');
}
if(isset($_POST['b_d_article'])){
    $query = $db->prepare("DELETE FROM comments WHERE idarticlesfk = ".$_POST['id_article']);
    $query->execute();
    $query = $db->prepare("DELETE FROM articles WHERE idarticles = ".$_POST['id_article']);
    $query->execute();
    header('Location: admin.php');
}

if(isset($_POST['d_user'])){
    $query = $db->prepare("DELETE FROM user WHERE iduser = ".$_POST['id_user']);
    $query->execute();
    $query = $db->prepare("DELETE FROM articles WHERE iduserfk = ".$_POST['id_user']);
    $query->execute();
    $query = $db->prepare("DELETE FROM comments WHERE iduserfk = ".$_POST['id_user']);
    $query->execute();
    header('Location: admin.php');
}

   
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
    header('Location: admin.php');
    }
    catch (Exception $e){
        echo "mail déja utilisé";
    }
    
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<div class="container">
    <div class="toppane">
    <form method="post">
                    <button type="submit" name="deconnexion">deconnexion</button>
                </form>
        
    </div>
    <div class="leftpane">
        <?php foreach($list_user as $user){ 
                if($user['role'] != "admin"){
                    ?>
            <form id="user_delete" method="post">
                <?php echo htmlspecialchars($user['lastname']) ."  ". $user['firstname'] ." <strong>mail</strong>: ". $user['mail']?>
                <button name='d_user' type="submit">Suprimer l'utilisateur</button> 
                </br>
                </br>
                <input type="hidden"  name="id_user" value="<?php echo $user['iduser'] ?>"/>
            </form>
            <?php }} ?>
    </div>
    <div class="midlepane">
        <?php foreach($list_articles as $article) {
            ?>
                ------------------------------------------------------------------------------------------------------------------------ <br>
                ------------------------------------------------------------------------------------------------------------------------ <br>
                ------------------------------------------------------------------------------------------------------------------------ <br>
            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
            <div class="content">
                <?php echo htmlspecialchars($article['content']); ?>
            </div>
            <h4> De: <?php echo htmlspecialchars($article['lastname'])." ".htmlspecialchars($article['firstname']); ?> </h4>
                            <form id="article_delete" method="post">
                            <button name='b_d_article' type="submit">Suprimer article</button>
                            <input type="hidden"  name="id_article" value="<?php echo $article['idarticles'] ?>"/>
                            </form>
            <div class="comment">
                Commentaire:<br>
                <br>
                <?php foreach($list_comment as $comment){
                    if($comment['idarticlesfk']==$article['idarticles']){
                        echo $comment['content']."<br>";
                        echo "De ".$comment['lastname']." ".$comment['firstname']."<br>";
                        echo "<br>";
                            ?>
                            <form id="comm_delete" method="post">
                            <button name='b_d_comm' type="submit">Suprimer commentaire</button>
                            <input type="hidden"  name="id_com" value="<?php echo $comment['idcomments'] ?>"/>
                            </form>
                            <?php
                     };
                } ?>
                ------------------------------------------------------------------------------------------------------------------------ <br>
                    
            </div>
        <?php }; ?>
    </div>
    <div class="rightpane">
        <div id="blocDeDroite">
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
        </div>
        </div>
    </div>
</body>
</html>