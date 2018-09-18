<?php
class ValidarCorreo {
    /* Valida la dirección de correo recibida en la variable $email. */
    function correoValido($email) {
        $esValido = true; //Bandera para manejar los condicionales si el correo es válido o no, comienza en true
        $existeArroba = strrpos($email, "@");//Verifica que la dirección recibida tenga @
        if (is_bool($existeArroba) && !$existeArroba) {
            $esValido = false; //Si no tiene arroba, la bandera es false
        } else { //En caso contrario
            $dominio = substr($email, $existeArroba + 1); //se obtiene la parte del dominio del correo
            $dominioLen = strlen($dominio); //longitud de la parte del dominio
            $usuario = substr($email, 0, $existeArroba); //se obtiene la parte usuario del correo
            $usuarioLen = strlen($usuario); //longitud de la parte usuario
            if ($usuarioLen < 1 || $usuarioLen > 64) {
                // valida la longitud de la parte usuario del correo
                $esValido = false;
            } else if ($dominioLen < 1 || $dominioLen > 255) {
                // valida la longitud de la parte del dominio del correo
                $esValido = false;
            } else if ($usuario[0] == '.' || $usuario[$usuarioLen - 1] == '.') {
                // valida que la parte usuario no termine en '.'
                $esValido = false;
            } else if (preg_match('/\\.\\./', $usuario)) {
                // valida que la parte usuario no tenga dos puntos consecutivos
                $esValido = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $dominio)) {
                // valida los caracteres aceptados en el dominio
                $esValido = false;
            } else if (preg_match('/\\.\\./', $dominio)) {
                // valida que la parte del dominio no tenga dos puntos consecutivos
                $esValido = false;
            } else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $usuario))) {
                // valida caracteres aceptados en la parte usuario
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $usuario))) {
                    $esValido = false;
                }
            }
            if ($esValido && !(checkdnsrr($dominio, "MX") || checkdnsrr($dominio, "A"))) {
                // valida el dominio del correo dentro del registro de Mail Exchange
                $esValido = false;
            }
        }
        return $esValido; //retorna el resultado de la bandera validando la dirección de correo
    }
}
?>