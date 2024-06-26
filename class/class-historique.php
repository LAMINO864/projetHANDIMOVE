<?php
    class Historique {
        private $id;
        private $demandeur;
        private $accompagnateur;
        private $adresseDepart;
        private $adresseArrivee;
        private $transport;
        private $note;

        public function __construct($id, $demandeur, $accompagnateur, $adresseDepart, $adresseArrivee, $transport, $note){
            $this->id = $id;
            $this->demandeur = $demandeur;
            $this->accompagnateur = $accompagnateur;
            $this->adresseDepart = $adresseDepart;
            $this->adresseArrivee = $adresseArrivee;
            $this->transport = $transport;
            $this->note = $note;
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

        public function getTransport(){
            return $this->transport;
        }

        public function setTransport($transport){
            $this->transport = $transport;
        }

        public function getNote(){
            return $this->note;
        }

        public function setNote($note){
            $this->note = $note;
        }
    }