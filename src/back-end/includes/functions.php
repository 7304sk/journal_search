<?php
/** 簡易リファラチェック（CRSF対策） */
function refererCheck( $app_domain ) {
    $domains = array_merge( [ $_SERVER[ 'SERVER_NAME' ] ], $app_domain );
    if( empty( $_SERVER[ 'HTTP_REFERER' ] ) ) AppError::id( 1 );
    $url_arr = parse_url( $_SERVER[ 'HTTP_REFERER' ] );
    if ( ! in_array( $url_arr[ 'host' ], $domains, true ) ) AppError::id( 2 );
}

/** ヌルバイトインジェクション対策 */
function clearNull( $arr ) {
    return is_array( $arr ) ? array_map( 'clearNull', $arr ) : str_replace( "\0", '', $arr );
}

function pathJoin($dir, $file) {
    return rtrim($dir, '\\/') . DIRECTORY_SEPARATOR . $file;
}