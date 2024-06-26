<?php
    class Note {
        private $trajet;
        private $demandeur;
        private $accompagnant;
        private $note;
        private $commentaire;

        public function __construct($trajet, $demandeur, $accompagnant, $note, $commentaire) {
            $this->trajet = $trajet;
            $this->demandeur = $demandeur;
            $this->accompagnant = $accompagnant;
            $this->note = $note;
            $this->commentaire = $commentaire;
        }

        public function getTrajet() {
            return $this->trajet;
        }

        public function setTrajet($trajet) {
            $this->trajet = $trajet;
        }

        public function getDemandeur() {
            return $this->demandeur;
        }

        public function setDemandeur($demandeur) {
            $this->demandeur = $demandeur;
        }

        public function getAccompagnant() {
            return $this->accompagnant;
        }

        public function setAccompagnant($accompagnant) {
            $this->accompagnant = $accompagnant;
        }

        public function getNote() {
            return $this->note;
        }

        public function setNote($note) {
            $this->note = $note;
        }

        public function getCommentaire() {
            return $this->commentaire;
        }

        public function setCommentaire($commentaire) {
            $this->commentaire = $commentaire;
        }
    }