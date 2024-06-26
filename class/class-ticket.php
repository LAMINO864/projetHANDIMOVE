<?php
    class Ticket {
        private $id;
        private $demandeur;
        private $accompagnateur;
        private $adresseDepart;
        private $adresseArrivee;
        private $date;
        private $heure;
        private $transport;

        public function __construct($id, $demandeur, $accompagnateur, $adresseDepart, $adresseArrivee, $date, $heure, $transport){
            $this->id = $id;
            $this->demandeur = $demandeur;
            $this->accompagnateur = $accompagnateur;
            $this->adresseDepart = $adresseDepart;
            $this->adresseArrivee = $adresseArrivee;
            $this->date = $date;
            $this->heure = $heure;
            $this->transport = $transport;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getDemandeur(){
            return $this->demandeur;
        }

        public function setDemandeur($demandeur){
            $this->demandeur = $demandeur;
        }

        public function getAccompagnateur(){
            return $this->accompagnateur;
        }

        public function setAccompagnateur($accompagnateur){
            $this->accompagnateur = $accompagnateur;
        }

        public function getAdresseDepart(){
            return $this->adresseDepart;
        }

        public function setAdresseDepart($adresseDepart){
            $this->adresseDepart = $adresseDepart;
        }

        public function getAdresseArrivee(){
            return $this->adresseArrivee;
        }

        public function setAdresseArrivee($adresseArrivee){
            $this->adresseArrivee = $adresseArrivee;
        }

        public function getDate() {
            return $this->date;
        }

        public function setDate($date) {
            $this->date = $date;
        }

        public function getHeure() {
            return $this->heure;
        }

        public function setHeure($heure) {
            $this->heure = $heure;
        }

        public function getTransport(){
            return $this->transport;
        }

        public function setTransport($transport){
            $this->transport = $transport;
        }
    }