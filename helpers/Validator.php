<?php

class Validator {

    // VALIDAR CAMPOS VACÍOS
    public static function requerido($valor): bool {

        return !empty(trim($valor));
    }

    // VALIDAR EMAIL
    public static function email($email): bool {

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // VALIDAR LONGITUD MÍNIMA
    public static function minLength($valor, $minimo): bool {

        return strlen(trim($valor)) >= $minimo;
    }

    // VALIDAR LONGITUD MÁXIMA
    public static function maxLength($valor, $maximo): bool {

        return strlen(trim($valor)) <= $maximo;
    }

    // VALIDAR QUE DOS CONTRASEÑAS COINCIDAN
    public static function passwords($password, $confirmarPassword): bool {

        return $password === $confirmarPassword;
    }

    // VALIDAR NÚMEROS
    public static function numero($valor): bool {

        return is_numeric($valor);
    }

    // VALIDAR TEXTO
    public static function texto($valor): bool {

        return preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $valor);
    }

    // VALIDAR CAMPOS SOLO POSITIVOS
    public static function positivo($valor): bool {

        return $valor > 0;
    }
}