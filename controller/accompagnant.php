<?php

    $accompagnant = getUtilByMail($_SESSION['mail']);
    $lesTickets = getTicket($accompagnant->getPermis());
    $mesTickets = getMesTickets($accompagnant);
    $historiqueTicket = getHistoriqueById($accompagnant->getId(), $accompagnant->getStatus()->getId());

    $lesNotes = [];

    /*if ($accompagnant->getNotes() != null){
        foreach($accompagnant->getNotes() as $uneNote){
            $lesNotes[] = [
            "trajet" => $uneNote->getTrajet(),
            "demandeur" => $uneNote->getDemandeur(),
            "accompagnant" => $uneNote->getAccompagnant(),
            "note" => $uneNote->getNote(),
            "commentaire" => $uneNote->getCommentaire()
            ];
        }
    }*/

    if (!empty($lesTickets)){
        foreach($lesTickets as $unTicket){
            if(isset($_POST['reserverTicket'.$unTicket->getId()])){
                reserveTicket($unTicket->getId(), $accompagnant->getId());
                header("Location:index.php");
            }
        }
    }

    if (!empty($mesTickets)){
        foreach($mesTickets as $unTicket){
            if(isset($_POST['annuleTicket'.$unTicket->getId()])){
                annuleTicket($unTicket->getId());
                $lesTickets[] = $unTicket;
            }
        }
    }

    if (isset($_POST['modifProfil'])){
        $nouvNom = htmlspecialchars($_POST['modifNom']);
        $nouvPrenom = htmlspecialchars($_POST['modifPrenom']);

        $accompagnant->setNom($nouvNom);
        $accompagnant->setPrenom($nouvPrenom);

        editProfil($accompagnant->getId(), $accompagnant->getNom(), $accompagnant->getPrenom());
    }

    if (isset($_POST['editMdp'])){
        $code = "";

        for ($n = 0 ; $n <= 10 ; $n++){
            $code .= random_int(0, 9);
        }

        $output = null;
        $result = null;
        exec("python py/mdpEdit.py ".$accompagnant->getMail()." ".$code, $output, $result);

        $_SESSION['code'] = $code;
    }

    if (isset($_POST['editMdpConf'])){
        $mdp = htmlspecialchars($_POST['editMdpNouv']);
        $mdpConf = htmlspecialchars($_POST['editMdpNouvConf']);

        if (checkMdpConf($mdp, $mdpConf) && checkMdpValue($mdp)){
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            editMdp($accompagnant->getId(), $mdp);
        }
    }

    $lesNotesJson = [];
    if (!empty($accompagnant->getNotes())){
        foreach($accompagnant->getNotes() as $uneNote){
            $lesNotesJson[] = [
                "trajet" => $uneNote->getTrajet()->getAdresseDepart()." - ".$uneNote->getTrajet()->getAdresseArrivee(),
                "demandeur" => $uneNote->getDemandeur()->getNom()." ".$uneNote->getDemandeur()->getPrenom(),
                "note" => $uneNote->getNote(),
                "commentaire" => $uneNote->getCommentaire()
            ];
        }
    }

    $lesTicketsHistoriqueJson = [];

    if (!empty($historiqueTicket)){
        foreach($historiqueTicket as $unHistoriqueTicket){
            $leDemandeur = [
                "id" => $unHistoriqueTicket->getDemandeur()->getId(),
                "nom" => $unHistoriqueTicket->getDemandeur()->getNom(),
                "prenom" => $unHistoriqueTicket->getDemandeur()->getPrenom(),
                "role" => $unHistoriqueTicket->getDemandeur()->getStatus()->getLibelle()
            ];
            $laNote = [];
            if (!empty($unHistoriqueTicket->getNote())){
                $laNote = [
                    "demandeur" => $unHistoriqueTicket->getNote()->getDemandeur()->getNom()." ".$unHistoriqueTicket->getNote()->getDemandeur()->getNom(),
                    "note" => $unHistoriqueTicket->getNote()->getNote(),
                    "commentaire" => $unHistoriqueTicket->getNote()->getCommentaire()
                ];
            }
            $lesTicketsHistoriqueJson[] = [
                "id" => $unHistoriqueTicket->getId(),
                "demandeur" => $leDemandeur,
                "transport" => $unHistoriqueTicket->getTransport()->getLibelle(),
                "adresseDepart" => $unHistoriqueTicket->getAdresseDepart(),
                "adresseArrivee" => $unHistoriqueTicket->getAdresseArrivee(),
                "note" => checkNote($unHistoriqueTicket->getDemandeur()->getId(), $unHistoriqueTicket->getAccompagnateur()->getId()),
                "isShowNote" => false,
                "laNote" => $laNote
            ];
        }
    }

    $jsonHistorique = json_encode($lesTicketsHistoriqueJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $LAccompagnant = [
        "id" => $accompagnant->getId(),
        "nom" => $accompagnant->getNom(),
        "prenom" => $accompagnant->getPrenom(),
        "mail" => $accompagnant->getMail(),
        "role" => $accompagnant->getStatus()->getLibelle(),
        "notes" => $lesNotesJson,
        "moyenne" => $accompagnant->getMoyenne()->getMoyenne()
    ];



    $jsonAccompagnant = json_encode($LAccompagnant, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesTickesJson = [];
    
    if (!empty($lesTickets)){
        foreach($lesTickets as $unTicket){
            $lesTickesJson[] =[
                "id" => $unTicket->getId(),
                "adresseDepart" => $unTicket->getAdresseDepart(),
                "adresseArrivee" => $unTicket->getAdresseArrivee(),
                "date" => $unTicket->getDate(),
                "heure" => $unTicket->getHeure(),
                "transport" => $unTicket->getTransport()->getLibelle(),
                "demandeur" => $unTicket->getDemandeur()->getNom()." ".$unTicket->getDemandeur()->getPrenom(),
                "isShowMap" => false
            ];
        }
    }

    $jsonTickets = json_encode($lesTickesJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $mesTicketsJson = [];

    if (!empty($mesTickets)){
        foreach($mesTickets as $unTicket){
            $mesTicketsJson[] = [
                "id" => $unTicket->getId(),
                "demandeur" => $unTicket->getDemandeur()->getNom()." ".$unTicket->getDemandeur()->getNom(),
                "accompagnateur" => $accompagnant->getNom()." ".$accompagnant->getPrenom(),
                "adresseDepart" => $unTicket->getAdresseDepart(),
                "adresseArrivee" => $unTicket->getAdresseArrivee(),
                "date" => $unTicket->getDate(),
                "heure" => $unTicket->getHeure(),
                "transport" => $unTicket->getTransport()->getLibelle()
            ];
        }
    }

    $jsonMesTickets = json_encode($mesTicketsJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesTansports = getTransport();

    $lesTansportsJson = [];

    if (!empty($lesTansports)){
        foreach($lesTansports as $unTransport){
            $lesTansportsJson[] = [
                "id" => $unTransport->getId(),
                "libelle" => $unTransport->getLibelle()
            ];
        }
    }

    $jsonTransport = json_encode($lesTansportsJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    if (isset($_POST['logout'])){
        session_destroy();
        header("Location:index.php");
    }

    require "view/view-accompagnant.php";