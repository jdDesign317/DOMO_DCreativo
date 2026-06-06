<?php

class Response {

    // MENSAJE SUCCESS
    public static function success($mensaje) {

        return "
            <div class='alert alert-success alert-dismissible fade show mt-3' role='alert'>
                {$mensaje}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        ";
    }

    // MENSAJE ERROR
    public static function error($mensaje) {

        return "
            <div class='alert alert-danger alert-dismissible fade show mt-3' role='alert'>
                {$mensaje}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        ";
    }

    // MENSAJE WARNING
    public static function warning($mensaje) {

        return "
            <div class='alert alert-warning alert-dismissible fade show mt-3' role='alert'>
                {$mensaje}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        ";
    }

    // MENSAJE INFO
    public static function info($mensaje) {

        return "
            <div class='alert alert-info alert-dismissible fade show mt-3' role='alert'>
                {$mensaje}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        ";
    }
}