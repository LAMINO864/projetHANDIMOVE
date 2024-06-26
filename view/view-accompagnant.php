<html>
    <head>
        <title id="catPage">Accompagnateur</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="js/map.js"></script>
        <script src="js/mdpEdit.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://unpkg.com/vue@3"></script>
    </head>
    <body>
        <div class="container-fluid min-vh-100 border border-dark rounded contener" id="app"><br><br>
            <form method=post>
                <input type=submit v-on:click="clearStorage" name="logout" value="Déconnexion" class="btn btn-primary">
            </form>
            <h1>{{ accompagnant.mail }}</h1>

            <div class="row">
                <div class="col">
                    <button v-on:click="changeLaPage('ticket')" class="btn btn-primary">Ticket</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('profile')" class="btn btn-primary">Profil</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('historiqueTrajet')" class="btn btn-primary">Historique des trajets</button>
                </div>
            </div>

            <div v-if="laPage == 'ticket'" class="container border border-dark rounded"><br><br>
                <select v-on:change="changeTicket">
                        <option selected>Les tickets disponibles</option>
                        <option>Mes tickets</option>
                </select>
                <div id="listTicket" v-if="!mesTicketsShow">
                    <h1>Liste des tickets disponibles :</h1>
                    <button v-on:click="changeIsShowRechercheTicket" class="btn btn-primary">Recherche</button>
                    <form method=post v-if="isShowRechercheTicket">
                        Ville de départ : <input type=text v-model="villeDepart" placeholder="Entrez votre ville de départ"><br>
                        Ville de d'arrivée : <input type=text v-model="villeArrivee" placeholder="Entrez votre ville d'arrivée"><br>
                        Date : <input type=date v-model="date"><br>
                        Transport :
                        <select v-model="transport">
                            <option value="all">Tout</option>
                            <option v-for="unTransport in lesTransports" :value="unTransport.libelle">{{ unTransport.libelle }}</option>
                        </select>
                    </form>
                
                    <p v-if="!filtreTickets.length" class="alert alert-info">Aucun résultat à votre recherche</p>
                    <div v-for="unTicket in filtreTickets" class="card">
                        <div class="card-header">
                            <p class="card-title">{{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }}</h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Date :  {{ unTicket.date }} Heure : {{ unTicket.heure }}</p>
                            <p class="card-text">Mode de transport : {{ unTicket.transport }}</p>
                            <p class="card-text">Demandeur : {{ unTicket.demandeur }}</p>
                            <div class="row">
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
                                <div class="col">
                                    <form method=post>
                                        <input type="submit" :name="'reserverTicket'+ unTicket.id" value="Réserver" class="btn btn-primary">
                                    </form>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>

                <div id="listMesTicket" v-else>
                    <h1>Mes tickets :</h1>
                    <button v-on:click="changeIsShowRechercheMesTicket" class="btn btn-primary">Recherche</button>
                    <form method=post v-if="isShowRechercheMesTicket">
                        Ville de départ : <input type=text v-model="villeDepartMesTickets" placeholder="Entrez votre ville de départ"><br>
                        Ville de d'arrivée : <input type=text v-model="villeArriveeMesTickets" placeholder="Entrez votre ville d'arrivée"><br>
                        Date : <input type=date v-model="dateMesTickets"><br>
                        Transport :
                        <select v-model="transportMesTickets">
                            <option value="all">Tout</option>
                            <option v-for="unTransport in lesTransports" :value="unTransport.libelle">{{ unTransport.libelle }}</option>
                        </select>
                    </form>
                    <p v-if="!filtreMesTickets.length" class="alert alert-info">Aucun ticket n'est valide</p>
                    <div v-for="unTicket in filtreMesTickets" class="card">
                        <div class="card-header">
                            <h2 class="card-title">{{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }}</h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Date : {{ unTicket.date }} Heure : {{ unTicket.heure }}</p>
                            <p class="card-text">Mode de transport : {{ unTicket.transport }}</p>
                            <p class="card-text"> {{ unTicket.demandeur }}</p>
                            <form method=post>
                                <input type="submit" :name="'annuleTicket' + unTicket.id" value="Annuler" class="btn btn-primary">    
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="laPage == 'profile'" class="container border border-dark rounded">
                <div v-if="!isShowModifProfil">
                    Nom : {{ accompagnant.nom }} <br>
                    Prenom : {{ accompagnant.prenom }} <br>
                    Mail : {{ accompagnant.mail }} <br>
                    Role : {{ accompagnant.role }} <br>
                    <div class="row">
                        <div class="col">
                            <button v-on:click="changeIsShowModifProfil" class="btn btn-primary">Modifier</button>
                        </div>
                        <div class="col">
                            <button v-on:click="changeIsShowNotes" class="btn btn-primary">Mes notes</button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <form method=post>
                        Nom : <input type=text name="modifNom" :value="accompagnant.nom" placeholder="Entrez votre nouveau nom"> <br>
                        Prenom : <input type=text name="modifPrenom" :value="accompagnant.prenom" placeholder="Entrez votre nouveau prénom"> <br>
                        Mail : {{ accompagnant.mail }} <br>
                        Role : {{ accompagnant.role }} <br>
                        <div class="row">
                            <div class="col">
                                <input type=submit name="modifProfil" value="Modifier" class="btn btn-primary">
                            </div>
                            <div class="col">
                                <button v-on:click="changeIsShowModifProfil" class="btn btn-primary">Retour</button>
                            </div>
                            <div class="col">
                                <input type=submit name="editMdp" value="Modifier le mot de passe" class="btn btn-primary">
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
                
                <div v-if="isShowMesNotes" class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <h2>Mes notes :</h2>
                            Moyenne : {{ accompagnant.moyenne }} / 10<br>
                            Trajet : 
                            <select v-model="searchNoteTrajet">
                                <option value="all">Tout</option>
                                <option v-for="uneNote in accompagnant.notes" :value="uneNote.trajet + ' ' + uneNote.demandeur">{{ uneNote.trajet }} | {{ uneNote.demandeur }}</option>
                            </select>
                            <p v-if="!accompagnant.notes.length" class="alert alert-info">Aucune note</p>
                            <div v-for="uneNote in filtreMesNotes" class="card">
                                <div class="card-header">
                                    <p class="card-title">{{ uneNote.demandeur }} : {{ uneNote.note }} / 10</p>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Trajet : {{ uneNote.trajet }}</p>
                                    <p class="card-text">Commentaire : <br>{{ uneNote.commentaire }}</p>
                                </div>
                            </div><br>
                            <button v-on:click="changeIsShowNotes" class="btn btn-primary">Retour</button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="laPage == 'historiqueTrajet'" class="container border border-dark rounded">
                <p v-if="!historiqueTicket.length" class="alert alert-info">Aucun ticket fini</p>
                <div v-for="unTicket in historiqueTicket" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Demandeur : {{ unTicket.demandeur.nom }} {{ unTicket.demandeur.prenom }}</p>
                        <p class="card-text">Transport : {{ unTicket.transport }}</p>
                        <div v-if="unTicket.laNote != ''">
                            <button v-on:click="changeIsShowLaNote(unTicket)" class="btn btn-primary">Voir la note</button>
                            <div v-if="unTicket.isShowNote" class="modal-mask">
                                <div class="modal-wrapper">
                                    <div class="modal-container">
                                        <div class="card">
                                            <div class="card-header">
                                                <p class="card-title">La note pour {{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }} par {{ unTicket.laNote.demandeur }}</p>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"> Note : {{ unTicket.laNote.note }}</p>
                                                <p class="card-text"> Commentaire : {{ unTicket.laNote.commentaire }}</p>
                                            </div>
                                        </div>
                                        <button v-on:click="changeIsShowLaNote(unTicket)" class="btn btn-primary">Retour</button>
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
                    lesTickets: <?php echo $jsonTickets; ?>,
                    mesTickets: <?php echo $jsonMesTickets; ?>,
                    historiqueTicket: <?php echo $jsonHistorique; ?>,
                    accompagnant: <?php echo $jsonAccompagnant; ?>,
                    lesTransports: <?php echo $jsonTransport; ?>,
                    isShowRechercheTicket: false,
                    isShowRechercheMesTicket: false,
                    isShowModifProfil: false,
                    isShowMesNotes: false,
                    mesTicketsShow: false,
                    villeDepart: "",
                    villeArrivee: "",
                    date: "",
                    transport: "all",
                    laPage: localStorage.getItem('laPage') || "ticket",
                    villeDepartMesTickets: "",
                    villeArriveeMesTickets: "",
                    dateMesTickets: "",
                    transportMesTickets: "all",
                    villeDepartHistorique: "",
                    villeArriveeHistorique: "",
                    dateHistorique: "",
                    transportHistorique: "all",
                    searchNoteTrajet: "all"
                }
            },
            watch: {
                laPage(newPage){
                    localStorage.setItem('laPage', newPage);
                }
            },
            computed: {
                filtreTickets(){
                    let recherche = this.lesTickets.filter(unTicket => unTicket.adresseDepart.toLowerCase().includes(this.villeDepart.toLowerCase()));

                    recherche = recherche.filter(unTicket => unTicket.adresseArrivee.toLowerCase().includes(this.villeArrivee.toLowerCase()));

                    recherche = recherche.filter(unTicket => unTicket.date.includes(this.date));

                    if (this.transport != "all"){
                        recherche = recherche.filter(unTicket => unTicket.transport.toLowerCase().includes(this.transport.toLowerCase()));
                    }

                    return recherche;
                },

                filtreMesTickets(){
                    let recherche = this.mesTickets.filter(unTicket => unTicket.adresseDepart.toLowerCase().includes(this.villeDepartMesTickets.toLowerCase()));

                    recherche = recherche.filter(unTicket => unTicket.adresseArrivee.toLowerCase().includes(this.villeArriveeMesTickets.toLowerCase()));

                    recherche = recherche.filter(unTicket => unTicket.date.includes(this.dateMesTickets));

                    if (this.transportMesTickets != "all"){
                        recherche = recherche.filter(unTicket => unTicket.transport.toLowerCase().includes(this.transportMesTickets.toLowerCase()));
                    }

                    return recherche;
                },

                filtreMesNotes(){
                    let recherche = this.accompagnant.notes;

                    if(this.searchNoteTrajet != "all"){
                        recherche = recherche.filter(uneNote => {
                            const trajet = uneNote.trajet + " " + uneNote.demandeur;
                            return trajet.toLowerCase().includes(this.searchNoteTrajet.toLowerCase());
                        });
                    }

                    return recherche;
                }
            },
            methods: {
                changeIsShowRechercheTicket(){
                    this.isShowRechercheTicket = !this.isShowRechercheTicket;
                },

                changeIsShowRechercheMesTicket(){
                    this.isShowRechercheMesTicket = !this.isShowRechercheMesTicket;
                },

                changeIsShowModifProfil(){
                    this.isShowModifProfil = !this.isShowModifProfil;
                },

                changeIsShowNotes(){
                    this.isShowMesNotes = !this.isShowMesNotes;
                },

                changeIsShowLaNote(unTicket){
                    unTicket.isShowNote = !unTicket.isShowNote;
                },

                changeLaPage(laPage){
                    this.laPage = laPage;
                },

                changeTicket(){
                    this.mesTicketsShow = !this.mesTicketsShow;
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