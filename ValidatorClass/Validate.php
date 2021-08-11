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

    public static function EmailFormat($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            echo $emailErr;
        }
    }

    public static function CheckEmpty($actual_name, $field){
        if (empty($field)){
            $Capital=ucwords($actual_name);
            $fieldErr="$Capital is required";
            echo $fieldErr;
            return false;
        }
        else{
            return true;
        }
    }
}



