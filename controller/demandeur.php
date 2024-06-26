<?php

    $demandeur = getDemandeurByMail($_SESSION['mail']);

    $mesTicketsValides = getTicketValideById($demandeur->getId());

    $historiqueTicket = getHistoriqueById($demandeur->getId(), $demandeur->getStatus()->getId());

    $lesTransport = getTransport();

    $motifSignalement = getMotifSignalement();

    $lesAccompagnants = getAccompagnant();

    if (isset($_POST['ajoutTicket'])){
        $date = htmlspecialchars($_POST['dateTicket']);
        $heure = htmlspecialchars($_POST['heureTicket']);
        $transport = htmlspecialchars($_POST['transportTicket']);
        $adresseD = htmlspecialchars($_POST['adresseDepartTicket']);
        $adresseA = htmlspecialchars($_POST['adresseArriveeTicket']);

        $id = registerTicket($demandeur->getId(), $adresseD, $adresseA, $date, $heure, $transport);

        $leTransport = getTransportById($transport);

        $ticket = new Ticket(1, $demandeur, '', $adresseD, $adresseA, $date, $heure, $leTransport);

        $demandeur->addTicket($ticket);
    }
    if (!empty($demandeur->getLesTickets())){
        foreach($demandeur->getLesTickets() as $unTicket){
            if (isset($_POST['editTicket'.$unTicket->getId()])){
                $adresseD = htmlspecialchars($_POST['editDepart'.$unTicket->getId()]);
                $adresseA = htmlspecialchars($_POST['editArrivee'.$unTicket->getId()]);
                $transport = htmlspecialchars($_POST['editTransport'.$unTicket->getId()]);
                $date = htmlspecialchars($_POST['editDate'.$unTicket->getId()]);
                $heure = htmlspecialchars($_POST['editHeure'.$unTicket->getId()]);
    
                editTicket($unTicket->getId(), $adresseD, $adresseA, $date, $heure, $transport);
    
                $unTicket->setAdresseDepart($adresseD);
                $unTicket->setAdresseArrivee($adresseA);
                $unTicket->setDate($date);
                $unTicket->setHeure($heure);
                $leTransport = getTransportById($transport);
                $unTicket->setTransport($leTransport);
            }
            if (isset($_POST['deleteTicket'.$unTicket->getId()])){
                deleteTicket($unTicket->getId());
                header("Location:index.php");
            }
        }    
    }

    if(!empty($mesTicketsValides)){
        foreach($mesTicketsValides as $unTicketValide){
            if (isset($_POST['annuleTicket'.$unTicketValide->getId()])){
                annuleTicket($unTicketValide->getId());
                $demandeur->addTicket($unTicketValide);
                header("Location:index.php");
            }
            if (isset($_POST['termineTicket'.$unTicketValide->getId()])){
                termineTicket($unTicketValide->getId());
                header("Location:index.php");
            }
        }
    }

    if(!empty($historiqueTicket)){
        foreach($historiqueTicket as $unHistoriqueTicket){
            if (isset($_POST['noteEnvoie'.$unHistoriqueTicket->getId()])){
                $idA = $unHistoriqueTicket->getAccompagnateur()->getId();
                $idH = $unHistoriqueTicket->getDemandeur()->getId();
                $idTrajet = $unHistoriqueTicket->getId();
                $note = htmlspecialchars($_POST['noteUtil'.$idA]);
                $commentaire = htmlspecialchars($_POST['commentaireNote'.$idA]);

                addNote($idA, $idH, $idTrajet, $note, $commentaire);

                $laNote = new Note($unHistoriqueTicket, $demandeur, $unHistoriqueTicket->getAccompagnateur(), $note, $commentaire);
                $unHistoriqueTicket->getAccompagnateur()->addNotes($laNote);
            }

            if (isset($_POST['signaler'.$unHistoriqueTicket->getId()])){
                $motif = htmlspecialchars($_POST['motif'.$unHistoriqueTicket->getAccompagnateur()->getId()]);
                $commentaire = htmlspecialchars($_POST['commentaireSignalement'.$unHistoriqueTicket->getAccompagnateur()->getId()]);

                $leSignalement = new Signalement($demandeur, $unHistoriqueTicket->getAccompagnateur(), $motif, $commentaire);

                addSignalement($leSignalement);
            }
        }
    }

    if (isset($_POST['confirmEditProfil'])){
        $nouvNom = htmlspecialchars($_POST['editNom']);
        $nouvPrenom = htmlspecialchars($_POST['editPrenom']);

        editProfil($demandeur->getId(), $nouvNom, $nouvPrenom);

        $demandeur->setNom($nouvNom);
        $demandeur->setPrenom($nouvPrenom);
    }

    if (isset($_POST['editMdp'])){
        $code = "";

        for ($n = 0 ; $n <= 10 ; $n++){
            $code .= random_int(0, 9);
        }

        $output = null;
        $result = null;
        exec("python py/mdpEdit.py ".$demandeur->getMail()." ".$code, $output, $result);

        $_SESSION['code'] = $code;
    }

    if (isset($_POST['editMdpConf'])){
        $mdp = htmlspecialchars($_POST['editMdpNouv']);
        $mdpConf = htmlspecialchars($_POST['editMdpNouvConf']);

        if (checkMdpConf($mdp, $mdpConf) && checkMdpValue($mdp)){
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            editMdp($demandeur->getId(), $mdp);
        }
    }

    $lesTicketsJson = [];

    if (!empty($demandeur->getLesTickets())){
        foreach($demandeur->getLesTickets() as $unTicket){
            $lesTicketsJson[] = [
                "id" => $unTicket->getId(),
                "adresseDepart" => $unTicket->getAdresseDepart(),
                "adresseArrivee" => $unTicket->getAdresseArrivee(),
                "date" => $unTicket->getDate(),
                "transport" => $unTicket->getTransport()->getLibelle(),
                "editForm" => false
            ];
        }
    }

    $jsonTickets = json_encode($lesTicketsJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $mesTicketsValidesJson = [];
    $lAccompagnant = [];

    if (!empty($mesTicketsValides)){
        foreach($mesTicketsValides as $unTicketValide){
            $lesNotesAccompagnant = [];
            if (!empty($unTicketValide->getAccompagnateur()->getNotes())){
                foreach($unTicketValide->getAccompagnateur()->getNotes() as $uneNote){
                    $lesNotesAccompagnant[] = [
                        "demandeur" => $uneNote->getDemandeur()->getNom()." ".$uneNote->getDemandeur()->getPrenom(),
                        "note" => $uneNote->getNote(),
                        "commentaire" => $uneNote->getCommentaire()
                    ];
                }
            }
            $lAccompagnant = [
                "id" => $unTicketValide->getAccompagnateur()->getId(),
                "nom" => $unTicketValide->getAccompagnateur()->getNom(),
                "prenom" => $unTicketValide->getAccompagnateur()->getPrenom(),
                "mail" => $unTicketValide->getAccompagnateur()->getMail(),
                "role" => $unTicketValide->getAccompagnateur()->getStatus()->getLibelle(),
                "note" => $lesNotesAccompagnant,
                "moyenne" => $unTicketValide->getAccompagnateur()->getMoyenne()->getMoyenne()
            ];
            $mesTicketsValidesJson[] = [
                "id" => $unTicketValide->getId(),
                "adresseDepart" => $unTicketValide->getAdresseDepart(),
                "adresseArrivee" => $unTicketValide->getAdresseArrivee(),
                "accompagnant" => $lAccompagnant,
                "date" => $unTicketValide->getDate(),
                "heure" => $unTicketValide->getHeure(),
                "checkProfilAccompagnant" => false,
                "isShowMap" => false
            ];
        }
    }

    $jsonMesTickets = json_encode($mesTicketsValidesJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesTicketsHistoriqueJson = [];

    if (!empty($historiqueTicket)){
        foreach($historiqueTicket as $unHistoriqueTicket){
            $lAccompagnant = [
                "id" => $unHistoriqueTicket->getAccompagnateur()->getId(),
                "nom" => $unHistoriqueTicket->getAccompagnateur()->getNom(),
                "prenom" => $unHistoriqueTicket->getAccompagnateur()->getPrenom(),
                "role" => $unHistoriqueTicket->getAccompagnateur()->getStatus()->getLibelle()
            ];
            $lesTicketsHistoriqueJson[] = [
                "id" => $unHistoriqueTicket->getId(),
                "accompagnant" => $lAccompagnant,
                "transport" => $unHistoriqueTicket->getTransport()->getLibelle(),
                "adresseDepart" => $unHistoriqueTicket->getAdresseDepart(),
                "adresseArrivee" => $unHistoriqueTicket->getAdresseArrivee(),
                "note" => checkNote($unHistoriqueTicket->getDemandeur()->getId(), $unHistoriqueTicket->getAccompagnateur()->getId()),
                "signalement" => checkSignalement($unHistoriqueTicket->getDemandeur()->getId(), $unHistoriqueTicket->getAccompagnateur()->getId()),
                "isShowNote" => false,
                "isShowSignaler" => false
            ];
        }
    }

    $jsonHistorique = json_encode($lesTicketsHistoriqueJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $leDemandeur = [
        "id" => $demandeur->getId(),
        "nom" => $demandeur->getNom(),
        "prenom" => $demandeur->getPrenom(),
        "mail" => $demandeur->getMail(),
        "role" => $demandeur->getStatus()->getLibelle()
    ];

    $jsonDemandeur = json_encode($leDemandeur, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);


    $lesMotifsSignalementJson = [];

    foreach($motifSignalement as $unMotifSignalement){
        $lesMotifsSignalementJson[] = [
            "id" => $unMotifSignalement->getId(),
            "libelle" => $unMotifSignalement->getLibelle()
        ];
    }

    $jsonMotifSignalement = json_encode($lesMotifsSignalementJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    
    $lesTransportJson = [];

    foreach($lesTransport as $unTransport){
        $lesTransportJson[] = [
            "id" => $unTransport->getId(),
            "libelle" => $unTransport->getLibelle()
        ];
    }

    $jsonTransport = json_encode($lesTransportJson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $lesAccompagnantsJSON = [];

    foreach($lesAccompagnants as $unAccompagnant){
        $lesNotesJSON = [];
        if(!empty($unAccompagnant->getNotes())){
            foreach($unAccompagnant->getNotes() as $uneNote){
                $lesNotesJSON[] = [
                    "demandeur" => $uneNote->getDemandeur()->getNom()." ".$uneNote->getDemandeur()->getPrenom(),
                    "note" => $uneNote->getNote(),
                    "commentaire" => $uneNote->getCommentaire()
                ];
            }
        }
        $lesAccompagnantsJSON[] = [
            "nom" => $unAccompagnant->getNom(),
            "prenom" => $unAccompagnant->getPrenom(),
            "permis" => $unAccompagnant->getPermis(),
            "role"  => $unAccompagnant->getStatus()->getLibelle(),
            "notes" => $lesNotesJSON,
            "moyenne" => $unAccompagnant->getMoyenne()->getMoyenne(),
            "isShowNotes" => false
        ];
    }

    $jsonLesAccompagnants = json_encode($lesAccompagnantsJSON, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    if (isset($_POST['logout'])){
        session_destroy();
        header("Location:index.php");
    }
    require "view/view-demandeur.php";