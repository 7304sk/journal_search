<?php
/** クリックジャッキング対策  */
header('X-FRAME-OPTIONS: SAMEORIGIN');

define( 'APP_PATH', __DIR__ . '/' );
define( 'APP_URL', ( ( ( ! empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] !== 'off' ) ) ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );

require_once APP_PATH . 'config.php';
require APP_PATH . 'includes/loader.php';

refererCheck();

$csv = new CSV( pathJoin( DATA_PATH, '*' . DATA_TYPE ) );
$csv->search();
$csv->output();