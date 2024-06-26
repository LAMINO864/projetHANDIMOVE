<?php

    function checkPermis($id){
        $pdo = connexionPDO();

        $permisUtil = $pdo->prepare("SELECT detientpermis FROM permisutil WHERE idUtil = :id");
        $permisUtil->bindValue(':id', $id, PDO::PARAM_INT);
        $permisUtil->execute();

        $ligne = $permisUtil->fetch(PDO::FETCH_ASSOC);
        return $ligne['detientpermis'];
    }

    function getAccompagnant(){
        $pdo = connexionPDO();

        $accompagnant = $pdo->prepare("SELECT util.id AS `idUtil`, util.nom, util.prenom, role.id AS `idRole`, role.libelle, permisutil.detientpermis
                                    FROM util
                                    INNER JOIN `role` ON util.status = role.id
                                    INNER JOIN permisutil ON util.id = permisutil.idUtil
                                    WHERE role.id = :id
                                    ");
        $accompagnant->bindValue(':id', 2, PDO::PARAM_INT);
        $accompagnant->execute();

        $ligne = $accompagnant->fetch(PDO::FETCH_ASSOC);

        while($ligne){
            $id = $ligne['idUtil'];
            $nom = $ligne['nom'];
            $prenom = $ligne['prenom'];
            $idRole = $ligne['idRole'];
            $role = $ligne['libelle'];
            $leRole = new Role($idRole, $role);
            $permis = $ligne['detientpermis'];

            $lesNotes = getNoteByIdUtil($id);

            $moyenne = getNoteUtil($id);

            $lesAccompagnant[] = new Accompagnant($id, $nom, $prenom, null, null, $leRole, $permis, $lesNotes, null, $moyenne);

            $ligne = $accompagnant->fetch(PDO::FETCH_ASSOC);
        }

        if (!empty($lesAccompagnant)){
            return $lesAccompagnant;
        }
        else{
            return null;
        }
    }

