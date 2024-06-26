<?php

function getMotifSignalement(){
    $pdo = connexionPDO();

    $motifSignalement = $pdo->prepare("SELECT * FROM motifsignalement");
    $motifSignalement->execute();

    $ligne = $motifSignalement->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $id = $ligne['id'];
        $libelle = $ligne['libelle'];

        $lesMotifsSignalement[] = new MotifSignalement($id, $libelle);
        
        $ligne = $motifSignalement->fetch(PDO::FETCH_ASSOC);
    }

    return $lesMotifsSignalement;
}

function addSignalement($leSignalement){
    $pdo = connexionPDO();

    $addSignalement = $pdo->prepare("INSERT INTO signalement VALUES (:idUtilSignale, :idUtilVise, :motif, :commentaire)");
    $addSignalement->bindValue(':idUtilSignale', $leSignalement->getUtilSignale()->getId(), PDO::PARAM_INT);
    $addSignalement->bindValue(':idUtilVise', $leSignalement->getUtilVise()->getId(), PDO::PARAM_INT);
    $addSignalement->bindValue(':motif', $leSignalement->getMotif(), PDO::PARAM_INT);
    $addSignalement->bindValue(':commentaire', $leSignalement->getCommentaire(), PDO::PARAM_STR);
    $addSignalement->execute();
}

function checkSignalement($idUtilSignale, $idUtilVise){
    $pdo = connexionPDO();

    $checkSignalement = $pdo->prepare("SELECT * FROM signalement WHERE idUtilSignale = :idUtilSignale AND idUtilVise = :idUtilVise");
    $checkSignalement->bindValue(':idUtilSignale', $idUtilSignale, PDO::PARAM_INT);
    $checkSignalement->bindValue(':idUtilVise', $idUtilVise, PDO::PARAM_INT);
    $checkSignalement->execute();

    if ($checkSignalement->rowcount() == 0){
        return true;
    }
    else{
        return false;
    }
}

function getSignalement(){
    $pdo = connexionPDO();

    $signalement = $pdo->prepare("SELECT UD.id AS `idD`, UD.nom AS `nomD`, UD.prenom AS `prenomD`, UD.mail AS `mailD`, roleD.id AS `idRoleD`, roleD.libelle AS `roleD`,
                                UA.id AS `idA`, UA.nom AS `nomA`, UA.prenom AS `prenomA`, UA.mail AS `mailA`, roleA.id AS `idRoleA`, roleA.libelle AS `roleA`,
                                motifsignalement.libelle AS `motif`, signalement.commentaire AS `commentaire` 
                                FROM signalement
                                INNER JOIN util UD ON signalement.idUtilSignale = UD.id
                                INNER JOIN util UA ON signalement.idUtilVise = UA.id
                                INNER JOIN `role` roleD ON UD.status = roleD.id
                                INNER JOIN `role` roleA ON UA.status = roleA.id
                                INNER JOIN motifsignalement ON signalement.motif = motifsignalement.id
                                ");
    $signalement->execute();

    $ligne = $signalement->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $roleDemandeur = new Role($ligne['idRoleD'], $ligne['roleD']);
        $leDemandeur = new Demandeur($ligne['idD'], $ligne['nomD'], $ligne['prenomD'], null, $ligne['mailD'], $roleDemandeur, null, null);

        $roleAccompagnant = new Role($ligne['idRoleA'], $ligne['roleA']);
        $lAccompagnant = new Accompagnant($ligne['idA'], $ligne['nomA'], $ligne['prenomA'], null, $ligne['mailA'], $roleAccompagnant, null, null, null, null);

        $lesSignalements[] = new Signalement($leDemandeur, $lAccompagnant, $ligne['motif'], $ligne['commentaire']);

        $ligne = $signalement->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($lesSignalements)){
        return $lesSignalements;
    }
    else{
        return null;
    }
}

function deleteSignalementById($idUtilSignale, $idUtilVise){
    $pdo = connexionPDO();

    $delete = $pdo->prepare("DELETE FROM signalement WHERE idUtilSignale = :idUtilSignale AND idUtilVise = :idUtilVise");
    $delete->bindValue(':idUtilSignale', $idUtilSignale, PDO::PARAM_INT);
    $delete->bindValue(':idUtilVise', $idUtilVise, PDO::PARAM_INT);
    $delete->execute();
}