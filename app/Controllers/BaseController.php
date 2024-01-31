<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();
    }

    public function control_pagina($id_pagina){
        $menu_permisos=session('permisos');
        if(array_search($id_pagina,$menu_permisos)===false){
            throw new \App\Controllers\Error\C_403();
        }
    }

    public function pagination($lista){
        $pager = \Config\Services::pager();

        $total   =count($lista);
        //configuracion de la paginacion(se obtiene la pagina actual)
        $page    = (int) ($_GET['page'] ?? 1);
        //(se establece la cantidad por pagina)
        $perPage = 10;
        //calcular el desplazamiento
        $offset = ($page - 1) * $perPage;
        //dividiendo el resultado
        $pagedResults = array_slice($lista, $offset, $perPage);
        //obteniendo enlaces de la paginacion
        $pager_links = $pager->makeLinks($page, $perPage, $total,'pagination');
        return [
            'pagedResults' => $pagedResults,
            'pager_links' => $pager_links
        ];
    }
}
