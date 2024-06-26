<?php

    $lesSignalements = getSignalement();

    $lesTickets = getLesTickets();

    $lesUtilisateurs = getUtil();

    if (!empty($lesSignalements)){
        foreach($lesSignalements as $unSignalement){
            if (isset($_POST['banUtil'.$unSignalement->getUtilSignale()->getId().$unSignalement->getUtilVise()->getId()])){
                deleteSignalementById($unSignalement->getUtilSignale()->getId(), $unSignalement->getUtilVise()->getId());
                deleteUtilById($unSignalement->getUtilVise()->getId());
            }
            
            if (isset($_POST['annuleSignalement'.$unSignalement->getUtilSignale()->getId().$unSignalement->getUtilVise()->getId()])){
                deleteSignalementById($unSignalement->getUtilSignale()->getId(), $unSignalement->getUtilVise()->getId());
            }
        }
    }

    if (!empty($lesTickets)){
        foreach($lesTickets as $unTicket){
            if (isset($_POST['deleteTicket'.$unTicket->getId()])){
                deleteTicket($unTicket->getId());
                header("Location:index.php");
            }
        }
    }

    if (!empty($lesUtilisateurs)){
        foreach($lesUtilisateurs as $unUtilisateur){
            if (isset($_POST['deleteUtilisateur'.$unUtilisateur->getId()])){
                deleteUtilById($unUtilisateur->getId());
                header("Location.index.php");
            }
        }
    }

    $lesSignalementsJson = [];

    if (!empty($lesSignalements)){
        foreach($lesSignalements as $unSignalement){
            $lesSignalementsJson[] = [
                "idDemandeur" => $unSignalement->getUtilSignale()->getId(),
                "demandeur" => $unSignalement->getUtilSignale()->getNom()." ".$unSignalement->getUtilSignale()->getPrenom(),
                "mailDemandeur" => $unSignalement->getUtilSignale()->getMail(),
                "roleDemandeur" => $unSignalement->getUtilSignale()->getStatus()->getLibelle(),
                "idAccompagnant" => $unSignalement->getUtilVise()->getId(),
                "accompagnant" => $unSignalement->getUtilVise()->getNom()." ".$unSignalement->getUtilVise()->getPrenom(),
                "mailAccompagnant" => $unSignalement->getUtilVise()->getMail(),
                "roleAccompagnant" => $unSignalement->getUtilVise()->getStatus()->getLibelle(),
                "motif" => $unSignalement->getMotif(),
                "commentaire" => $unSignalement->getCommentaire()
            ];
        }
    }

    $jsonSignalement = json_encode($lesSignalementsJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesTicketsJson = [];

    if (!empty($lesTickets)){
        foreach($lesTickets as $unTicket){
            if (!empty($unTicket->getAccompagnateur())){
                $lAccompagnant = $unTicket->getAccompagnateur()->getNom()." ".$unTicket->getAccompagnateur()->getPrenom();
            }
            else{
                $lAccompagnant = null;
            }

            $lesTicketsJson[] = [
                "id" => $unTicket->getId(),
                "demandeur" => $unTicket->getDemandeur()->getNom()." ".$unTicket->getDemandeur()->getPrenom(),
                "accompagnant" => $lAccompagnant,
                "adresseDepart" => $unTicket->getAdresseDepart(),
                "adresseArrivee" => $unTicket->getAdresseArrivee(),
                "date" => $unTicket->getDate(),
                "heure" => $unTicket->getHeure(),
                "transport" => $unTicket->getTransport()->getLibelle()
            ];
        }
    }

    $jsonTicket = json_encode($lesTicketsJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesUtilisateursJson = [];

    if (!empty($lesUtilisateurs)){
        foreach($lesUtilisateurs as $unUtilisateur){
            $lesUtilisateursJson[] = [
                "id" => $unUtilisateur->getId(),
                "nom" => $unUtilisateur->getNom(),
                "prenom" => $unUtilisateur->getPrenom(),
                "mail" => $unUtilisateur->getMail(),
                "role" => $unUtilisateur->getStatus()->getLibelle()
            ];
        }
    }

    $jsonUtilisateur = json_encode($lesUtilisateursJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    if (isset($_POST['logout'])){
        session_destroy();
        header("Location:index.php");
    }

    require "view/view-admin.php";