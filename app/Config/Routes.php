<?php
/*
    AyrtonGuevaraMontaño 30/07/2023 
*/
namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');

//categorias
$routes->get('/categorias','Categoria\Ccategorias::index');

//login
$routes->get('/login','Login\C_login::index');
$routes->post('/login/autenticar','Login\C_login::autenticar');
$routes->get('/cerrar_sesion','Security\session::cerrar_sesion');

//Personal
$routes->get('/personal','Personal\C_personal::index');
$routes->post('/personal/agregar_personal','Personal\C_personal::registrar_personal');
$routes->post('/personal/mostrar_personal','Personal\C_personal::mostrar_personal');
$routes->post('/personal/modificar_personal','Personal\C_personal::modificar_personal');
$routes->post('/personal/eliminar_personal','Personal\C_personal::eliminar_personal');

//ubicaciones
$routes->get('/ubicacion','Ubicacion\C_ubicacion::index');
$routes->post('/ubicacion/registrar_ubicacion','Ubicacion\C_ubicacion::registrar_ubicacion');
$routes->post('/ubicacion/mostrar_ubicacion', 'Ubicacion\C_ubicacion::mostrar_ubicacion');
$routes->post('/ubicacion/molificar_ubicacion','Ubicacion\C_ubicacion::modificar_ubicacion');
$routes->post('/ubicacion/eliminar_ubicacion','Ubicacion\C_ubicacion::eliminar_ubicacion');
//aulas
$routes->get('/aula','Ubicacion\C_aulas::index');
$routes->post('/aula/registrar_aula','Ubicacion\C_aulas::registrar_aula');
$routes->post('/aula/mostrar_aula','Ubicacion\C_aulas::mostrar_aula');
$routes->post('/aula/modificar_aula','Ubicacion\C_aulas::modificar_aula');
$routes->post('/aula/eliminar_aula','Ubicacion\C_aulas::eliminar_aula');


//usuarios
$routes->get('/usuario','Usuario\C_usuario::index');
$routes->post('/usuario/registrar_usuario','Usuario\C_usuario::registrar_usuario');
$routes->post('/usuario/mostrar_usuario','Usuario\C_usuario::mostrar_usuario');
$routes->post('/usuario/modificar_usuario','Usuario\C_usuario::modificar_usuario');
$routes->post('usuario/eliminar_usuario','Usuario\C_usuario::eliminar_usuario');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
