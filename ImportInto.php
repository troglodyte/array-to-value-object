<?php

namespace ArrayToValueObject;

class ImportInto
{
    /**
     * Usage: 
     * $userEntity = new UserEntity(); // some kind of value obj with setters
     * $obj = ImportInto::import($userEntity, ['username'=>'john']);
     *
     * @param $valueObject
     * @param array $data
     * @return mixed
     */
    public static function import(&$valueObject, array $data)
    {
        $className = get_class($valueObject);

        // Get methods
        $methods = array_map(function ($method) {
            return strtolower($method);
        }, get_class_methods($className));

        // Filter out all but setters
        $methods = array_filter($methods, function ($method) {
            return substr($method, 0,3) == 'set';
        });

        // Call the setters
        foreach ($data as $k => $v) {
            $mKey = 'set' . strtolower(str_replace([' ', '_'], '', $k));
            if (in_array($mKey, $methods) && !is_null($v)) {
                $valueObject->$mKey($v);
            }
        }
        return $valueObject;
    }
}
