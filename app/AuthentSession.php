<?php
namespace App;

class AuthentSession {

    private static $instance = null;
    
    private $code, $nom, $prenom, $email, $code_pharmacie, $role, $is_connected = false;

    private function __construct() {
        $this->loadSession();
    }

    // Pattern Singleton pour avoir une seule instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new AuthentSession();
        }
        return self::$instance;
    }

    // Charger les données depuis la session
    private function loadSession() {
        if (isset($_SESSION['poly-pharborel'])) {
            $userData = $_SESSION['poly-pharborel'];
            $this->code = $userData['code'] ?? null;
            $this->nom = $userData['nom'] ?? null;
            $this->email = $userData['email'] ?? null;
            $this->role = $userData['role'] ?? null;
            $this->is_connected = true;
        }
    }

    // Méthode de connexion
    public function login($userData) {
        $_SESSION['poly-pharborel'] = [
            'code' => $userData['code'] ?? null,
            'nom' => $userData['nom'] ?? null,
            'email' => $userData['email'] ?? null,
            'role' => $userData['role'] ?? null,
        ];
        
        $this->loadSession();
        return true;
    }

    // Méthode de déconnexion
    public function logout() {
        session_unset();
        session_destroy();
        
        $this->code = null;
        $this->nom = null;
        $this->email = null;
        $this->role = null;
        $this->is_connected = false;
        
        return true;
    }

    // Vérifier si l'utilisateur est connecté
    public function isConnected(){
        return $this->is_connected;
    }

    // Vérifier si un admin est connecté
    public function isAdminConnected(){
        return $this->is_connected && $this->role === 'admin';
    }

    // Vérifier si un utilisateur standard est connecté
    
    public function isMedecinConnected(){
        return $this->is_connected && $this->role === 'medecin';
    }
    public function isLaboConnected(){
        return $this->is_connected && $this->role === 'laboratoires';
    }
    public function isReceptionConnected(){
        return $this->is_connected && $this->role === 'reception';
    }

    // Vérifier un rôle spécifique
    public function hasRole($role){
        return $this->is_connected && $this->role === $role;
    }

    // Getters pour les propriétés
    public function getUserCode(){
        return $this->code;
    }

    public function getCodePharmacie(){
        return $this->code_pharmacie;
    }

    public function getNom(){
        return $this->nom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getRole(){
        return $this->role;
    }

    public function getFullName(){
        return $this->prenom . ' ' . $this->nom;
    }

    // Vérifier les permissions
    public function canAccess($requiredRole){
        if (!$this->is_connected) {
            return false;
        }

        // Hiérarchie des rôles (à adapter selon vos besoins)
        $hierarchy = [
            'user' => 1,
            'moderator' => 2,
            'admin' => 3,
            'superadmin' => 4
        ];

        $userLevel = $hierarchy[$this->role] ?? 0;
        $requiredLevel = $hierarchy[$requiredRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    // Mettre à jour les informations utilisateur
    public function updateUserInfo($data)
    {
        if ($this->is_connected && isset($_SESSION['poly-pharborel'])) {
            foreach ($data as $key => $value) {
                if (isset($_SESSION['poly-pharborel'][$key])) {
                    $_SESSION['poly-pharborel'][$key] = $value;
                    $this->$key = $value; // Mettre à jour la propriété
                }
            }
        }
    }
}
