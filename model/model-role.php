<?php
    function getStatusById($id){
        $pdo = connexionPDO();

        $statusSearch = $pdo->prepare("SELECT * FROM role WHERE id = :id");
        $statusSearch->bindValue(':id', $id, PDO::PARAM_INT);
        $statusSearch->execute();

        $resultStatus = $statusSearch->fetch(PDO::FETCH_ASSOC);

        $id = $resultStatus['id'];
        $libelle = $resultStatus['libelle'];

        $role = new Role($id, $libelle);

        return $role;
    }
