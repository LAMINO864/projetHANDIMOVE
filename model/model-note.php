<?php
    function addNote($idA, $idH, $idTrajet, $note, $commentaire){
        $pdo = connexionPDO();

        $addNote = $pdo->prepare("INSERT INTO `notation` VALUES (:idTrajet, :idH, :idA, :note, :commentaire)");
        $addNote->bindValue(':idTrajet', $idTrajet, PDO::PARAM_INT);
        $addNote->bindValue(':idH', $idH, PDO::PARAM_INT);
        $addNote->bindValue(':idA', $idA, PDO::PARAM_INT);
        $addNote->bindValue(':note', $note, PDO::PARAM_INT);
        $addNote->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $addNote->execute();
    }

    function checkNote($idH, $idA){
        $pdo = connexionPDO();

        $checkNote = $pdo->prepare("SELECT idA FROM notation WHERE idA = :idA AND idH = :idH");
        $checkNote->bindValue(':idH', $idH, PDO::PARAM_INT);
        $checkNote->bindValue(':idA', $idA, PDO::PARAM_INT);
        $checkNote->execute();

        if ($checkNote->rowcount() == 0){
            return true;
        }
        else{
            return false;
        }
    }

    
    function getNoteByIdUtil($id){
        $pdo = connexionPDO();

        $noteUtil = $pdo->prepare("SELECT util.nom AS `nom`, util.prenom AS `prenom`,
                                notation.note AS `note`, notation.commentaire AS `commentaire`,
                                historiquetrajet.adresseD AS `adresseD`, historiquetrajet.adresseA AS `adresseA`, transport.libelle AS `transport`
                                FROM notation 
                                INNER JOIN util ON notation.idH = util.id
                                INNER JOIN historiquetrajet ON notation.idTrajet = historiquetrajet.id
                                INNER JOIN transport ON historiquetrajet.transport = transport.id
                                WHERE notation.idA = :idUtil");
        $noteUtil->bindValue(":idUtil", $id, PDO::PARAM_INT);
        $noteUtil->execute();

        $ligne = $noteUtil->fetch(PDO::FETCH_ASSOC);

        while($ligne){
            $nom = $ligne['nom'];
            $prenom = $ligne['prenom'];
            $adresseD = $ligne['adresseD'];
            $adresseA = $ligne['adresseA'];
            $transport = $ligne['transport'];
            $trajet = new Historique(null, null, null, $adresseD, $adresseA, $transport, null);
            $accompagnant = new Accompagnant(null, $nom, $prenom, null, null, null, null, null, null, null);
            $note = $ligne['note'];
            $commentaire = $ligne['commentaire'];

            $notes[] = new Note($trajet, $accompagnant, null, $note, $commentaire);
            $ligne = $noteUtil->fetch(PDO::FETCH_ASSOC);
        }

        if (!empty($notes)){
            return $notes;
        }
        else{
            return null;
        }

    }

    function getNoteByIdTrajet($id){
        $pdo = connexionPDO();

        $noteTrajet = $pdo->prepare("SELECT demandeur.nom AS `nomDemandeur`, demandeur.prenom AS `prenomDemandeur`, role.id AS `idRoleD`, role.libelle AS `roleDemandeur`,
                                    accompagnant.nom AS `nomAccompagnant`, accompagnant.prenom AS `prenomAccompagnant`, r.id AS `idRoleA`, r.libelle AS `roleAccompagnant`,
                                    notation.note AS `note`, notation.commentaire AS `commentaire`
                                    FROM notation
                                    INNER JOIN util demandeur ON notation.idH = demandeur.id
                                    INNER JOIN util accompagnant ON notation.idA = accompagnant.id
                                    INNER JOIN `role` ON demandeur.status = role.id
                                    INNER JOIN `role` r ON accompagnant.status = r.id
                                    WHERE idTrajet = :id
                                    ");
        $noteTrajet->bindValue(':id', $id, PDO::PARAM_INT);
        $noteTrajet->execute();

        $result = $noteTrajet->fetch();

        if ($noteTrajet->rowcount() == 1){
            $nomD = $result['nomDemandeur'];
            $prenomD = $result['prenomDemandeur'];
            $roleD = new Role($result['idRoleD'], $result['roleDemandeur']);

            $leDemandeur = new Demandeur(null, $nomD, $prenomD, null, null, $roleD, null, null);

            $nomA = $result['nomAccompagnant'];
            $prenomA = $result['prenomAccompagnant'];
            $roleA = new Role($result['idRoleA'], $result['roleAccompagnant']);

            $lAccompagnant = new Accompagnant(null, $nomA, $prenomA, null, null, $roleA, null, null, null, null);

            $note = $result['note'];
            $commentaire = $result['commentaire'];

            $leTicketHistorique = new Note($id, $leDemandeur, $lAccompagnant, $note, $commentaire);

            return $leTicketHistorique;
        }
        else{
            return null;
        }
    }
