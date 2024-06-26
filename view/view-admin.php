<html>
    <head>
        <title id="catPage">Administration</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/connexion.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://unpkg.com/vue@3"></script>
    </head>
    <body>
        <div class="container-fluid min-vh-100 border border-dark rounded contener" id="app"><br><br>
            <form method=post>
                <input type=submit v-on:click="clearStorage" name="logout" value="Déconnexion" class="btn btn-primary">
            </form>
            <div class="row">
                <div class="col">
                    <button v-on:click="changeLaPage('signalement')" class="btn btn-primary">Signalements</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('ticket')" class="btn btn-primary">Tickets</button>
                </div>
                <div class="col">
                    <button v-on:click="changeLaPage('utilisateur')" class="btn btn-primary">Utilisateurs</button>
                </div>
            </div>
            <h1 v-if="laPage == ''" class="alert alert-info">Veuillez sélectionner une page</h1>
            <div v-if="laPage == 'signalement'">
                <h1>Les signalements :</h1>
                <p v-if="!lesSignalements.length" class="alert alert-info">Aucun signalement</p>
                <div v-for="unSignalement in lesSignalements" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unSignalement.accompagnant }} - {{ unSignalement.motif }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Par {{ unSignalement.demandeur }}</p>
                        <p class="card-text">Commentaire :</p>
                        <textarea readonly cols=50 rows=10>{{ unSignalement.commentaire}}</textarea>
                        <form method=post>
                            <div class="row">
                                <div class="col">
                                    <input type=submit :name="'banUtil' + unSignalement.idDemandeur + unSignalement.idAccompagnant" value="Bannir" class="btn btn-primary">
                                </div>
                                <div class="col">
                                    <input type=submit :name="'annuleSignalement' + unSignalement.idDemandeur + unSignalement.idAccompagnant" value="Annuler" class="btn btn-primary">  
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div v-if="laPage == 'ticket'">
                <h1>Les tickets :</h1>
                Adresse de départ : <input type=text v-model="searchTicketAdresseDepart"> Adresse d'arrivée : <input type=text v-model="searchTicketAdresseArrivee">
                <p v-if="!filteredTicket.length" class="alert alert-info">Aucun ticket</p>
                <div v-for="unTicket in filteredTicket" class="card">
                    <div class="card-header">
                        <p class="card-title">{{ unTicket.adresseDepart }} - {{ unTicket.adresseArrivee }}</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Demandeur : {{ unTicket.demandeur }}</p>
                        <p class="card-text">Accompagnant : {{ unTicket.accompagnant }} <span v-if="!unTicket.accompagnant">Pas d'accompagnant</span></p>
                        <p class="card-text">Transport : {{ unTicket.transport }}</p>
                        <p class="card-text">Date : {{ unTicket.date }}</p>
                        <p class="card-text">Heure : {{ unTicket.heure }}</p>
                        <form method=post>
                            <input type=submit :name="'deleteTicket' + unTicket.id" value="Supprimer" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>

            <div v-if="laPage == 'utilisateur'">
                <h1>Les utilisateurs :</h1>
                Nom : <input type=text v-model="searchUtilNom"> Prénom : <input type=text v-model="searchUtilPrenom">
                <p v-if="!filteredUtil.length" class="alert alert-info">Aucun ticket</p>
                <table v-else class="table table-striped table-hover table-bordered table-responsive">
                    <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Mail</th>
                        <th scope="col">Role</th>
                        <th scope="col">Bannir</th>
                    </tr>
                    <tr v-for="unUtilisateur in filteredUtil">
                        <td scope="row">{{ unUtilisateur.nom }}</td>
                        <td>{{ unUtilisateur.prenom }}</td>
                        <td>{{ unUtilisateur.mail }}</td>
                        <td>{{ unUtilisateur.role }}</td>
                        <td>
                            <form method=post>
                                <input type=submit :name="'deleteUtilisateur' + unUtilisateur.id" value="Bannir" class="btn btn-primary">
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
            const app = Vue.createApp({
                data(){
                    return{
                        laPage: localStorage.getItem('laPage') || "",
                        lesSignalements: <?php echo $jsonSignalement; ?>,
                        lesTickets: <?php echo $jsonTicket; ?>,
                        lesUtilisateurs: <?php echo $jsonUtilisateur; ?>,
                        searchUtilNom: "",
                        searchUtilPrenom: "",
                        searchTicketAdresseDepart: "",
                        searchTicketAdresseArrivee: ""
                    }
                },
                watch: {
                    laPage(newPage){
                        localStorage.setItem('laPage', newPage);
                    }
                },
                computed: {
                    filteredUtil(){
                        let recherche = this.lesUtilisateurs.filter(unUtil => unUtil.nom.toLowerCase().includes(this.searchUtilNom.toLowerCase()));

                        return recherche.filter(unUtil => unUtil.prenom.toLowerCase().includes(this.searchUtilPrenom.toLowerCase()));
                    },

                    filteredTicket(){
                        let recherche = this.lesTickets.filter(unTicket => unTicket.adresseDepart.toLowerCase().includes(this.searchTicketAdresseDepart.toLowerCase()));

                        return recherche.filter(unTicket => unTicket.adresseArrivee.toLowerCase().includes(this.searchTicketAdresseArrivee.toLowerCase()));
                    }
                },
                methods: {
                    changeLaPage(laPage){
                        this.laPage = laPage;
                    },
                    
                    clearStorage(){
                        localStorage.clear();
                    }
                }
            });

            app.mount("#app");
        </script>
    </body>
</html>