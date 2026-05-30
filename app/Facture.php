<?php
    
    namespace App;    

    class Facture extends Database{

        protected $table = "facture";

        			
        private $code_conso, $montant, $statut, $date_facture;

        private static $config;

        public function __construct($code_conso = null, $montant = null, $statut = null, $date_facture = null) {

            $this->code_conso = $code_conso;
            $this->montant = $montant;
            $this->statut = $statut;
            $this->date_facture = $date_facture;

            self::$config = (ConfigDB::getInstance())->getConfig();
            parent::__construct(self::$config);
        }

        public function getAll() {
            try {
                return self::all($this->table);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        public function findOne($code) {
            try {
                return self::find($this->table, $code);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        public function getById ($id) {
            try {
                return self::findByParams($this->table, 'id = :id', ['id' => $id]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        public function add(){

            try {

                self::insert($this->table, [
                    'code_conso' => $this->code_conso,
                    'montant' => $this->montant,
                    'statut' => $this->statut,
                    'date_facture'=> $this->date_facture,
                    'code' => bin2hex(random_bytes(16)),
                ]);
        
                $id = self::$db->lastInsertId();
                return self::findByParams($this->table, 'id = :id', ['id'=> $id]);

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la création de la facture : " . $e->getMessage());
            }
        }

        public function getByCode($code) {
            return self::findAllByParams($this->table, 'code = :code', ['code' => $code]);
        }
        
        // Mettre à jour le statut de la facture
        public function updateStatut($code, $newStatut) {
            try {
                return self::updateByParam($this->table, 'statut = ?', 'code = ?', [$newStatut, $code]);
            } catch (\Exception $th) {
                throw $th;
            }
        }

        


        


    }
