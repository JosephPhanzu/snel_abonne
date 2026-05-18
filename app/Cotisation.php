<?php

namespace App;

class Cotisation extends Database {
    protected $table = 'cotisations';
    private $code_employe, $montant, $date_paiement, $statut, $numero_paiement, $nom_tit_carte;

    private static $config;

    public function __construct($code_employe = null, $montant = null, $date_paiement = null, $statut = null, $numero_paiement = null, $nom_tit_carte = null)
    {
        $this->code_employe = $code_employe;
        $this->montant = $montant;
        $this->date_paiement = $date_paiement;
        $this->statut = $statut;
        $this->numero_paiement = $numero_paiement;
        $this->nom_tit_carte = $nom_tit_carte;

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }

    public function add() {
        $data = [
            'code_employe' => $this->code_employe,
            'montant' => $this->montant,
            'date_paiement' => $this->date_paiement,
            'statut' => $this->statut,
            'numero_paiement' => $this->numero_paiement,
            'nom_tit_carte' => $this->nom_tit_carte,
            'code' => bin2hex(random_bytes(16))
        ];
        try {
             return self::insert($this->table, $data);
        } catch (\Exception $th) {
            die('Erreur lors de l\'insertion'. $th->getMessage());
        }
    }

    public function updateInfo($code_employe, $date_paiement, $numero_paiement, $nom_tit_carte, $statut) {

        $data = [
            $date_paiement,
            $numero_paiement,
            $nom_tit_carte,
            $statut,
            $code_employe
        ];
        try {
            return self::updateByParam($this->table, 'date_paiement = ?, numero_paiement = ?, nom_tit_carte = ?, statut = ?', 'code_employe = ?', $data);
        } catch (\Exception $th) {
            die($th->getMessage());
        }
    }

    public function sumCotisations($code_employeur) {
        $stmt = self::$db->prepare("SELECT SUM(montant) AS total FROM $this->table WHERE code_employeur = :code_employeur");
        $stmt->bindValue('code_employeur', $code_employeur, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function totalCotPaye($code_employeur) {
        $stmt = self::$db->prepare("
            SELECT e.code_employeur, COUNT(*) AS total 
            FROM $this->table c
            JOIN employes e ON c.code_employe = e.code
            WHERE e.code_employeur = :code_employeur
        ");
        $stmt->bindValue('code_employeur', (string) $code_employeur, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
    }


    public function getPaginate($limit, $offset) {
        try {
            return self::paginate($this->table, $limit, $offset);
        } catch (\Exception $th) {
            die('Erreur lors de la pagination'. $th->getMessage());
        }
    }

    public function getAll() {
        return self::all($this->table);
    }

    public function exist() {
        try {
            return self::findByParams($this->table, 'code_employe = :code_employe AND date_paiement = :proprio', ['code_employe' => $this->code_employe, 'proprio' => $this->date_paiement]);
        } catch (\Throwable $th) {
            die('Erreur lors de existSymptome'. $th->getMessage());
        }
    }

    public function getByCode($code) {
        try {
            return self::findByParams($this->table, 'code = :code', ['code' => $code]);
        } catch (\Throwable $th) {
            die('Erreur lors de getByCode'. $th->getMessage());
        }
    }

 
}