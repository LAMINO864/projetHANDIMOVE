<?php

    if (isset($_POST['inscription'])){
        $nom = htmlspecialchars($_POST['nomUtil']);
        $prenom = htmlspecialchars($_POST['prenomUtil']);
        $mail = htmlspecialchars($_POST['mailUtil']);
        $type = htmlspecialchars($_POST['statusUtil']);
        if ($type == "2"){
            $permis = htmlspecialchars($_POST['permisUtil']);
        }
        $mdp = htmlspecialchars($_POST['mdpUtil']);
        $mdpConf = htmlspecialchars($_POST['confMdpUtil']);

        if (checkMail($mail)){
            if(checkMdpValue($mdp)){
                if(checkMdpConf($mdp, $mdpConf)){
                    $password = password_hash($mdp, PASSWORD_DEFAULT);
                    registerUser($nom, $prenom, $mail, $type, $password);
                    if ($type == 2){
                        registerPermisUser($mail, $permis);
                    }
                    /*$target_dir = "image/";
                    $target_file = $target_dir . basename($_FILES["ppUtil"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    move_uploaded_file($_FILES["ppUtil"]["name"], $target_file);*/
                }
                else{
                    $errorRegister = "Les mots de passes ne sont pas identiques";
                }
            }
            else{
                $errorRegister = "Le mot de passe doit faire au moins 8 caractères, contenir une majuscule ainsi que d'avoir un chiffre";
            }
        }
        else{
            $errorRegister = "Le mail est déjà associé à un autre compte";
        }
    }

    if (isset($_POST['connexion'])) {
        $mail = htmlspecialchars($_POST['mailCo']);
        $mdp = htmlspecialchars($_POST['passwordCo']);

        $errorLogin = connexion($mail, $mdp);
    }
    
    require "view/view-connexion.php";