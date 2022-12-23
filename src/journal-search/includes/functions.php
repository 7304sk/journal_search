<?php
/** ヌルバイトインジェクション対策 */
function clearNull( $arr ) {
    return is_array( $arr ) ? array_map( 'clearNull', $arr ) : str_replace( "\0", '', $arr );
}

function pathJoin($dir, $file) {
    return rtrim($dir, '\\/') . DIRECTORY_SEPARATOR . $file;
}