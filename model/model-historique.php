<?php

function termineTicket($id){
    $pdo = connexionPDO();

    $termineTicket = $pdo->prepare("SELECT idH, idA, adresseD, adresseA, transport FROM ticket WHERE id = :id");
    $termineTicket->bindValue(':id', $id, PDO::PARAM_INT);
    $termineTicket->execute();

    $ligne = $termineTicket->fetch(PDO::FETCH_ASSOC);


    $histoTicket = new Historique($id, $ligne['idH'], $ligne['idA'], $ligne['adresseD'], $ligne['adresseA'], $ligne['transport'], null);

    $ajoutHistorique = $pdo->prepare("INSERT INTO historiquetrajet VALUES (:id, :idH, :idA, :adresseD, :adresseA, :transport)");
    $ajoutHistorique->bindValue(':id', $histoTicket->getId(), PDO::PARAM_INT);
    $ajoutHistorique->bindValue(':idH', $histoTicket->getDemandeur(), PDO::PARAM_INT);
    $ajoutHistorique->bindValue(':idA', $histoTicket->getAccompagnateur(), PDO::PARAM_INT);
    $ajoutHistorique->bindValue(':adresseD', $histoTicket->getAdresseDepart(), PDO::PARAM_STR);
    $ajoutHistorique->bindValue(':adresseA', $histoTicket->getAdresseArrivee(), PDO::PARAM_STR);
    $ajoutHistorique->bindValue('transport', $histoTicket->getTransport(), PDO::PARAM_INT);
    $ajoutHistorique->execute();

    deleteTicket($id);
}

function getHistoriqueById($id, $idRole){
    $pdo = connexionPDO();

    $req = "SELECT historiquetrajet.id AS `id`, idH, idA, adresseD, adresseA, transport, t.libelle AS `libelleT`,
            h.nom AS `nomH`, h.prenom AS `prenomH`, h.mail AS `mailH`, h.status AS `statusH`,
            a.nom AS `nomA`, a.prenom AS `prenomA`, a.mail AS `mailA`, a.status AS `statusA`,
            role.id AS `idRoleA`, role.libelle AS `libelleRoleA`,
            r.id AS `idRoleD`, r.libelle AS `libelleRoleD`
            FROM historiquetrajet
            INNER JOIN util h ON h.id = historiquetrajet.idH
            INNER JOIN util a ON a.id = historiquetrajet.idA
            INNER JOIN `role` ON a.status = role.id
            INNER JOIN `role` r ON h.status = r.id
            INNER JOIN transport t ON t.id = historiquetrajet.transport
            ";

    if ($idRole == 1){
        $req .= "WHERE idH = :id";
    }
    else{
        $req .= "WHERE idA = :id";
    }

    $ticket = $pdo->prepare($req);
    $ticket->bindValue(':id', $id, PDO::PARAM_INT);
    $ticket->execute();

    $ligne = $ticket->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $leRoleD = new Role($ligne['idRoleD'], $ligne['libelleRoleD']);
        $demandeur = new Demandeur($ligne['idH'], $ligne['nomH'], $ligne['prenomH'], null, $ligne['mailH'], $leRoleD, null, null);
        $leRoleA = new Role($ligne['idRoleA'], $ligne['libelleRoleA']);
        $accompagnant = new Accompagnant($ligne['idA'], $ligne['nomA'], $ligne['prenomA'], null, $ligne['mailA'], $leRoleA, null, null, null, getNoteUtil($ligne['idA']));
        $leTransporrt = new Transport($ligne['transport'], $ligne['libelleT']);
        $laNote = getNoteByIdTrajet($ligne['id']);
        $histoTicket[] = new Historique($ligne['id'], $demandeur, $accompagnant, $ligne['adresseD'], $ligne['adresseA'], $leTransporrt, $laNote);

        $ligne = $ticket->fetch(PDO::FETCH_ASSOC);
    }
    if(!empty($histoTicket)){
        return $histoTicket;
    }
    else{
        return null;
    }
}