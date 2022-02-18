

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
  Numero: <input type="text" name="numero">
 
        <select>
            
        <option value="0">Select Menu:</option>
        <?php
        for($i = 0; $i < count($menus); $i++){
            $repas = $menus[$i]['name'];
            $id = $menus[$i]['id'];
        echo "<option value='$id'>$repas</option>";
        }
        ?>
      

        </select>
        
  <input type="submit">
</form>




<?php
require_once('./api.php');
//www.resto.com/commandes
//www.resto.com/commande/: clients
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