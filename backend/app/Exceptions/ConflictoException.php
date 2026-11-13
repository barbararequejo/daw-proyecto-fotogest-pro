<?php

namespace App\Exceptions;

use Exception;

/**
 * Se lanza cuando una operación choca con el estado actual de los datos
 * (email ya registrado, servicio con sesiones asociadas...). Se responde con 409.
 */
class ConflictoException extends Exception
{
}
