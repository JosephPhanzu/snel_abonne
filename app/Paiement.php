<?php
    
    namespace App;    

    class Paiement extends Database{

        protected $table = "paiement";

        private $code_facture, $montant, $statut, $date_paiement, $methode, $reference_transaction;

        private static $config;

        public function __construct($code_facture = null, $montant = null, $statut = null, $date_paiement = null, $methode = null, $reference_transaction = null) {

            $this->code_facture = $code_facture;
            $this->montant = $montant;
            $this->statut = $statut;
            $this->date_paiement = $date_paiement;
            $this->methode = $methode;
            $this->reference_transaction = $reference_transaction;

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

        public function exist() {
        try {
            return self::findByParams($this->table, 'code_facture = :code_facture AND statut = :statut', ['code_facture' => $this->code_facture, 'statut' => 'Payée']);
        } catch (\Throwable $th) {
            die('Erreur lors de existSymptome'. $th->getMessage());
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
                    'code_facture' => $this->code_facture,
                    'montant' => $this->montant,
                    'date_paiement'=> $this->date_paiement,                    
                    'methode' => $this->methode,
                    'reference_transaction' => $this->reference_transaction,
                    'statut' => $this->statut,
                    'code' => bin2hex(random_bytes(16)),
                ]);
        
                $id = self::$db->lastInsertId();
                return self::findByParams($this->table, 'id = :id', ['id'=> $id]);

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la création de la facture : " . $e->getMessage());
            }
        }

        public function getByCode($date_facturecie) {
            return self::findAllByParams($this->table, 'date_facturecie = :code', ['code' => $date_facturecie]);
        }
        
        public function getPaimentJoinFacPayeJoinabonne() {
            $stmt = self::$db->prepare("SELECT p.*, f.code_abonne, f.mois, f.anne, a.nom FROM $this->table p JOIN facture f ON p.code_facture = f.code JOIN consommation c ON f.code_conso = c.code JOIN abonne a ON c.code_abonne = a.code");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        public function getPaimentJoinFacPayeJoinByabonne($code) {
            $stmt = self::$db->prepare("SELECT p.*, f.code_abonne, f.mois, f.anne, a.nom FROM $this->table p JOIN facture f ON p.code_facture = f.code JOIN consommation c ON f.code_conso = c.code JOIN abonne a ON c.code_abonne = a.code WHERE a.code = :code");
            $stmt->bindValue('code', $code, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }


    }
