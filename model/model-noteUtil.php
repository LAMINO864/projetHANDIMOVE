<?php

function getNoteUtil($id){
    $pdo = connexionPDO();

    $noteUtil = $pdo->prepare("SELECT note FROM noteutil WHERE idUtil = :idUtil");
    $noteUtil->bindValue(':idUtil', $id, PDO::PARAM_INT);
    $noteUtil->execute();

    $result = $noteUtil->fetch();

    $laMoyenne = new NoteUtil($id, $result['note'], null);

    if (!empty($laMoyenne)){
        return $laMoyenne;
    }
    else {
        return null;
    }
}