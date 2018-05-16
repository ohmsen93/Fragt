<?php
    class PassHash{
        private static $options=['cost' => 11];

        public static function hash($password){
            $options=['cost' => 11];
            return password_hash($password, PASSWORD_BCRYPT, $options);
        }

        public static function check_password($hash, $password){
            return password_verify($password, $hash);
        }
    }
?>