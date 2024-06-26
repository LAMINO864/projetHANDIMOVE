<?php
    class NoteUtil {
        private $utilisateur;
        private $moyenne;
        private $lesNotes;

        public function __construct($utilisateur, $moyenne, $lesNotes) {
            $this->utilisateur = $utilisateur;
            $this->moyenne = $moyenne;
            $this->lesNotes = $lesNotes;
        }

        public function getUtilisateur() {
            return $this->utilisateur;
        }

        public function setUtilisateur($utilisateur) {
            $this->utilisateur = $utilisateur;
        }

        public function getMoyenne() {
            return $this->moyenne;
        }

        public function setMoyenne($moyenne) {
            $this->moyenne = $moyenne;
        }

        public function getLesNotes() {
            return $this->lesNotes;
        }

        public function addNote($note) {
            array_push($this->lesNotes, $note);
        }

        public function calculMoyenne() {
            $total = 0;
            foreach($this->lesNotes as $uneNote) {
                $total += $uneNote.getNote();
            }
            $moyenne = $total / count($this->lesNotes);
            $this->moyenne = $moyenne;
        }
    }