<?php

class Validate{

    /**
     *
     * @param $company_data
     *
     * @return false|string
     */
    public static function EmailValidation($company_data){
        if (count($company_data) > 0) {
            return json_encode(array("Message" => "Email address already exists"));
        } else {
            return json_encode(array("Message" => ""));
        }
    }
}