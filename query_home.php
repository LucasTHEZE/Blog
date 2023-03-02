<?php
require "dbcon.php";

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

if(isset($_POST['b_s_comment'])){


    $query = $db->prepare("INSERT INTO comments(content,iduserfk,idarticlesfk) VALUES(:content,:iduserfk,:idarticlesfk)");
    $query->execute([
        'content' => $_POST['comment'],
        'iduserfk' => $_SESSION['iduser'],
        'idarticlesfk' => $_POST['id_article']
    ]);
    header('Location: home.php');

}
if(isset($_POST['b_d_comm'])){
    $query = $db->prepare("DELETE FROM comments WHERE idcomments = ".$_POST['id_com']);
    $query->execute();
    header('Location: home.php');
}
if(isset($_POST['b_d_article'])){
    $query = $db->prepare("DELETE FROM comments WHERE idarticlesfk = ".$_POST['id_article']);
    $query->execute();
    $query = $db->prepare("DELETE FROM articles WHERE idarticles = ".$_POST['id_article']);
    $query->execute();
    header('Location: home.php');
}
?>
