<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    	<link href="blog.css" rel="stylesheet" />
        <title>Cambridge Motors</title>
	<link href="blog.css" rel="stylesheet" />
    </head>

    <body class="container">
        <header class="row">
           <img src="image/logo.png" alt=""><h1> Cambridge Motors </h1>
        </header>

        <section>
            <nav>
              <ul><?php
              session_start();

              try {

                $bdd = new PDO('mysql:host=pedago2;dbname=rt1a1718_bk339265','bk339265','mdpbk339265',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $req = "SELECT id_billet FROM billet ORDER BY id_billet DESC LIMIT 1";
                $res = $bdd->prepare($req);
                $res->execute();
                $ligne = $res->fetch();
                $_SESSION["max"]=$ligne['id_billet'];

                }

              catch (Exception $e) {
                // message en cas d'erreur
                die ('Erreur : ' . $e->getMessage());
              }
              finally {
                $bdd = null;  //fermeture de la connexion
              }

              $max=$_SESSION["max"];

              if ($_SESSION["admin"]==0) {
                echo "
                  <li class='actif'><a href='insertion_contenu.php?action=3'>Accueil</a></li><!aaaa
                  aaa><li><a href='commentaires.php?article=$max'>Commentaires</a></li><!aaaa
                  aaa><li><a href='login.php'>Connexion</a></li><!aaaa
                  aaa><li><a>Histoire</a></li><!aaaa
                  aaa><li><a>Contact</a></li>";
              }

              elseif ($_SESSION["admin"]==1) {
                echo "
                  <li class='actif'><a href='insertion_contenu.php?action=3'>Accueil</a></li><!aaaa
                  aaa><li><a href='commentaires.php?article=$max'>Commentaires</a></li><!aaaa
                aaa><li><a href='administration.php'>Administration</a></li><!aaaa
                aaa><li><a href='insertion_contenu.php?action=4'>Deconnexion</a></li><!aaaa
                aaa><li><a>Contact</a></li>";
              }

                  ?>
              </ul>
            </nav>
        </section>


      <section class="row">
        <article class="col-md-8">
          <a href='index.php'>Retour à la liste des articles</a>
      <?php
              include('./fonctions.php');

            // Connexion a la base de donnee
              try {
                $bdd = new PDO('mysql:host=pedago2;dbname=rt1a1718_bk339265','bk339265','mdpbk339265',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $req = "SELECT * FROM billet WHERE id_billet IN (:article)";
                // préparation de la requête avec les noms
                $res = $bdd->prepare($req);
                $article = htmlentities($_GET['article']);
                $res->execute(array('article'=> $article));
                $res->bindValue(':article',$article);
                $res->execute();


                // affichage des billet
                if ((htmlentities($_GET["article"] <= $max))&& (htmlentities($_GET["article"] >= 1))) {
                while ($ligne = $res->fetch())
                  {
                    $date=date_create($ligne['date_creation']);
                    echo "<p><h2> " . $ligne['titre'] ."</h2></p>". "<p><h5> le ". date_format($date, 'd-m-Y')." à ". date_format($date, 'H:i') ." </h5> " . $ligne['contenu'] ."</p>";
                  }
              }

              else {
                message_erreur("Ce Billet n'existe pas");
              }
            }

              catch (Exception $e) {
                // message en cas d'erreur
                die ('Erreur : ' . $e->getMessage());
              }
              finally {
                $bdd = null;  //fermeture de la connexion
              }

              if ((htmlentities($_GET["article"] <= $max))&& (htmlentities($_GET["article"] >= 1))) {

              try {
                $bdd = new PDO('mysql:host=pedago2;dbname=rt1a1718_bk339265','bk339265','mdpbk339265',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $req = "SELECT * FROM commentaire WHERE id_billet = :article ORDER BY date_commentaire DESC LIMIT 5";
                // préparation de la requête avec les noms
                $res = $bdd->prepare($req);
                $article = htmlentities($_GET['article']);
                $res->execute(array('article'=> $article));
                $res->bindValue(':article',$article);
                $res->execute();

          //      <input type="hidden" name="a" value="1" />

                echo "<p><h4> Ajouter un commentaire: </h4></p>";
                // formulaire pour rajouter un commentaire
                echo "
                  <form action='insertion_contenu.php' method='post'>
                    <p>
                      Auteur : <input type='text' name='auteur'/>
                      Commentaire : <input type='text' name='com'/>
                      <input type='hidden' name='article' value='$article' />
                      <input type='submit' value='Valider'/>
                    </p>
                  </form>";



                // affichage des commentaires
                while ($ligne = $res->fetch())
                  {

                    echo "<p><h3> " . $ligne['auteur'] . ": </h3> " . $ligne['commentaire'] ."</p>";
                  }

                }


              catch (Exception $e) {
                // message en cas d'erreur
                die ('Erreur : ' . $e->getMessage());
              }
              finally {
                $bdd = null;  //fermeture de la connexion
              }
              }

              else {
                message_erreur("Ce Billet n'existe pas");
              }


      ?>

    </article>
  </section>

 </body>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>
