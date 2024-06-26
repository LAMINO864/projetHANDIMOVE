<?php
    class Signalement {
        private $utilSignale;
        private $utilVise;
        private $motif;
        private $commentaire;

        public function __construct($utilSignale, $utilVise, $motif, $commentaire){
            $this->utilSignale = $utilSignale;
            $this->utilVise = $utilVise;
            $this->motif = $motif;
            $this->commentaire = $commentaire;
        }

        public function getUtilSignale(){
            return $this->utilSignale;
        }

        public function setUtilSignale($utilSignale){
            $this->utilSignale = $utilSignale;
        }

        public function getUtilVise(){
            return $this->utilVise;
        }

        public function setUtilVise($utilVise){
            $this->utilVise = $utilVise;
        }

        public function getMotif(){
            return $this->motif;
        }

        public function setMotif($motif){
            $this->motif = $motif;
        }

        public function getCommentaire(){
            return $this->commentaire;
        }

        public function setCommentaire($commentaire){
            $this->commentaire = $commentaire;
        }
    }