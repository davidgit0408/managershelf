<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load auth filter
$auth = ['filter' => 'auth'];

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function () {
    return view('errors/html/error_404');
});
// The auto-routing is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// It is recommended that you do not set it to `true`.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// LOAD VIEW
$routes->get('/', 'AdminController::index');
$routes->get('/sign_up', 'SignUpController::index');
$routes->get("/dashboard", 'AdminController::dashboard_admin', $auth);
$routes->get("/myaccount", 'AdminController::myaccount', $auth);
$routes->get("/painel_financeiro", 'AdminController::painel_financeiro', $auth);
$routes->get("/new_planogram/(:num)", 'AdminController::new_planogram/$1', $auth);
$routes->get("/all_planograms", 'AdminController::all_planograms', $auth);
$routes->get("/edit_planogram/(:num)", 'AdminController::edit_planogram/$1', $auth);
$routes->get("/duplica_planogram/(:num)", 'AdminController::duplica_planogram/$1', $auth);
$routes->get("/delete_planogram/(:num)", 'AdminController::delete_planogram/$1', $auth);
$routes->get("/all_scenarios", 'AdminController::all_scenarios', $auth);
$routes->get("/all_orders", 'AdminController::all_orders', $auth);
$routes->get("/order/(:num)", 'AdminController::order/$1', $auth);
$routes->get("/order", 'AdminController::order', $auth);
$routes->get("/gallery", 'AdminController::gallery', $auth);
$routes->get("/new_product", 'AdminController::new_product', $auth);
$routes->get("/import_export", 'AdminController::import_export', $auth);
$routes->get("/interviewed_import", 'AdminController::interviewed_import', $auth);
$routes->get("/all_company", 'AdminController::all_company', $auth);
$routes->get("/new_company", 'AdminController::new_company', $auth);
$routes->get("/view_scenario/(:num)", 'AdminController::view_scenario/$1', $auth);
$routes->get("/get_orders", 'AdminController::get_orders', $auth);
$routes->get("/new_company", 'AdminController::new_company', $auth);
$routes->get('/client_scenario', 'AdminController::client_scenario', $auth);
$routes->get("/scenario", 'ClientController::scenario');
$routes->get("/users", 'AdminController::users', $auth);
$routes->get("/edit_client/(:num)", 'AdminController::edit_client/$1', $auth);
$routes->get("/all_clients", 'AdminController::all_clients', $auth);
$routes->get('/teste', 'AdminController::teste', $auth);
$routes->get('/delete_user/(:num)', 'AdminController::delete_user/$1', $auth);
$routes->get('/confirm_email/(:any)/(:any)', 'SignUpController::confirm_email/$1/$2', $auth);
$routes->get('/heatmap', 'AdminController::heatmap', $auth);
$routes->get("/share", 'AdminController::share/', $auth);
$routes->get("/alert_product_view", 'AdminController::alert_product_view/', $auth);
$routes->get("/export_carts", 'AdminController::export_carts/', $auth);
$routes->get("/thankyou", 'ClientController::thankyou/', $auth);
$routes->get("/cart", 'ClientController::cart/', $auth);
$routes->get('/panel_interviewees', 'AdminController::panel_interviewees');

$routes->group("form", static function ($routes){
    global $auth;
    $routes->get('init', 'AdminController::init_search_form');
    $routes->get('end', 'AdminController::end_shopping_search_form'); 
    $routes->get('obrigado', 'AdminController::obrigado');
    $routes->get('obrigado_por_concluir', 'AdminController::obrigado_por_concluir');
    $routes->get('obrigado_ja_realizou', 'AdminController::obrigado_ja_realizou');
    $routes->get('obrigado_cota_encerrada', 'AdminController::obrigado_cota_encerrada');
    $routes->get('confirm_pix', 'AdminController::confirm_pix');
    $routes->match(['get', 'post'], 'save', 'AdminController::save_form_answers');
    $routes->match(['get', 'post'], 'save_confirm_pix', 'AdminController::save_form_answers_confirm_pix');
    $routes->match(['get', 'post'], 'save_end', 'AdminController::save_end_form_answers');
    $routes->get('results', 'AdminController::view_result_answers', $auth);
    $routes->match(['get', 'post'], 'get_interview_person', 'AdminController::get_interview_person');
});

