<?php
// Allow Origin
header('Access-Control-Allow-Origin: '.$_SERVER[ 'HTTP_HOST' ]);

// APP PATH
define( 'APP_PATH', __DIR__ . '/' );
define( 'APP_URL', ( ( ( ! empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] !== 'off' ) ) ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );

// require
require_once APP_PATH . 'includes/loader.php';

// sanitize $_GET
$_GET = isset( $_GET ) ? clearNull( $_GET ) : array();

// read CSV and then write JSON
$csv = new CSV( pathJoin( DATA_PATH, '*' . DATA_TYPE ) );
$csv->search( $_GET );
$csv->output();