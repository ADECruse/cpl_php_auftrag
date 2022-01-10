<?php 
    class Kunde {
        public $auftrag;
        public $kunde; // change to first and last names
        public $status;
        public $quellmedien;
        public $zielmedien;
        public $notizen;

        function __construct($auftrag, $kunde, $status, $quellmedien, $zielmedien, $notizen) {
            $this->auftrag = $auftrag;
            $this->kunde = $kunde; 
            $this->status = $status;
            $this->quellmedien = $quellmedien; 
            $this->zielmedien = $zielmedien; 
            $this->notizen = $notizen; 
        }
        function get_auftrag() {
            return $this->auftrag;
        }

        function get_kunde() {
            return $this->auftrag;
        }

        function get_status() {
            return $this->status;
        }

        function get_quellmedien() {
            return $this->quellmedien;
        }

        function get_zielmedien() {
            return $this->zielmedien;
        }

        function get_notizen() {
            return $this->notizen;
        }
    }
?>