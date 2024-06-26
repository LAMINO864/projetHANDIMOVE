<?php

function getTicketById($id) {
    $pdo = connexionPDO();

    $ticket = $pdo->prepare("SELECT * FROM ticket WHERE idH = :id AND idA IS NULL");
    $ticket->bindValue(':id', $id, PDO::PARAM_INT);
    $ticket->execute();

    $ligne = $ticket->fetch(PDO::FETCH_ASSOC);

    while($ligne) {
        $id = $ligne['id'];
        $demandeur = $ligne['idH'];
        $accompagnant = $ligne['idA'];
        $adresseD = $ligne['adresseD'];
        $adresseA = $ligne['adresseA'];
        $date = $ligne['date'];
        $heure = $ligne['heure'];
        $transport = $ligne['transport'];
        $leTransport = getTransportById($transport);

        $lesTickets[] = new Ticket($id, $demandeur, $accompagnant, $adresseD, $adresseA, $date, $heure, $leTransport);
        $ligne = $ticket->fetch(PDO::FETCH_ASSOC);;
    }
    if (!empty($lesTickets)) {
        return $lesTickets;
    }
    else {
        return null;
    }

}

function getTicketValideById($id){
    $pdo = connexionPDO();

    $mesTickes = $pdo->prepare("SELECT ticket.id, ticket.idA, ticket.adresseD, ticket.adresseA, ticket.date, ticket.heure, ticket.transport,
                                util.nom, util.prenom, util.mail,
                                role.id AS `idRole`, role.libelle AS `libelleRole`
                                FROM ticket
                                INNER JOIN util ON util.id = ticket.idA
                                INNER JOIN `role` ON util.status = role.id   
                                WHERE ticket.idH = :id AND ticket.idA IS NOT NULL");
    $mesTickes->bindValue(':id', $id, PDO::PARAM_INT);
    $mesTickes->execute();

    $ligne = $mesTickes->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $idTrajet = $ligne['id'];

        $checkPermis = $pdo->prepare("SELECT detientpermis FROM permisutil WHERE idUtil = :id");
        $checkPermis->bindValue(':id', $ligne['idA'], PDO::PARAM_INT);
        $checkPermis->execute();
        $permisA = $checkPermis->fetch(PDO::FETCH_ASSOC);

        $role = new Role($ligne['idRole'], $ligne['libelleRole']);
        $notes = getNoteByIdUtil($ligne['idA']);
        $moyenne = getNoteUtil($ligne['idA']);
        $accompagnant = new Accompagnant($ligne['idA'], $ligne['nom'], $ligne['prenom'], null, $ligne['mail'], $role, $permisA, $notes, null, $moyenne);
        $adresseD = $ligne['adresseD'];
        $adresseA = $ligne['adresseA'];
        $date = $ligne['date'];
        $heure = $ligne['heure'];
        $leTransport = getTransportById($ligne['transport']);

        $mesTicketValide[] = new Ticket($idTrajet, null, $accompagnant, $adresseD, $adresseA, $date, $heure, $leTransport);
        $ligne = $mesTickes->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($mesTicketValide)){
        return $mesTicketValide;
    }
    else{
        return null;
    }
}

