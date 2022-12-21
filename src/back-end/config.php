<?php

define('DIRECTORY_SEPARATOR', '/');

# データファイルの拡張子
define('DATA_TYPE', 'csv');
# データファイルの保存ディレクトリ
define('DATA_PATH', '/var/www/html/data');
# データファイルの1行目がヘッダー情報であるか？
define('DATA_HAS_HEADER', true);

# データファイルが保持するヘッダー情報（順番もこれに倣う）
$csv_data_headers = ['title_ja','title_en','authors','category','publish_date','vol','no','pp1','pp2','pdf'];