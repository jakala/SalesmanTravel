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
    private $distances;


    public function __construct()
    {
        $this->cities = [];
        $this->distances = [];
    }


    public function getCities()
    {
        return $this->cities;
    }

    public function getDistances()
    {
        return $this->distances;
    }

    /** read the cities.txt file and put info in associative array, with city name as key and
     * two parameters lat, lon, like
     *
     *    "city" => ["lat" => 0.0, "lon"=> 9.3]
     *
     * returns: $this
     */
    public function readFile()
    {
        // file is in root directory.
        $file = __DIR__.'/cities.txt';

        $content = file($file, FILE_IGNORE_NEW_LINES);

        foreach ($content as $city) {

            $tmp = explode(' ', $city);

            if(isset($tmp[3])) {
                // case cities with two words in name
                $key = $tmp[0]." ".$tmp[1];
                $lat = $tmp[2];
                $lon = $tmp[3];
            } else {
                // city with 1 word in name
                $key = $tmp[0];
                $lat = $tmp[1];
                $lon = $tmp[2];
            }

            $key = trim($key);

            $this->cities[$key] = ['lat' => floatval($lat), 'lon' => floatval($lon)];
        }

        return $this;
    }

    public function calculateDistances()
    {
        // calculate distances between cities
        $x = $this->getCities();
        $y = $this->getCities();

        foreach($x as $city1 => $gps1) {
            foreach ($y as $city2 =>$gps2) {
                $distance = $this->pythagoras($gps1, $gps2);
                $this->distances[$city1."-".$city2] =  $distance;
            }

        }

    }

    public function __toString()
    {
        $result = "List of Cities:\n";

        foreach ($this->cities as $city => $gps) {
            $result.= sprintf("%s %f %f\n", $city, $gps['lat'], $gps['lon']);
        }

        return $result;
    }



    private function pythagoras($point1, $point2)
    {
        $cat1 = $point2['lat'] - $point1['lat'];
        $cat2 = $point2['lon'] - $point2['lon'];

        $h = sqrt( ($cat1 * $cat1) + ($cat2 * $cat2)  );

        return $h;
    }
}

$solve = new Solve();
$solve->readFile();
$solve->calculateDistances();

printf($solve);