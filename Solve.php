<?php
/**
 * Created by PhpStorm.
 * User: jakala
 * Date: 12/08/17
 * Time: 14:45
 */
namespace App;

class Solve
{
    private $cities;

    public function __construct()
    {
        $this->cities = [];
    }


    public function getCities()
    {
        return $this->cities;
    }


    public function readFile()
    {
        // file is in root directory.
        $file = __DIR__.'/cities.txt';

        try {
            $content = file($file, FILE_IGNORE_NEW_LINES);


            foreach ($content as $city) {
                list($city, $lat, $lon) = explode(' ', $city);
                $this->cities[$city] = ['lat' => floatval($lat), 'lon' => floatval($lon)];
            }
        } catch(\Exception $e) {
            var_dump($e); die();
        }


        return $this;
    }

}