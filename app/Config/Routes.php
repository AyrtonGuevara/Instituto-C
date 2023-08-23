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

//Cursos
$routes->get('/cursos','Cursos\C_cursos::index');
$routes->post('/cursos/registrar_curso','Cursos\C_cursos::registrar_curso');
$routes->post('/cursos/mostrar_curso','Cursos\C_cursos::mostrar_curso');
$routes->post('/cursos/modificar_curso','Cursos\C_cursos::modificar_curso');
$routes->post('/cursos/eliminar_curso','Cursos\C_cursos::eliminar_curso');
//horarios
$routes->get('/horarios','Cursos\C_horarios::index');

//Ambientes
$routes->get('/ambientes','Ambientes\C_ubicacion::index');
$routes->post('/ambientes/registrar_ubicacion','Ambientes\C_ubicacion::registrar_ubicacion');
$routes->post('/ambientes/mostrar_ubicacion', 'Ambientes\C_ubicacion::mostrar_ubicacion');
$routes->post('/ambientes/molificar_ubicacion','Ambientes\C_ubicacion::modificar_ubicacion');
$routes->post('/ambientes/eliminar_ubicacion','Ambientes\C_ubicacion::eliminar_ubicacion');
$routes->post('/ambientes/modal_mostrar_aulas','Ambientes\C_ubicacion::modal_mostrar_aulas');
$routes->post('/ambientes/modal_editar_aulas','Ambientes\C_ubicacion::modal_editar_aulas');

//aulas ???
$routes->get('/aula','Ubicacion\C_aulas::index');
$routes->post('/aula/registrar_aula','Ubicacion\C_aulas::registrar_aula');
$routes->post('/aula/mostrar_aula','Ubicacion\C_aulas::mostrar_aula');
$routes->post('/aula/modificar_aula','Ubicacion\C_aulas::modificar_aula');
$routes->post('/aula/eliminar_aula','Ubicacion\C_aulas::eliminar_aula');

//horarios
$routes->get('/horario','Clases\C_horarios::index');
$routes->post('/horario/registrar_horario','Clases\C_horarios::registrar_horarios');
$routes->post('/horario/aulas','Clases\C_horarios::mostrar_aulas');

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
