<?php
    
    namespace App;
    
    class Token_csrf {
        public static function gererateTokenCsrf(){
            if (empty($_SESSION['csrf'])) {
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            }
            return $_SESSION['csrf'];
        }
    }