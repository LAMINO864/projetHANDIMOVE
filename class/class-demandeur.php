<?php
    class Demandeur extends Util {
        private $lesTickets;

        public function __construct($id, $nom, $prenom, $photoProfile, $mail, $status, $lesTickets, $lesTicketsValide) {
            parent::__construct($id, $nom, $prenom, $photoProfile, $mail, $status);
            $this->lesTickets = $lesTickets;
            $this->lesTicketsValide = $lesTicketsValide;
        }

        public function getLesTickets() {
            return $this->lesTickets;
        }

        public function addTicket($ticket) {
            $this->lesTickets[] = $ticket;
        }

        public function getLesTicketsValide(){
            return $this->lesTicketsValide;
        }

        public function addTicketValide($ticket){
            $this->lesTicketsValide = $ticket;
        }
    }