function registerTicket($idDemandeur, $adresseD, $adresseA, $date, $heure, $transport) {
    $pdo = connexionPDO();

    $ticket = $pdo->prepare("INSERT INTO ticket VALUES (:id, :idDemandeur, :idAccompagnant, :adresseD, :adresseA, :datee, :heure, :transport)");
    $ticket->bindValue(':id', null, PDO::PARAM_INT);
    $ticket->bindValue(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
    $ticket->bindValue(':idAccompagnant', null, PDO::PARAM_INT);
    $ticket->bindValue(':adresseD', $adresseD, PDO::PARAM_STR);
    $ticket->bindValue(':adresseA', $adresseA, PDO::PARAM_STR);
    $ticket->bindValue(':datee', $date, PDO::PARAM_STR);
    $ticket->bindValue(':heure', $heure, PDO::PARAM_STR);
    $ticket->bindValue(':transport', $transport, PDO::PARAM_INT);
    $ticket->execute();
}

function editTicket($id, $adresseD, $adresseA, $date, $heure, $transport){
    $pdo = connexionPDO();

    $ticket = $pdo->prepare("UPDATE ticket SET adresseD = :adresseD, adresseA = :adresseA, date = :date, heure = :heure, transport = :transport 
                            WHERE id = :id");
    $ticket->bindValue(':id', $id, PDO::PARAM_INT);
    $ticket->bindValue(':adresseD', $adresseD, PDO::PARAM_STR);
    $ticket->bindValue(':adresseA', $adresseA, PDO::PARAM_STR);
    $ticket->bindValue(':date', $date, PDO::PARAM_STR);
    $ticket->bindValue(':heure', $heure, PDO::PARAM_INT);
    $ticket->bindValue(':transport', $transport, PDO::PARAM_INT);
    $ticket->execute();
}

function deleteTicket($id){
    $pdo = connexionPDO();

    $ticket = $pdo->prepare("DELETE FROM ticket WHERE id = :id");
    $ticket->bindValue(':id', $id, PDO::PARAM_INT);
    $ticket->execute();
}

function annuleTicket($id){
    $pdo = connexionPDO();

    $annuleTicker = $pdo->prepare("UPDATE ticket SET idA = null WHERE id = :id");
    $annuleTicker->bindValue(':id', $id, PDO::FETCH_ASSOC);
    $annuleTicker->execute();
}

function getTicket($permisUtil){
    $pdo = connexionPDO();

    $req = "SELECT * FROM ticket WHERE idA IS NULL";
    if ($permisUtil == 0){
        $req = $req." AND transport != 2";
    }

    $lesTicketsSearch = $pdo->prepare($req);
    $lesTicketsSearch->execute();

    $ligne = $lesTicketsSearch->fetch(PDO::FETCH_ASSOC);
    
    while($ligne){
        $idTrajet = $ligne['id'];
        $idH = $ligne['idH'];
        $adresseD = $ligne['adresseD'];
        $adresseA = $ligne['adresseA'];
        $date = $ligne['date'];
        $heure = $ligne['heure'];
        $transport = $ligne['transport'];

        $leDemandeur = getDemandeurById($idH);
        $leTransport = getTransportById($transport);

        $lesTickets[] = new Ticket($idTrajet, $leDemandeur, null, $adresseD, $adresseA, $date, $heure, $leTransport);
        $ligne = $lesTicketsSearch->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($lesTickets)){
        return $lesTickets;
    }
    else{
        return null;
    }
}

function reserveTicket($idTicket, $idAccompagnant){
    $pdo = connexionPDO();

    $reservation = $pdo->prepare("UPDATE ticket SET idA = :idA WHERE id = :id");
    $reservation->bindValue(':id', $idTicket, PDO::PARAM_INT);
    $reservation->bindValue(':idA', $idAccompagnant, PDO::PARAM_INT);
    $reservation->execute();
}

function getMesTickets($accompagnant){
    $pdo = connexionPDO();

    $ticket = $pdo->prepare("SELECT * FROM ticket WHERE idA = :id");
    $ticket->bindValue(':id', $accompagnant->getId(), PDO::PARAM_INT);
    $ticket->execute();

    $ligne = $ticket->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $idTrajet = $ligne['id'];
        $idH = $ligne['idH'];
        $adresseD = $ligne['adresseD'];
        $adresseA = $ligne['adresseA'];
        $date = $ligne['date'];
        $heure = $ligne['heure'];
        $transport = $ligne['transport'];

        $leDemandeur = getDemandeurById($idH);
        $leTransport = getTransportById($transport);

        $mesTickets[] = new Ticket($idTrajet, $leDemandeur, $accompagnant, $adresseD, $adresseA, $date, $heure, $leTransport);
        $ligne = $ticket->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($mesTickets)){
        return $mesTickets;
    } 
    else {
        return null;
    }
}

function getLesTickets(){
    $pdo = connexionPDO();

    $getTicket = $pdo->prepare("SELECT ticket.id AS `idT`, d.nom AS `nomD`, d.prenom AS `prenomD`,
                                a.nom AS `nomA`, a.prenom AS `prenomA`, 
                                ticket.adresseD, ticket.adresseA, ticket.date, ticket.heure,
                                transport.id AS `idTr`, transport.libelle AS `libelleTr`, 
                                roleD.id AS `idRole`, roleD.libelle AS `libelleRole`,
                                roleA.id AS `idRoleA`, roleA.libelle AS `libelleRoleA`
                                FROM ticket
                                INNER JOIN util d ON ticket.idH = d.id
                                LEFT JOIN util a ON ticket.idA = a.id
                                INNER JOIN transport ON ticket.transport = transport.id
                                INNER JOIN `role` roleD ON d.status = roleD.id
                                LEFT JOIN `role` roleA ON a.status = roleA.id
                                ");
    $getTicket->execute();

    $ligne = $getTicket->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $leRole = new Role($ligne['idRole'], $ligne['libelleRole']);
        $leDemandeur = new Demandeur(null, $ligne['nomD'], $ligne['prenomD'], null, null, $leRole, null, null);
        if (!empty($ligne['nomA'])){
            $leRoleA = new Role($ligne['idRoleA'], $ligne['libelleRoleA']);
            $lAccompagnant = new Accompagnant(null, $ligne['nomA'], $ligne['prenomA'], null, null, $leRoleA, null, null, null);
        }
        else {
            $lAccompagnant = null;
        }
        $leTransport = new Transport($ligne['idTr'], $ligne['libelleTr']);

        $lesTickets[] = new Ticket($ligne['idT'], $leDemandeur, $lAccompagnant, $ligne['adresseD'], $ligne['adresseA'], $ligne['date'], $ligne['heure'], $leTransport);

        $ligne = $getTicket->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($lesTickets)){
        return $lesTickets;
    }
    else {
        return null;
    }
}

function getLesTicketsValides(){
    $pdo = connexionPDO();
}