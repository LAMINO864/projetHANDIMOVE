<?php

    function getDemandeurByMail($mail) {
        $pdo = connexionPDO();

        $util = $pdo->prepare("SELECT * FROM util WHERE mail = :mail");
        $util->bindValue(':mail', $mail, PDO::PARAM_STR);
        $util->execute();

        $result = $util->fetch(PDO::FETCH_ASSOC);

        $id = $result['id'];
        $nom = $result['nom'];
        $prenom = $result['prenom'];
        $photoProfile = $result['photoProfil'];
        $mail = $result['mail'];
        $statusCheck = $result['status'];
        $role = getStatusById($statusCheck);
        $ticket = getTicketById($id);
        $ticketValide = getTicketValideById($id);

        $demandeur = new Demandeur($id, $nom, $prenom, $photoProfile, $mail, $role, $ticket, $ticketValide);
        return $demandeur;
    }

    function getDemandeurById($id){
        $pdo = connexionPDO();

        $leDemamndeurReq = $pdo->prepare("SELECT mail, nom, prenom FROM util WHERE id = :id");
        $leDemamndeurReq->bindValue(':id', $id, PDO::PARAM_INT);
        $leDemamndeurReq->execute();

        $ligne = $leDemamndeurReq->fetch(PDO::FETCH_ASSOC);
        $mail = $ligne['mail'];
        $nom = $ligne['nom'];
        $prenom = $ligne['prenom'];

        $leDemandeur = new Demandeur($id, $nom, $prenom, null, $mail, null, null, null);

        return $leDemandeur;
    }