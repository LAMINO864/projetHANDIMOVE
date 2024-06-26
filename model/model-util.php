<?php

function getUtilByMail($mail){
    $pdo = connexionPDO();

    $util = $pdo->prepare("SELECT * FROM util WHERE mail = :mail");
    $util->bindValue(':mail', $mail, PDO::PARAM_STR);
    $util->execute();

    $result = $util->fetch(PDO::FETCH_ASSOC);

    $id = $result["id"];
    $nom = $result['nom'];
    $prenom = $result['prenom'];
    $photoProfile = $result['photoProfil'];
    $mail = $result['mail'];
    $statusCheck = $result['status'];
    $role = getStatusById($statusCheck);
    $note = getNoteByIdUtil($id);
    $permis = checkPermis($id);
    $moyenne = getNoteUtil($id);

    $accompagnant = new Accompagnant($id, $nom, $prenom, $photoProfile, $mail, $role, $permis, $note, null, $moyenne);

    return $accompagnant;
}

function editProfil($id, $nom, $prenom){
    $pdo = connexionPDO();

    $profil = $pdo->prepare("UPDATE util SET nom = :nom, prenom = :prenom WHERE id = :id");
    $profil->bindValue(':id', $id, PDO::PARAM_INT);
    $profil->bindValue(':nom', $nom, PDO::PARAM_STR);
    $profil->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $profil->execute();
}

function deleteUtilById($id){
    $pdo = connexionPDO();

    $delete = $pdo->prepare("DELETE FROM util WHERE id = :id");
    $delete->bindValue(':id', $id, PDO::PARAM_INT);
    $delete->execute();
}

function getUtil(){
    $pdo = connexionPDO();

    $getUtil = $pdo->prepare("SELECT util.id AS `idU`, util.nom, util.prenom, util.mail,
                            role.id AS `idR`, role.libelle AS `libelleR`
                            FROM util
                            INNER JOIN `role` ON util.status = role.id
                            ");
    $getUtil->execute();

    $ligne = $getUtil->fetch(PDO::FETCH_ASSOC);

    while($ligne){
        $id = $ligne['idU'];
        $nom = $ligne['nom'];
        $prenom = $ligne['prenom'];
        $mail = $ligne['mail'];
        
        $idRole = $ligne['idR'];
        $libelleRole = $ligne['libelleR'];

        $leRole = new Role($idRole, $libelleRole);

        $lesUtilisateurs[] = new Util($id, $nom, $prenom, null, $mail, $leRole);

        $ligne = $getUtil->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($lesUtilisateurs)){
        return $lesUtilisateurs;
    }
    else{
        return null;
    }
}

function editMdp($id, $mdp){
    $pdo = connexionPDO();

    $editMdp = $pdo->prepare("UPDATE util SET mdp = :mdp WHERE id = :id");
    $editMdp->bindValue(':mdp', $mdp, PDO::PARAM_STR);
    $editMdp->bindValue(':id', $id, PDO::PARAM_INT);
    $editMdp->execute();
}