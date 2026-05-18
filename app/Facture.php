<?php
    
    namespace App;    

    class Facture extends Database{

        protected $table = "factures";
        private $num_client, $total, $code_user, $code_pharma;

        private static $config;

        public function __construct($num_client = null, $total = null, $code_user = null, $code_pharma = null) {

            $this->num_client = $num_client;
            $this->total = $total;
            $this->code_user = $code_user;
            $this->code_pharma = $code_pharma;

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
                    'num_client' => $this->num_client,
                    'total' => $this->total,
                    'code_utilisateur' => $this->code_user,
                    'code_pharmacie'=> $this->code_pharma,
                    'code' => bin2hex(random_bytes(16)),
                    'temps' => time()
                ]);
        
                $id = self::$db->lastInsertId();
                return self::findByParams($this->table, 'id = :id', ['id'=> $id]);

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la création de la facture : " . $e->getMessage());
            }
        }

        public function getByCode($code_pharmacie) {
            return self::findAllByParams($this->table, 'code_pharmacie = :code', ['code' => $code_pharmacie]);
        }
        
        // Single facture for employé
        public function getFactureByPharmaUser($code_pharmacie, $code_user, $code_facture): array{
            try {

                $stmt = self::$db->prepare("
                    SELECT DISTINCT f.id, f.code AS code_facture, c.nom_client, f.total, f.temps, e.nom AS nom_vendeur, fp.nom_produit, fp.quantite, fp.prix
                    FROM $this->table f
                    INNER JOIN client c ON f.code_membre = c.contact_client
                    INNER JOIN facture_produit fp ON f.code = fp.code_facture
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND f.code_utilisateur = :code_user AND f.code = :code_facture
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("code_user", $code_user, \PDO::PARAM_STR);
                $stmt->bindValue("code_facture", $code_facture, \PDO::PARAM_STR);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }

        // Single facture for proprio
        public function getArticleFactureByPharma($code_pharmacie, $code_facture): array {
            try {
                // 2️⃣ Articles de la facture
                $produitsStmt = self::$db->prepare("
                    SELECT 
                        nom_produit, quantite, prix, (quantite * prix) AS sous_total
                    FROM facture_produit
                    WHERE code_facture = :code_facture
                ");
                $produitsStmt->execute(['code_facture' => $code_facture]);
                return $produitsStmt->fetchAll(\PDO::FETCH_ASSOC);

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }


        public function getFactureByPharma($code_pharmacie, $code_facture): array{
            try {

                $stmt = self::$db->prepare("
                    SELECT DISTINCT
                        f.id, 
                        f.code AS code_facture, 
                        c.nom_client, 
                        f.total, 
                        f.temps, 
                        e.nom AS nom_vendeur,
                        fp.nom_produit, 
                        fp.quantite, 
                        fp.prix,
                        (fp.quantite * fp.prix) AS sous_total
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN facture_produit fp ON f.code = fp.code_facture
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie 
                    AND f.code = :code_facture;


                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("code_facture", $code_facture, \PDO::PARAM_STR);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }

        // All sales for employé
        public function getAllFacturesDetailsByPharmaUser($code_pharmacie, $code_user, $debutJour, $finJour, $limit, $offset): array{
            try {

                $stmt = self::$db->prepare("
                    SELECT DISTINCT f.id, f.code AS code_facture, c.code_pharmacie, c.nom_client, f.total, f.temps, e.nom AS nom_vendeur
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND f.code_utilisateur = :code_user AND c.code_pharmacie = :pharma_client AND f.temps BETWEEN :debutJour AND :finJour
                    ORDER BY f.id DESC 
                    LIMIT :limit OFFSET :offset
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("code_user", $code_user, \PDO::PARAM_STR);
                $stmt->bindValue("pharma_client", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("debutJour", $debutJour, \PDO::PARAM_STR);
                $stmt->bindValue("finJour", $finJour, \PDO::PARAM_STR);
                $stmt->bindValue('limit', (int)$limit, \PDO::PARAM_INT);
                $stmt->bindValue('offset', (int)$offset, \PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }

        // All sales for proprio
        public function getAllFacturesDetailsByPharma($code_pharmacie, $debutJour, $finJour, $limit, $offset): array{
            try {

                $stmt = self::$db->prepare(query: "
                    SELECT DISTINCT f.id, f.code AS code_facture, f.code_pharmacie, c.nom_client, f.total, f.temps, e.nom AS nom_vendeur
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND c.code_pharmacie = :pharma_client AND f.temps BETWEEN :debutJour AND :finJour
                    ORDER BY f.id DESC 
                    LIMIT :limit OFFSET :offset
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("pharma_client", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("debutJour", $debutJour, \PDO::PARAM_STR);
                $stmt->bindValue("finJour", $finJour, \PDO::PARAM_STR);
                $stmt->bindValue('limit', (int)$limit, \PDO::PARAM_INT);
                $stmt->bindValue('offset', (int)$offset, \PDO::PARAM_INT);
                $stmt->execute();
                
                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }


        public function getAllFacturesByPharmaUser($code_pharmacie, $code_user, $debutJour, $finJour): array{
            try {

                $stmt = self::$db->prepare("
                    SELECT DISTINCT f.id, f.code AS code_facture, f.code_pharmacie, c.nom_client, f.total, f.temps, e.nom AS nom_vendeur
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND f.code_utilisateur = :code_user AND c.code_pharmacie = :pharma_client AND f.temps BETWEEN :debutJour AND :finJour
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("code_user", $code_user, \PDO::PARAM_STR);
                $stmt->bindValue("pharma_client", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("debutJour", $debutJour, \PDO::PARAM_STR);
                $stmt->bindValue("finJour", $finJour, \PDO::PARAM_STR);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }

        // All sales for proprio
        public function getAllFacturesByPharma($code_pharmacie, $debutJour, $finJour): array{
            try {

                $stmt = self::$db->prepare(query: "
                    SELECT DISTINCT f.id, f.code AS code_facture, f.code_pharmacie, c.nom_client, f.total, f.temps, e.nom AS nom_vendeur
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND c.code_pharmacie = :pharma_client AND f.temps BETWEEN :debutJour AND :finJour
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("pharma_client", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("debutJour", $debutJour, \PDO::PARAM_STR);
                $stmt->bindValue("finJour", $finJour, \PDO::PARAM_STR);
                $stmt->execute();
                
                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }


        public function getProduitPlusVenduByPharma($code_pharmacie, $debutJour, $finJour): array{
            try {

                $stmt = self::$db->prepare("
                    SELECT DISTINCT c.nom_client, f.total, f.temps, e.nom AS nom_vendeur, fp.nom_produit, fp.quantite, fp.prix
                    FROM $this->table f
                    INNER JOIN client c ON f.num_client = c.contact_client
                    INNER JOIN facture_produit fp ON f.code = fp.code_facture
                    INNER JOIN employe e ON f.code_utilisateur = e.code
                    WHERE f.code_pharmacie = :code_pharmacie AND f.temps BETWEEN :debutJour AND :finJour
                ");
                $stmt->bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
                $stmt->bindValue("debutJour", $debutJour, \PDO::PARAM_STR);
                $stmt->bindValue("finJour", $finJour, \PDO::PARAM_STR);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la récupération des factures : " . $e->getMessage());
            }
        }

        // Rapport de ventes journalier
        public function getDailyReport($date = null, $code_pharmacie = null): array {
            try {
                if (empty($date)) {
                    $debutJour = strtotime('today');
                } else {
                    $debutJour = strtotime($date);
                }
                $finJour = $debutJour + 86400 - 1;

                $params = [
                    'debut' => $debutJour,
                    'fin' => $finJour
                ];

                $pharmaFilter = '';
                if (!empty($code_pharmacie)) {
                    $pharmaFilter = ' AND f.code_pharmacie = :code_pharmacie';
                    $params['code_pharmacie'] = $code_pharmacie;
                }

                // Total ventes et nombre de factures
                $stmt = self::$db->prepare("SELECT COUNT(DISTINCT f.id) AS invoices_count, COALESCE(SUM(fp.quantite * fp.prix),0) AS total_sales FROM $this->table f JOIN facture_produit fp ON f.code = fp.code_facture WHERE f.temps BETWEEN :debut AND :fin" . $pharmaFilter);
                $stmt->execute($params);
                $summary = $stmt->fetch(\PDO::FETCH_ASSOC) ?: ['invoices_count' => 0, 'total_sales' => 0];

                // Top produits
                $queryTop = "SELECT fp.nom_produit AS name, SUM(fp.quantite) AS qty, COALESCE(SUM(fp.quantite * fp.prix),0) AS sales FROM facture_produit fp JOIN $this->table f ON f.code = fp.code_facture WHERE f.temps BETWEEN :debut AND :fin" . $pharmaFilter . " GROUP BY fp.nom_produit ORDER BY qty DESC LIMIT 10";
                $stmtTop = self::$db->prepare($queryTop);
                $stmtTop->execute($params);
                $topProducts = $stmtTop->rowCount() > 0 ? $stmtTop->fetchAll(\PDO::FETCH_ASSOC) : [];

                return [
                    'date' => date('Y-m-d', $debutJour),
                    'total_sales' => (float)$summary['total_sales'],
                    'invoices_count' => (int)$summary['invoices_count'],
                    'top_products' => $topProducts
                ];

            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la génération du rapport : " . $e->getMessage());
            }
        }


    }
