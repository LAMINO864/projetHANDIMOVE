$(document).ready(function(){
    $("input[name='editMdpNouv']").on('input', function(){
        let checkLength = false;
        let checkMaj = false;
        let checkMin = false;
        let checkNum = false;

        var input = $(this).val();

        if (input.length >= 12){
            checkLength = true;
            $("#taille").css("color", "green");
        }
        else{
            checkLength = false;
            $("#taille").css("color", "red");
        }

        if (input.match(/[A-Z]/)){
            checkMaj = true;
            $("#maj").css("color", "green");
        }
        else{
            checkMaj = false;
            $("#maj").css("color", "red");
        }

        if (input.match(/[a-z]/)){
            checkMin = true;
            $("#min").css("color", "green");
        }
        else{
            checkMin = false;
            $("#min").css("color", "red");
        }

        if (input.match(/[0-9]/)){
            checkNum = true;
            $("#num").css("color", "green");
        }
        else{
            checkNum = false;
            $("#num").css("color", "red");
        }

        if (checkLength && checkMaj && checkMin && checkNum){
            $("input[name='editMdpConf']").attr("disabled", false);
        }
        else{
            $("input[name='editMdpConf']").attr("disabled", true);
        }
    });
    
    $("input[name='editMdpNouvConf']").on('input', function(){
        var input = $("input[name='editMdpNouv']").val();
        var inputConf = $(this).val();

        if (input == inputConf){
            $(this).css("borderColor", "green");
            $("input[name='editMdpNouv']").css("borderColor", "green");
        }
        else{
            $(this).css("borderColor", "red");
            $("input[name='editMdpNouv']").css("borderColor", "red");
        }
    });

    $("input[name='voirMdp']").mousedown(function(){
        $("input[name='editMdpNouv']").attr("type", "text");
    });
    $("input[name='voirMdp']").mouseup(function(){
        $("input[name='editMdpNouv']").attr("type", "password");
    });

    $("input[name='voirMdpConf']").mousedown(function(){
        $("input[name='editMdpNouvConf']").attr("type", "text");
    });
    $("input[name='voirMdpConf']").mouseup(function(){
        $("input[name='editMdpNouvConf']").attr("type", "password");
    });
});