<html>
    <head>
        <title id="catPage">Connexion</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="js/connexion.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="row">
                <div class="col">
                    <p>HANDIMOVE</p>
                </div>
                <div class="col">
                    <p id="title"></p>
                </div>
            </div>
        </div><br><br>
        <div class="contener" id="inscription">
            <input type=button  class="btn btn-primary" name="connexionPage" value="Connexion"> 
            <input type=button class="btn btn-primary" name="inscriptionPage" value="Inscription">
            <h1>Inscription</h1>
            <form method=post enctype="multipart/form-data">
                Nom : <input type=text name="nomUtil" placeholder="Entrez votre nom" onInput="checkValues()" required>
                Prenom : <input type=text name="prenomUtil" placeholder="Entrez votre prenom" onInput="checkValues()" required>
                <br><br>
                Mail : <input type=text name="mailUtil" placeholder="Entrez votre mail" onInput="checkValues()" required>
                <br><br>
                Vous êtes : <select name="statusUtil" onInput="checkValues()">
                    <option value="0" selected>--Sélectionnez un type d'utilisateur--
                    <option value="1">Une personne à mobiliter réduite
                    <option value="2">Un accompagnant
                </select>
                <div id="formAccompagnant">
                    Possèdez vous le permis ? : Oui <input type=radio name="permisUtil" value="1"> Non <input type=radio name="permisUtil" value="0"><br><br>
                </div><br><br>
                Mot de passe : <input id="mdpUtil" type="password" name="mdpUtil" placeholder="Entrez votre mot de passe" onInput="mdpCheck(), mdpConfirmCheck(), checkValues()" required><br><br>
                <ul id="mdp">
                    <li id="check1">1 majuscule</li>
                    <li id="check2">1 chiffre</li>
                    <li id="check3">8 caractères</li>
                </ul>
                Confirmation de mot de passe : <input id="confMdpUtil" type="password" name="confMdpUtil" placeholder="Veuillez confirmer votre mot de passe" onInput="mdpConfirmCheck()"><br><br>

                <input type=submit name="inscription" value="S'inscrire" id="registerButton" class=" btn btn-primary" disabled>
            </form>
            <?php if(!empty($errorRegister)){ ?> <div class="alert alert-danger"><?= $errorRegister ?></div> <?php } ?>
        </div>


        <div class="contener" id="connexion">
            <input type=button class="btn btn-primary" name="connexionPage" value="Connexion" onClick="connexion()"> 
            <input type=button class="btn btn-primary" name="inscriptionPage" value="Inscription" onClick="inscription()">
            <h1>Connexion</h1>
            
            <form method=post>
                Mail : <input type=text name="mailCo" placeholder="Entrez votre mail"><br><br>
                Mot de passe : <input type=password name="passwordCo" placeholder="Entrez votre mot de passe"><br><br>
                <input type=submit name="connexion" value="Connexion" class="btn btn-primary">
            </form>
            <?php if(!empty($errorLogin)){ ?> <div class="alert alert-danger"><?= $errorLogin ?></div> <?php } ?> 
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>