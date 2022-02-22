
<!DOCTYPE html>
<html>
<body>
<h1>AJOUTER UNE COMMANDE</h1>
<?php
require_once('./api.php');
$db = getConnexion();
$req = "SELECT * from menu m";
$stmt = $db->prepare($req);
$stmt ->execute(); 
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Numero: <input type="text" name="number">
 
        <select name='menu'>
            
        <option value="0">Select Menu:</option>
        <?php
        for($i = 0; $i < count($menus); $i++){
            $repas = $menus[$i]['name'];
            $id_menu = $menus[$i]['id'];
            
        echo "<option value=$id_menu >$repas</option>";
        }
        ?>
        </select>        
  <input type="submit">
</form>
<?php

//POST DES COMMANDES
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $request = true; 
    if($request){ 
    $number = strip_tags($_POST['number']);
    $menu = ceil($_POST['menu']);

    
    //RECUPERER LE CLIENT A PARTIR DE SON number
    $db = getConnexion();
    $sql_client = "SELECT id FROM `api_restaurant`.`client` WHERE `number` = '$number' ";
    $id_client = $db->prepare($sql_client);
    $id_client ->execute();
    $client = $id_client->fetch();
   
    $sql_commande = "INSERT INTO `commande` (`commande_id`, `client_id`, `menu_id`) VALUES (NULL, '$client[0]', '$menu')";

    //on prepare la requete
    $query = $db->prepare($sql_commande);
    //on injecte la requete
    // $query->bindValue(":number", $number);
    // $query->bindValue(":menu", $menu);

    $query->execute();
    if (!$query->execute()){
        die('Une erreur est survenue');
    }
    $id_commande = $db->lastInsertId();
    die("Commande ajouté sous le numero $id_commande");
    }

    else {
        die("le formulaire est imcomplet");
    }
}



?>



<?php
require_once('./api.php');
//www.resto.com/commandes
//www.resto.com/commandes/: clients
//www.resto.com/commande/:id
// $servername = "localhost";
// $username = "api_restaurant";
// $password = "";

// $conn = mysqli_connect($servername, $username, $password);

try{
    if (!empty($_GET['demande'])){
       $url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));
        switch($url[0]){
            case 'commandes':
                if (empty($url[1])){
                    getCommandes()();
                }else {
                    getCommandeByClient($url[1]);
                }
            break;
            case "commande" :
                if (!empty($url[1])){ 
                    getCommandeById($url[1]);
                } else{  
                    throw new Exception("vous n'avez pas renseigner de commande.");
                }
        }
    } else {
       

    }
} catch(Exception $e){
    $erreur = [
        "message" => $e->getMessage(),
        "code" => $e->getCode()
    ];
    print_r($erreur);
}

?>