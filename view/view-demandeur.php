<html>
    <head>
        <title id="catPage">Demandeur</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="js/demandeur.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="js/map.js"></script>
        <script src="js/mdpEdit.js"></script>
        <script src="https://unpkg.com/vue@3"></script>
    </head>
    <body>
        <div class="container-fluid min-vh-100 border border-dark rounded contener" id="app">
            <form method=post>
                    <input type=submit v-on:click="clearStorage" name="logout" value="Déconnexion" class="btn btn-primary">
            </form>
            <div class="row">
                <div class="col">
                    <button v-on:click="changeLaPage('ticket')" class="btn btn-primary">Mes tickets</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('profil')" class="btn btn-primary">Profil</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('historique')" class="btn btn-primary">Historique des trajets</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('profilAccompagnant')" class="btn btn-primary">Les accompagnants</button>
                </div>
            </div>
            
            <div class="contener" v-if="laPage == 'ticket'">
                <h1>{{ demandeur.nom }}</h1>
                <h1>Mes Tickets en attente :</h1>
                <p class="alert alert-info" v-if="!lesTickets.length">Aucun ticket</p>
                <div v-for="unTicket in lesTickets" :id="'ticketAffiche' + unTicket.id" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }}</p>
                    </div>
                    <div class="card-body" v-if="!unTicket.editForm">
                        <p class="card-text">{{ unTicket.transport }}  {{ unTicket.date }} {{ unTicket.heure }}</p>
                        <div class="row">
                            <div class="col">
                                <button v-on:click="editTicketForm(unTicket)" class="btn btn-primary">Modifier</button>
                            </div>
                            <div class="col">
                                <form method=post>
                                    <input type=submit :name="'deleteTicket' + unTicket.id" value="Supprimer" class="btn btn-primary">
                                </form>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary" v-on:click="changeIsShowMap(unTicket)">Voir le trajet</button>
                                <div v-if="unTicket.isShowMap" class="modal-mask">
                                    <div class="modal-wrapper">
                                        <div class="modal-container">
                                            <div :class="'map' + unTicket.id" id="map">

                                            </div>
                                            <button v-on:click="changeIsShowMap(unTicket)" class="btn btn-primary">Retour</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-else>
                        <form method=post>
                            <p class="card-text"><input type=text :name="'editDepart' + unTicket.id" :value="unTicket.adresseDepart" placeholder="Entrez la nouvelle adresse de dépar"> - <input type=text :name="'editArrivee' + unTicket.id" :value="unTicket.adresseArrivee" placeholder="Entrez la nouvelle adresse d'arrivée"></p>
                            <select :name="'editTransport' + unTicket.id">
                                <option v-for="unTransport in lesTransports" :value="unTransport.id">{{ unTransport.libelle }}</option>
                            </select><br>
                            <input type=date :name="'editDate' + unTicket.id" :value="unTicket.date">
                            <input type=text :name="'editHeure' + unTicket.id" :value="unTicket.heure" placeholder="Entrez la nouvelle heure"><br>

                            <input type=submit :name="'editTicket' + unTicket.id" value="Modifier" class="btn btn-primary">
                            <button v-on:click="editTicketForm(unTicket)" class="btn btn-primary">Annuler</button>
                        </form>
                    </div>
                </div>

                <h1>Mes tickets validés :</h1>
                <p class="alert alert-info" v-if="!mesTickets.length">Aucun ticket n'a été validé</p>
                <div v-for="unTicketValide in mesTickets" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unTicketValide.adresseDepart }} - {{ unTicketValide.adresseArrivee }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-title">Date : {{ unTicketValide.date }} Heure : {{ unTicketValide.heure }}</p>
                        <p class="card-title">Accompagnant : {{ unTicketValide.accompagnant.nom }} {{ unTicketValide.accompagnant.prenom }}</p>
                        <div class="row">
                            <div class="col">
                                <button v-on:click="checkProfilAccompagnant(unTicketValide)" class="btn btn-primary">Voir profil</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary" v-on:click="changeIsShowMap(unTicketValide)">Voir le trajet</button>
                                <div v-if="unTicketValide.isShowMap" class="modal-mask">
                                    <div class="modal-wrapper">
                                        <div class="modal-container">
                                            <div :class="'map' + unTicketValide.id" id="map">

                                            </div>
                                            <button v-on:click="changeIsShowMap(unTicketValide)" class="btn btn-primary">Retour</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form method=post>
                                <div class="col">
                                    <input type=submit :name="'annuleTicket' + unTicketValide.id" value="Annuler le ticket" class="btn btn-primary">
                                </div>
                                <div class="col">
                                    <input type=submit :name="'termineTicket' + unTicketValide.id" value="Terminer le ticket" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                        <div class="modal-mask" v-if="unTicketValide.checkProfilAccompagnant">
                            <div class="modal-wrap">
                                <div class="modal-container">
                                    <h2>Les notes de {{ unTicketValide.accompagnant.nom }} {{ unTicketValide.accompagnant.prenom }} :</h2>
                                    <p v-if="!unTicketValide.accompagnant.note.length" class="alert alert-info">Aucune note pour {{ unTicketValide.accompagnant.nom }}</p>
                                    <p v-else>Moyenne de {{ unTicketValide.accompagnant.moyenne }} / 10</p>
                                    <div v-for="uneNote in unTicketValide.accompagnant.note" class="card">
                                        <div class="card-header">
                                            {{ uneNote.demandeur }} : {{ uneNote.note }} / 10
                                        </div>
                                        <div class="card-body">
                                            {{ uneNote.commentaire }}
                                        </div>
                                    </div>
                                    <br><button v-on:click="checkProfilAccompagnant(unTicketValide)" class="btn btn-primary">Retour</button>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type=button name="ticketAjout" value="Nouveau ticket" v-on:click="changeIsShowTicketAjout" class="btn btn-primary"><br><br>
                <div class="modal-mask" v-if="isShowTicketAjout">
                    <div class="modal-wrap">
                        <div class="modal-container">
                            <form method=post>
                                Date : <input type=date name="dateTicket"><br><br>
                                Heure : 
                                <select name="heureTicket">
                                    <?php
                                        for ($n = 0 ; $n <= 23 ; $n++){
                                            for ($i = 0 ; $i <= 59 ; $i+=10){
                                                if ($n < 10 && $i < 10){
                                                    $h = "0".$n;
                                                    $m = "0".$i;
                                                }
                                                else if ($n < 10){
                                                    $h = "0".$n;
                                                    $m = $i;
                                                }
                                                else if ($i < 10){
                                                    $h = $n;
                                                    $m = "0".$i;
                                                }
                                                else{
                                                    $h = $n;
                                                    $m = $i;
                                                }
                                                ?>
                                                <option value="<?= $h.":".$m ?>"><?=$h.":".$m?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select><br><br>
                                Transport : 
                                <select name="transportTicket">
                                        <option v-for="unTransport in lesTransports" :value="unTransport.id"> {{ unTransport.libelle }}</option>
                                </select><br><br>
                                Adresse de départ : <input type=text name="adresseDepartTicket" placeholder="Entrez l'adresse de départ"><br><br>
                                Adresse d'arrivée : <input type=text name="adresseArriveeTicket" placeholder="Entrez l'adresse d'arrivée"><br><br>
                                <input type=button name="checkAdresses" value="Voir les adresses sur une carte" onClick="visioCarteAjoutTicket()" class="btn btn-primary">
                                <div id="map"></div><br><br>
                                <div class="row">
                                    <div class="col">
                                        <input type=submit name="ajoutTicket" value="Ajouter" class="btn btn-primary">
                                    </div>
                                    <div class="col">
                                        <button v-on:click="changeIsShowTicketAjout" class="btn btn-primary">Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="contener" v-if="laPage == 'profil'">
                <div v-if="!isShowModifProfil">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title">Mail : {{ demandeur.mail }}</p>
                        </div>
                        <div class="card-body">
                            Nom : <span id="visioNom"> {{ demandeur.nom }}</span><br>
                            Prenom : <span id="visioPrenom"> {{ demandeur.prenom }}</span><br>
                            Status : {{ demandeur.role }}<br><br>
                            <button v-on:click="changeIsShowModifProfil" class="btn btn-primary">Modifier</button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <form method=post>
                        <div class="card">
                            <div class="card-header">
                                Mail : {{ demandeur.mail }}
                            </div>
                            <div class="card-body">
                                Nom : <input type=text name="editNom" :value="demandeur.nom" placeholder="Modifier votre nom"><br><br>
                                Prenom : <input type=text name="editPrenom" :value="demandeur.prenom" placeholder="Modifier votre prenom"><br><br>
                                Status : {{ demandeur.role }}<br><br>
                                <div class="row">
                                    <div class="col">
                                        <input type=submit name="confirmEditProfil" value="Valider" class="btn btn-primary">
                                    </div>
                                    <div class="col">
                                        <button v-on:click="changeIsShowModifProfil" class="btn btn-primary">Retour</button>
                                    </div>
                                    <div class="col">
                                        <input type=submit name="editMdp" value="Modifier le mot de passe" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                if (isset($_POST['editMdp'])){
                    ?>
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <h1>Modification de mot de passe</h1>
                            <form method=post>
                                Code de vérification : <input type=text name="editMdpCode" placeholder="Entrez le code de vérification"> <input type=submit name="editMdpVerif" value="Valider" class="btn btn-primary"><br><br>
                                <input type=submit value="Retour" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                    <?php
                }
                if (isset($_POST['editMdpVerif']) && htmlspecialchars($_POST['editMdpCode']) == $_SESSION['code']){
                    ?>
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <h1>Modification de mot de passe :</h1>
                            <form method=post>
                                <input type=password name="editMdpNouv" placeholder="Entrez votre nouveau mot de passe"> <input type=button name="voirMdp" value="👁️‍🗨️" class="btn btn-primary">
                                <ul>
                                    <li id="taille">12 caractères</li>
                                    <li id="maj">1 majuscule</li>
                                    <li id="min">1 minuscule</li>
                                    <li id="num">1 chiffre</li>
                                </ul>
                                <input type=password name="editMdpNouvConf" placeholder="Veuillez confirmer votre mot de passe"> <input type=button name="voirMdpConf" value="👁️‍🗨️" class="btn btn-primary"><br><br>
                                <input type=submit name="editMdpConf" value="Confirmer" class="btn btn-primary" disabled> <input type=submit value="Retour" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                    <?php
                }
                ?>
            </div>

            <div class="contener" v-if="laPage == 'historique'">
                <p v-if="!lesTicketsHistorique.length" class="alert alert-info">Aucun trajet fini</p>
                <div v-for="unTicketHistorique in lesTicketsHistorique" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unTicketHistorique.adresseDepart }} - {{ unTicketHistorique.adresseArrivee }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Accompagnant : {{ unTicketHistorique.accompagnant.nom }} {{unTicketHistorique.accompagnant.prenom }}</p>
                        <p class="card-text">Transport : {{ unTicketHistorique.transport }}</p>
                        <div class="row">
                            <div class="col" v-if="unTicketHistorique.note">
                                <button v-on:click="changeIsShowNote(unTicketHistorique)" class="btn btn-primary">Noter {{ unTicketHistorique.accompagnant.nom }} {{ unTicketHistorique.accompagnant.prenom }}</button>
                            </div>
                            <div class="col" v-if="unTicketHistorique.signalement">
                                <button v-on:click="changeIsShowSignaler(unTicketHistorique)" class="btn btn-primary">Signaler {{ unTicketHistorique.accompagnant.nom }} {{ unTicketHistorique.accompagnant.prenom }}</button>
                            </div>
                        </div>
                        <div class="modal-mask" v-if="unTicketHistorique.isShowNote">
                            <div class="modal-wrapper">
                                <div class="modal-container">
                                    <h2>Notation de {{ unTicketHistorique.accompagnant.nom }} {{ unTicketHistorique.prenom }}</h2>
                                    <form method=post>
                                        Note :
                                        <select :name="'noteUtil' + unTicketHistorique.id">
                                            <?php
                                            for ($n = 0 ; $n <= 10 ; $n++){
                                                echo "<option value='".$n."'>".$n."</option>";
                                            }
                                            ?>
                                        </select><br>
                                        Votre commentaire : 
                                        <textarea :name="'commentaireNote' + unTicketHistorique.accompagnant.id" cols=50 rows=10 placeholder="Entrez votre commentaire"></textarea><br>
                                        <div class="row">
                                            <div class="col">
                                                <input type=submit :name="'noteEnvoie' + unTicketHistorique.id" value="Noter" class="btn btn-primary">
                                            </div>
                                            <div class="col">
                                                <button v-on:click="changeIsShowNote(unTicketHistorique)" class="btn btn-primary">Retour</button>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-mask" v-if="unTicketHistorique.isShowSignaler">
                            <div class="modal-wrapper">
                                <div class="modal-container">
                                    <h2>Signaler {{ unTicketHistorique.accompagnant.nom }} {{ unTicketHistorique.accompagnant.prenom }}</h2>
                                    <form method=post>
                                        Motif :
                                        <select :name="'motif' + unTicketHistorique.accompagnant.id">
                                            <option v-for="unMotifSignalement in lesMotifsSignalements" :value="unMotifSignalement.id">{{ unMotifSignalement.libelle }}</option>
                                        </select><br>
                                        Commentaire : 
                                        <textarea :name="'commentaireSignalement' + unTicketHistorique.accompagnant.id" placeholder="Entrez votre commentaire" cols=50 rows=10></textarea><br><br>
                                        <div class="row">
                                            <div class="col">
                                                <input type=submit :name="'signaler' + unTicketHistorique.id" value="Signaler" class="btn btn-primary">
                                            </div>
                                            <div class="col">
                                                <button v-on:click="changeIsShowSignaler(unTicketHistorique)" class="btn btn-primary">Retour</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contener" v-if="laPage == 'profilAccompagnant'">
                <h1>Les accompagnants :</h1>
                <p v-if="!lesAccompagnants.length" class="alert alert-info">Aucun accompagnant</p>
                <div v-for="unAccompagnant in lesAccompagnants" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unAccompagnant.nom }} {{ unAccompagnant.prenom }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text" v-if="unAccompagnant.permis">Détient le permis</p>
                        <p class="card-text" v-else>Ne détient pas le permis</p>
                        <div v-if="unAccompagnant.notes.length">
                            <button v-on:click="changeIsShowNotesAccompagnant(unAccompagnant)" class="btn btn-primary">Voir les notes</button>
                            <div v-if="unAccompagnant.isShowNotes" class="modal-mask">
                                <div class="modal-wrapper">
                                    <div class="modal-container">
                                        <h2>Les notes de {{ unAccompagnant.nom }} {{ unAccompagnant.prenom }} :</h2>
                                        <p>Moyenne de {{ unAccompagnant.moyenne }} / 10</p>
                                        <div v-for="uneNote in unAccompagnant.notes" class="card">
                                            <div class="card-header">
                                                <p class="card-title">{{ uneNote.demandeur }} : {{ uneNote.note }} / 10</p>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ uneNote.commentaire }}</p>
                                            </div>
                                        </div>
                                        <button v-on:click="changeIsShowNotesAccompagnant(unAccompagnant)" class="btn btn-primary">Retour</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        //Création d'une vue
        const app = Vue.createApp({
            data(){
                return{
                    demandeur: <?php echo $jsonDemandeur; ?>,
                    lesTickets: <?php echo $jsonTickets; ?>,
                    mesTickets: <?php echo $jsonMesTickets; ?>,
                    lesTransports: <?php echo $jsonTransport; ?>,
                    lesTicketsHistorique: <?php echo $jsonHistorique; ?>,
                    lesMotifsSignalements: <?php echo $jsonMotifSignalement; ?>,
                    lesAccompagnants: <?php echo $jsonLesAccompagnants; ?>,
                    laPage: localStorage.getItem('laPage') || "ticket",
                    isShowModifProfil: false,
                    isShowTicketAjout: false,
                }
            },
            watch: {
                laPage(newPage){
                    localStorage.setItem('laPage', newPage);
                }
            },
            methods: {
                changeLaPage(laPage){
                    this.laPage = laPage;
                },

                changeIsShowModifProfil(){
                    this.isShowModifProfil = !this.isShowModifProfil;
                },

                changeIsShowTicketAjout(){
                    this.isShowTicketAjout = !this.isShowModifProfil;
                },

                changeIsShowNote(unTicket){
                    unTicket.isShowNote = !unTicket.isShowNote;
                },

                changeIsShowSignaler(unTicket){
                    unTicket.isShowSignaler = !unTicket.isShowSignaler;
                },

                changeIsShowNotesAccompagnant(unAccompagnant){
                    unAccompagnant.isShowNotes = !unAccompagnant.isShowNotes;
                },

                editTicketForm(unTicket){
                    unTicket.editForm = !unTicket.editForm;
                },

                checkProfilAccompagnant(unTicket){
                    unTicket.checkProfilAccompagnant = !unTicket.checkProfilAccompagnant;
                },

                changeIsShowMap(unTicket) {
                    unTicket.isShowMap = !unTicket.isShowMap;

                    if (unTicket.isShowMap) {
                        this.$nextTick(() => {
                            map(unTicket.id, unTicket.adresseDepart, unTicket.adresseArrivee);
                        });
                    }
                },

                clearStorage(){
                    localStorage.clear();
                }
            }
        });

        //Montage de la vue sur la div
        app.mount("#app");
    </script>
    </body>
</html>