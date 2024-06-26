<?php
    class Util {
        private $id;
        private $nom;
        private $prenom;
        private $photoProfile;
        private $mail;
        private $status;

        public function __construct($id, $nom, $prenom, $photoProfile, $mail, $status){
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->photoProfile = $photoProfile;
            $this->mail = $mail;
            $this->status = $status;
        }

        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id = $id;
        }

        public function getNom(){
            return $this->nom;
        }
        public function setNom($nom){
            $this->nom = $nom;
        }

        public function getPrenom(){
            return $this->prenom;
        }
        public function setPrenom($prenom){
            $this->prenom;
        }

        public function getPhotoProfile(){
            return $this->photoProfile;
        }
        public function setPhotoProfile($photoProfile){
            $this->photoProfile = $photoProfile;
        }

        public function getMail(){
            return $this->mail;
        }
        public function setMail($mail){
            $this->mail = $mail;
        }

        public function getStatus(){
            return $this->status;
        }
        public function setStatus($status){
            $this->status = $status;
        }
    }