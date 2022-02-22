<?php

function getCommandes(){
    $pdo = getConnexion();
    $req = "SELECT c.commande_id, p.last_name, p.first_name, p.number, m.name, m.price from ((commande c inner join client p  on c.client_id = p.id) inner join menu m on c.client_id = m.id);";
    $stmt = $pdo->prepare($req);
    $stmt ->execute();  
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    sendJSON($commandes);
} 

function getCommandeByClient($client){
    $pdo = getConnexion();
    $req = "SELECT c.commande_id, p.last_name, p.first_name, p.number, m.name, m.price from ((commande c inner join client p  on c.client_id = p.id) inner join menu m on c.client_id = m.id) where p.last_name= :client;";
    $stmt = $pdo->prepare($req);
    $stmt ->bindValue(":client", $client,PDO::PARAM_STR);
    $stmt ->execute();  
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    sendJSON($commandes);  
}
function getCommandeById($id){
    $pdo = getConnexion();
    $req = "SELECT c.commande_id, p.last_name, p.first_name, p.number, m.name, m.price from ((commande c inner join client p  on c.client_id = p.id) inner join menu m on c.client_id = m.id) where c.commande_id= :id;";
    $stmt = $pdo->prepare($req);
    $stmt ->bindValue(":id", $id,PDO::PARAM_INT);
    $stmt ->execute();  
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    sendJSON($commandes);

}

function getConnexion(){
    return new PDO ("mysql:host=localhost;dbname=api_restaurant;charset=utf8",'root','root');
}
function sendJSON($infos){

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    echo json_encode($infos,JSON_UNESCAPED_UNICODE);
}


?>