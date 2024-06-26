<?php

function getTransportById($id) {
    $pdo = connexionPDO();

    $transport = $pdo->prepare("SELECT * FROM transport WHERE id = :id");
    $transport->bindValue(':id', $id, PDO::PARAM_INT);
    $transport->execute();

    $result = $transport->fetch(PDO::FETCH_ASSOC);

    $id = $result['id'];
    $libelle = $result['libelle'];

    $leTransport = new Transport($id, $libelle);

    return $leTransport;
}

function getTransport() {
    $pdo = connexionPDO();

    $transport = $pdo->prepare("SELECT * FROM transport");
    $transport->execute();

    $ligne = $transport->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $id = $ligne['id'];
        $libelle = $ligne['libelle'];
        
        $lesTransport[] = new Transport($id, $libelle);

        $ligne = $transport->fetch(PDO::FETCH_ASSOC);
    }
    return $lesTransport;
}