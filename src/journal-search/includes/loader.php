<?php
/** config.php の読み込み */
require_once APP_PATH . 'config.php';

/** クラスローダーの実行 */
require APP_PATH . 'includes/library/ClassLoader.php';
$loader = new ClassLoader();
$loader->set( APP_PATH . 'includes/library' );
$loader->start();

/** その他ファイルの読み込み */
require_once APP_PATH . 'includes/functions.php';