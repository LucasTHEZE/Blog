<?php
session_start();
require "dbcon.php";

require "query_home.php";

if ($_SESSION['is_connected']==false) {
    header('Location: index.php');
}

if(isset($_POST["deconnexion"])) {
    $_SESSION['is_connected']=false;
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<div class="container">
    <div class="toppane">
    </div>
    <div class="leftpane">
        <form method='post'>
            <div>
                <label for="title">Titre de l'article :</label>
                <input type="text" id="title" name="title" placeholder="Titre" required>
            </div>
            <div>
                <label for="content">Contenu :</label>
                <textarea id="content" name="content" wrap="off" placeholder="Ecrire l'article ici" required></textarea>
            </div>
            <div>
                <button name='formsend' type="submit">Poster l'article</button>
            </div>
    </form>
    </div>
    <div class="midlepane">
        <?php foreach($list_articles as $article) {
            ?>
                ------------------------------------------------------------------------------------------------------------------------ <br>
                ------------------------------------------------------------------------------------------------------------------------ <br>
                ------------------------------------------------------------------------------------------------------------------------ <br>
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <div class="content">
                <?php echo htmlspecialchars($article['content']); ?>
            </div>
            <h4> De: <?php echo htmlspecialchars($article['lastname'])." ".htmlspecialchars($article['firstname']); ?> </h4>
            <?php
            if($_SESSION['iduser']==$article['iduserfk']){
                            ?>
                            <form id="article_delete" method="post">
                            <button name='b_d_article' type="submit">Suprimer article</button>
                            <input type="hidden"  name="id_article" value="<?php echo $article['idarticles'] ?>"/>
                            </form>
                            
                            <?php
                        };
                        ?>
                        <h3>Commentaire:</h3>
            <div class="comment">
                <?php foreach($list_comment as $comment){
                    if($comment['idarticlesfk']==$article['idarticles']){
                        echo $comment['content']."<br>";
                        echo "De ".$comment['lastname']." ".$comment['firstname']."<br>";
                        echo "<br>";
                        if($_SESSION['iduser']==$comment['iduserfk']){
                            ?>
                            <form id="comm_delete" method="post">
                            <button name='b_d_comm' type="submit">Suprimer commentaire</button>
                            <input type="hidden"  name="id_com" value="<?php echo $comment['idcomments'] ?>"/>
                            </form>
                            <?php
                        };
                     };
                } ?>
                </div>
                <br>
                    <form id="com_form" method="post">
                        <textarea id="comment" name="comment" cols="40" rows="2" placeholder="Ecrire votre commentaire ici" required></textarea>
                        <button name='b_s_comment' type="submit">Poster commentaire</button>
                        <input type="hidden"  name="id_article" value="<?php echo $article['idarticles'] ?>"/>
                    </form>
            
        <?php }; ?>
    </div>
    <div class="rightpane">
        <div id="blocDeDroite">
            <form method="post">
                    <button type="submit" name="deconnexion">deconnexion</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>