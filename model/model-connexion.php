<?php
    function checkMail($mail){
        $pdo = connexionPDO();

        $mailCheck = $pdo->prepare("SELECT * FROM util WHERE mail = :mail");
        $mailCheck->bindValue(':mail', $mail, PDO::PARAM_STR);
        $mailCheck->execute();
        $mailResult = $mailCheck->rowcount();

        if ($mailResult == 0){
            return true;
        }
        else {
            return false;
        }
    }

    function checkMdpValue($mdp){
        $checkChiffre = false;
        $checkMaj = false;
        $checkNbCar = false;

        if (strlen($mdp) >= 8){
            $checkNbCar = true;
        }
        for ($n = 0 ; $n < strlen($mdp) ; $n++){
            if (is_numeric($mdp[$n])){
                $checkChiffre = true;
            }
            else if (ctype_upper($mdp[$n])){
                $checkMaj = true;
            }
        }

        if ($checkChiffre == true && $checkMaj == true && $checkNbCar == true){
            return true;
        }
        else{
            return false;
        }
    }

    function checkMdpConf($mdp, $mdpConf){
        if ($mdp == $mdpConf){
            return true;
        }
        else {
            return false;
        }
    }

    function registerUser($nom, $prenom, $mail, $type, $mdp){
        $pdo = connexionPDO();

        $register = $pdo->prepare("INSERT INTO util VALUES (:id, :nom, :prenom, :pp, :mail, :statu, :mdp)");
        $register->bindValue(':id', 'id', PDO::PARAM_INT);
        $register->bindValue(':nom', $nom, PDO::PARAM_STR);
        $register->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $register->bindValue(':pp', ':pp', PDO::PARAM_STR);
        $register->bindValue(':mail', $mail, PDO::PARAM_STR);
        $register->bindValue(':statu', $type, PDO::PARAM_INT);
        $register->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $register->execute();
    }

    function registerPermisUser($mail, $permis){
        $pdo = connexionPDO();

        $idUser = $pdo->prepare("SELECT id FROM util WHERE mail = :mail");
        $idUser->bindValue(':mail', $mail, PDO::PARAM_STR);
        $idUser->execute();

        $id = $idUser->fetch();

        $registerPermis = $pdo->prepare("INSERT INTO permisutil VALUES (:id, :permis)");
        $registerPermis->bindValue(':id', $id['id'], PDO::PARAM_INT);
        $registerPermis->bindValue(':permis', $permis, PDO::PARAM_INT);
        $registerPermis->execute();
    }

    function connexion($mail, $mdp){
        $utilExiste = false;

        $pdo = connexionPDO();

        $req = $pdo->prepare("SELECT * FROM util WHERE mail = ?");
        $req->execute(array($mail));
        $utilExiste = $req->fetchall(PDO::FETCH_OBJ);

        $nb_result=count($utilExiste);

        if($utilExiste){
            $mdp_bdd = $utilExiste[0]->mdp;
            if (password_verify($mdp, $mdp_bdd)){
                $_SESSION['mail'] = $mail;
                $_SESSION['role'] = $utilExiste[0]->status;
                header("Location:index.php");
                $error = "Connexion réussie";
            }
            else{
                $error = "Erreur de connexion, mot de passe ou identifiant incorrect.";
            }
        }
        else{
            $error = "Erreur de connexion, mot de passe ou identifiant incorrect.";
        }
        return $error;
    }