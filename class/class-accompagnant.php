<?php
    class Accompagnant extends Util {
        private $notes;
        private $signalements;
        private $moyenne;

        public function __construct($id, $nom, $prenom, $photoProfile, $mail, $status, $permis, $notes, $signalements, $moyenne) {
            parent::__construct($id, $nom, $prenom, $photoProfile, $mail, $status);
            $this->permis = $permis;
            $this->notes = $notes;
            $this->signalements = $signalements;
            $this->moyenne = $moyenne;
        }

        public function getPermis(){
            return $this->permis;
        }

        public function setPermis($permis){
            $this->permis = $permis;
        }

        public function getNotes() {
            return $this->notes;
        }

        public function setNotes($notes) {
            $this->notes = $notes;
        }

        public function addNotes($note){
            $this->notes[] = $note;
        }

        public function getSignalement(){
            return $this->signalement;
        }

        public function setSignalement($signalements){
            $this->signalements = $signalements;
        }

        public function addSignalement($signalement){
            $this->signalements[] = $signalement;
        }

        public function getMoyenne(){
            return $this->moyenne;
        }

        public function setMoyenne($moyenne){
            $this->moyenne = $moyenne;
        }
    }