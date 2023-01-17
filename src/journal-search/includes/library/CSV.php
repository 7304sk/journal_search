<?php

class CSV {
    public $data_dir;
    public $filenames;
    public $datas;
    public $result;

    public function __construct( $data_dir ) {
        $this->data_dir = $data_dir;
        $this->filenames = glob( $data_dir );
        $this->read();
    }

    private function read() {
        global $csv_data_headers;
        $arr = [];
        $data_order = [];

        foreach ( $this->filenames as $filename ) {
            # read CSV
            $file = new SplFileObject( $filename );
            $file->setFlags( SplFileObject::READ_CSV );
            # set lines
            foreach ( $file as $i => $l ) {
                if ( DATA_HAS_HEADER ) {
                    if ( $i == 0 ) {
                        $data_order = [];
                        foreach ( $l as $j => $v ) {
                            $data_order[$j] = array_search( $v, $csv_data_headers );
                        }
                    } else {
                        $sorted = [];
                        foreach ( $data_order as $order ) {
                            foreach ( $l as $j => $v ) {
                                if ( $order == $j ) {
                                    $sorted[$csv_data_headers[$j]] = $v;
                                    unset( $l[$j] );
                                    break;
                                }
                            }
                        }
                        $arr[] = $sorted;
                    }
                } else {
                    foreach ( $csv_data_headers as $j => $v ) {
                        $arr[$v] = $l[$j];
                    }
                }
            }
        }
        $this->datas = $arr;
    }

    public function search( $dict ) {
        $result = $this->datas;
        foreach ( $this->datas as $i => $row ) {
            $drop = false;
            foreach ( $dict as $key => $val ) {
                if( !empty( $val ) ) if( stristr( $row[$key], $val ) === false ) $drop = true;
            }
            if ( $drop ) unset( $result[$i] );
        }
        $this->result = array_values( $result );
    }

    public function output() {
        echo json_encode( $this->result, JSON_UNESCAPED_UNICODE );
    }
}