// ACTIONS
$routes->add('login', 'AdminController::login');
$routes->add('/sign_up/store', 'SignUpController::store');
$routes->add("/add_product", 'AdminController::add_product');
$routes->add("/add_planogram", 'AdminController::add_planogram');
$routes->add("/all_products", 'AdminController::all_products');
$routes->add("/all_alert_products", 'AdminController::all_alert_products');
$routes->add("/export_csv_alert_priducts", 'AdminController::export_csv_alert_priducts');
$routes->add("/client_scenario", 'AdminController::client_scenario');
$routes->add('/update_user', 'AdminController::update_user');
$routes->add('/update_planogram', 'AdminController::update_planogram');
$routes->add('/dragDropUpload', 'AdminController::dragDropUpload');
// $routes->add('/update_product', 'AdminController::update_product');
$routes->add('/update_product_dimension', 'AdminController::update_product_dimension');
$routes->add('/delete_product', 'AdminController::delete_product');
$routes->add('/add_company', 'AdminController::add_company');
$routes->add('/edit_company', 'AdminController::edit_company');
$routes->add('/update_company', 'AdminController::update_company');
$routes->add('/payment/transition_id=(:any)/', 'AdminController::payment/$1/$2');
$routes->add('/gera_heatmap/(:num)', 'AdminController::gera_heatmap/$1');
$routes->add("/client/id_company=(:num)", 'ClientController::client/$1');
$routes->add('/libera_planograma', 'AdminController::libera_planograma');
$routes->add("/logout", 'AdminController::logout');
$routes->add("/password_reset", 'PasswordResetController::reset_pass_request_view');
$routes->add("/password_reset/key", 'PasswordResetController::insert_reset_key');
$routes->add("/reset", 'PasswordResetController::reset_pass_view');
$routes->add("/reset/update", 'PasswordResetController::update_password');
$routes->add('/export_csv', 'AdminController::export_csv');
$routes->add('/import_interviewed_csv', 'AdminController::import_interviewed_csv');
$routes->add('/import_csv', 'AdminController::import_csv');
$routes->add("/update_client", 'AdminController::update_client');
$routes->add("/add_user", 'AdminController::add_user');
$routes->add("/logs", 'AdminController::logs');
$routes->add("/versions", 'AdminController::versions');
$routes->add('/edit_product/(:num)/', 'AdminController::edit_product/$1');
$routes->add("/save_eye_tracking", 'ClientController::save_eye_tracking');
$routes->add("/save_user_image", 'ClientController::save_user_image');
$routes->add( '/saveProdutosVistos', 'ClientController::saveProdutosVistos');
$routes->add("/add_cart", 'ClientController::add_cart/');
$routes->add("/add_order", 'ClientController::add_order/');
$routes->add("/bigdata_products", 'AdminController::bigdata_products'); //BigData
$routes->add('/end_form', 'ApiController::index_post');


// AJAX
$routes->post('/adicionarPosicao', 'AdminController::adicionarPosicao');
$routes->match(['get', 'post'], '/update_payment_status/transiton_id=(:any)/id=(:any)', 'PagamentoController::update_payment/$1/$2');
$routes->match(['get', 'post'], '/add_category_product', 'AdminController::add_category_product');
$routes->match(['get', 'post'], '/orderUpdate', 'AdminController::orderUpdate');
$routes->match(['get', 'post'], '/remove_position', 'AdminController::remove_position');
$routes->match(['get', 'post'], '/remove_shelf', 'AdminController::remove_shelf');
$routes->match(['get', 'post'], '/edit_position', 'AdminController::edit_position');
$routes->match(['get', 'post'], '/remove_column', 'AdminController::remove_column');
$routes->match(['get', 'post'], '/get_orders', 'AdminController::get_orders');
$routes->match(['get', 'post'], '/edit_dimension', 'AdminController::edit_dimension');
$routes->match(['get', 'post'], '/copy_column', 'AdminController::copy_column');
$routes->match(['get', 'post'], '/export_scenario', 'AdminController::export_scenario');
$routes->match(['get', 'post'], '/copy_shelf', 'AdminController::copy_shelf');
$routes->match(['get', 'post'], '/img_user', 'AdminController::img_user');
$routes->match(['get', 'post'], '/update_view', 'AdminController::update_view');
$routes->match(['get', 'post'], '/update_view_notification', 'AdminController::update_view_notification');
$routes->match(['get', 'post'], '/view_notify', 'AdminController::view_notify');
$routes->match(['get', 'post'], '/delete_notify', 'AdminController::delete_notify');
$routes->match(['get', 'post'], '/get_company_info', 'AdminController::get_company_info');
$routes->match(['get', 'post'], '/get_produtos', 'AdminController::get_produtos');
$routes->match(['get', 'post'], '/check_produtos', 'AdminController::check_produtos');
$routes->match(['get', 'post'], '/user_pass', 'AdminController::user_pass');
$routes->match(['get', 'post'], '/add_blank_position', 'AdminController::add_blank_position');
$routes->match(['post'], '/search_product', 'AdminController::search_product');
$routes->match(['get', 'post'], '/edit_product', 'AdminController::edit_product');
$routes->match(['get', 'post'], '/add_image', 'AdminController::add_image');
$routes->match(['get', 'post'], '/export_user_image', 'AdminController::export_user_image'); // GazeRecorder
$routes->match(['get', 'post'], '/saveProdutosVistos', 'ClientController::saveProdutosVistos'); // GazeRecorder
$routes->match(['get', 'post'], '/pagseguro_return', 'PagamentoController::pagseguro_return'); // MercadoPago
$routes->match(['get', 'post'], '/do_payment', 'PagamentoController::do_payment'); // MercadoPago
$routes->match(['get', 'post'], '/do_paymentCredit', 'PagamentoController::do_paymentCredit'); // MercadoPago
$routes->match(['get', 'post'], '/deleteImage', 'AdminController::deleteImage');
$routes->match(['get', 'post'], '/loading_img', 'AdminController::loading_img'); 
$routes->match(['get', 'post'], '/loading_gallery', 'AdminController::loading_gallery'); 
$routes->match(['get', 'post'], '/update_product', 'AdminController::update_product');
$routes->match(['get', 'post'], '/get_purchase/(:any)', 'AdminController::get_purchase/$1');
$routes->match(['get', 'post'], '/bigdata_import/(:any)', 'AdminController::bigdata_import/$1'); //BigData
$routes->match(['get', 'post'], '/get_products_form_by_ean', 'AdminController::get_products_form_by_ean');


$routes->group("bigdata", static function ($routes){
    global $auth;
    $routes->get("all", 'BigDataController::viewList', $auth);
    $routes->get("import", 'BigDataController::viewCreate', $auth);
    $routes->post("store", 'BigDataController::store');
    $routes->get("edit/(:num)", 'BigDataController::viewUpdate/$1', $auth);
    $routes->post("update", 'BigDataController::update');
    $routes->add("delete", 'BigDataController::delete');
});

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
