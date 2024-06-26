$(document).ready(function() {
    if (localStorage.getItem('laPage') == "connexion" || !localStorage.getItem('laPage')){
        $("#inscription").hide();
        $("#connexion").show();
        $("title").text("Connexion");
        $("#title").text("Connexion");
    }
    else if (localStorage.getItem('laPage') == "inscription"){
        $("#connexion").hide();
        $("#inscription").show();
        $("title").text("Inscription");
        $("#title").text("Inscription");
    }

    $("input[name='connexionPage']").click(function(){
        $("#inscription").hide();
        $("#connexion").show();
        $("title").text("Connexion");
        $("#title").text("Connexion");
        localStorage.setItem("laPage", "connexion");
    }); 
    
    $("input[name='inscriptionPage']").click(function(){
        $("#connexion").hide();
        $("#inscription").show();
        $("title").text("Inscription");
        $("#title").text("Inscription");
        localStorage.setItem("laPage", "inscription");
    });

    $("input[name='connexion']").click(function(){
        localStorage.clear();
    });

    $("select[name='statusUtil']").change(function(){
        if ($(this).val() == 2){
            $("#formAccompagnant").css("display", "block");
        }
        else{
            $("#formAccompagnant").css("display", "none");
        }
    });
});



function mdpCheck(){
    var input = document.getElementById("mdpUtil").value;

    var check_maj = false;
    var check_num = false;
    var check_lenght = false;


    input = input.trim();
    document.getElementById("mdpUtil").value = input;

    if (input.match(/[A-Z]/)){
        document.getElementById("check1").style.color = "green";
        check_maj = true;
    }
    else{
        document.getElementById("check1").style.color = "red";
        check_maj = false;
    }
    if (input.match(/[0-9]/)){
        document.getElementById("check2").style.color = "green";
        check_num = true;
    }
    else{
        document.getElementById("check2").style.color = "red";
        check_num = false;
    }
    if (input.length >= 8){
        document.getElementById("check3").style.color = "green";
        check_lenght = true;
    }
    else{
        document.getElementById("check3").style.color = "red";
        check_lenght = false;
    }
}

function mdpConfirmCheck(){
    var mdp = document.getElementById("mdpUtil").value;
    var mdpConf = document.getElementById("confMdpUtil").value;

    if (mdp == mdpConf){
        document.getElementById("mdpUtil").style.borderColor = "green";
        document.getElementById("confMdpUtil").style.borderColor = "green";
    }
    else{
        document.getElementById("mdpUtil").style.borderColor = "red";
        document.getElementById("confMdpUtil").style.borderColor = "red";
    }
}

function checkValues(){
    var type = document.getElementsByName("statusUtil")[0].value;
    var mdp = document.getElementById("mdpUtil").value;
    var mail = document.getElementsByName("mailUtil")[0].value;
    var nom = document.getElementsByName("nomUtil")[0].value;
    var prenom = document.getElementsByName("prenomUtil")[0].value;
    
    if ((type == 1 || type == 2) && mdp.length > 0 && mail.length > 0 && nom.length > 0 && prenom.length > 0) {
        document.getElementById("registerButton").disabled = false;
    }
    else {
        document.getElementById("registerButton").disabled = true;
    }
}