<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================================
// RUTE HALAMAN UTAMA
// ==========================================================
$routes->get('/', 'Home::index');

// ==========================================================
// RUTE OTENTIKASI (LOGIN & REGISTER)
// ==========================================================
$routes->get('/login', 'Auth::login');
$routes->post('/login/process', 'Auth::processLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register/process', 'Auth::processRegister');
$routes->get('/logout', 'Auth::logout');

// ==========================================================
// RUTE PROFIL PEMAIN
// ==========================================================
$routes->get('/profil', 'Profil::index');
$routes->post('/profil/update', 'Profil::updateProfil');
$routes->post('/profil/password', 'Profil::updatePassword');

// ==========================================================
// RUTE TURNAMEN (UNTUK PEMAIN)
// ==========================================================
$routes->get('/turnamen/daftar/(:num)', 'Turnamen::daftar/$1');
$routes->post('/turnamen/simpan', 'Turnamen::simpan');
$routes->get('/tim-saya', 'Turnamen::timSaya');
$routes->get('/turnamen/detail/(:num)', 'Turnamen::detail/$1');

// INI DIA RUTE BATAL YANG BENAR (DI LUAR GRUP ADMIN)
$routes->get('/user/batal/(:num)', 'Turnamen::batal/$1');

// ==========================================================
// RUTE ADMIN (MENGELOLA TURNAMEN & PENDAFTAR)
// ==========================================================
$routes->group('admin', function($routes) {
    // Dashboard Admin (Opsional, karena sekarang tombol kelola ada di Home)
    $routes->get('/', 'Admin::index');
    
    // Rute Generate Bagan
    $routes->get('generate-bracket/(:num)', 'Admin::generateBracket/$1');
    
    // Rute CRUD Turnamen
    $routes->get('create', 'Admin::create');               // Menampilkan form tambah
    $routes->post('store', 'Admin::store');                // Memproses data tambah
    $routes->get('edit/(:num)', 'Admin::edit/$1');         // Menampilkan form edit
    $routes->post('update/(:num)', 'Admin::update/$1');    // Memproses data edit
    $routes->get('delete/(:num)', 'Admin::delete/$1');     // Menghapus turnamen
    
    // Rute Kelola Pendaftar (Tim)
    $routes->get('tim/(:num)', 'Admin::teams/$1');                               // Melihat daftar tim di suatu turnamen
    $routes->get('tim/status/(:num)/(:segment)', 'Admin::updateTeamStatus/$1/$2'); // Mengubah status (Approve/Reject)

    // Kelola Pertandingan & Skor
    $routes->get('matches/(:num)', 'Admin::matches/$1');
    $routes->post('update-score/(:num)', 'Admin::updateScore/$1');
    $routes->get('generate-next-round/(:num)/(:num)', 'Admin::generateNextRound/$1/$2');
});