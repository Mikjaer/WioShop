<?php

function verify_pass( $pass )
{
    if ( preg_match( '/[a-z]/', $pass ) == 0 ) return false;
    if ( preg_match( '/[A-Z]/', $pass ) == 0 ) return false;
    if ( preg_match( '/[0-9]/', $pass ) == 0 ) return false;
    return true;
}

function rand_pass()
{
    do {
        $ret = "";
        for ( $i = 1; $i <= 16; $i++ )
        {
            $c = rand( 1, 60 );

            if ( $c < 10 )
                $ret .= (String)$c;
            else if ( $c < 35 )
                $ret .= chr( 65 - 10 + $c );
            else 
                $ret .= chr( 97 - 35 + $c );
        }
    } while ( !verify_pass( $ret ) );
    return $ret;
}

