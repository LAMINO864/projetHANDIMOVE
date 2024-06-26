<?php
    $lifeTime = 3600*5;
    session_set_cookie_params($lifeTime);
    ini_set('session.gc_maxlifetime', $lifeTime);
    session_start();
    session_regenerate_id(true);
    setcookie(session_name(), session_id(), time()+$lifeTime);
    
    require "model/model-connexionBDD.php";
    require "class/class-motifSignalement.php";
    require "class/class-historique.php";
    require "class/class-note.php";
    require "class/class-noteUtil.php";
    require "class/class-role.php";
    require "class/class-signalement.php";
    require "class/class-ticket.php";
    require "class/class-transport.php";
    require "class/class-util.php";
    require "class/class-accompagnant.php";
    require "class/class-demandeur.php";
    require "model/model-connexion.php";
    require "model/model-role.php";
    require "model/model-note.php";
    require "model/model-noteUtil.php";
    require "model/model-signalement.php";
    require "model/model-transport.php";
    require "model/model-ticket.php";
    require "model/model-historique.php";
    require "model/model-util.php";
    require "model/model-demandeur.php";
    require "model/model-accompagnant.php";

    


    if (!empty($_SESSION['mail'])){
        if ($_SESSION['role'] == 1) {
            include "controller/demandeur.php";
        }
        else if ($_SESSION['role'] == 3){
            include "controller/admin.php";
        }
        else {
            include "controller/accompagnant.php";
        }
    }
    else{
        include "controller/connexion.php";
    }