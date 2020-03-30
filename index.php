<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once('/users/21813169/private/mysql_config.php');

/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
//$GameStorage=new GameStorageFile('src/games_db.txt');
//
$servername = MYSQL_HOST.":".MYSQL_PORT;
$username = MYSQL_USER;
$password = MYSQL_PASSWORD;
$dbname = MYSQL_DB;
$connexion;

try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $connexion=$conn; 
    }
    catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
          }


$AccountStorage=new AccountStorageMySQL($connexion);
$GameStorage=new GameStorageMySQL($connexion);
$router->main($GameStorage,$AccountStorage);
?>