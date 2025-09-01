<?php
class Validator {
    public static function validatePassword($password) {
        if (strlen($password) < 8) {
            return "La contraseña debe tener al menos 8 caracteres";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "La contraseña debe contener al menos una mayúscula";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "La contraseña debe contener al menos una minúscula";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "La contraseña debe contener al menos un número";
        }
        return true;
    }
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El formato del email no es válido";
        }
        return true;
    }
    public static function validateName($name) {
        if (strlen(trim($name)) < 2) {
            return "El nombre debe tener al menos 2 caracteres";
        }
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $name)) {
            return "El nombre solo puede contener letras y espacios";
        }
        return true;
    }
}
?>