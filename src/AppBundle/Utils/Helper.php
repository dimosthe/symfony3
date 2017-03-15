<?php
namespace AppBundle\Utils;

class Helper
{
    public function checkRequestFields(array $fields, array $request)
    {
        foreach($fields as $value)
        {
            if(empty($request[$value]))
            {
                return false;
            }
        }

        return true;
    }
}

?>