<?php
function catcherror ($test){
    if(is_null($test)){
        throw new Exception('You should login');
    }

}