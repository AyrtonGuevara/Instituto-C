<?php
/*
	Ayrton Jhonny Guevara Montaño 28-12-23
*/
namespace App\Controllers\Error;

use CodeIgniter\Exceptions\ExceptionInterface;

class C_403 extends \RuntimeException implements ExceptionInterface
{
    protected $message = 'No tiene permitido el acceso.';
    protected $code = 403;
}


?>