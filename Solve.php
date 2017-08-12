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
    private $path;


    public function __construct()
    {
        $this->cities = [];
        $this->distances = [];
        $this->path = [];
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

    /**
     * For all cities, calculate distances.
     */
    public function calculateDistances()
    {
        // calculate distances between cities
        $x = $this->getCities();
        $y = $this->getCities();

        foreach($x as $city1 => $gps1) {
            foreach ($y as $city2 =>$gps2) {
                if($city1 <> $city2) {
                    $distance = $this->pythagoras($gps1, $gps2);
                    $this->distances[$city1][$city2] = $distance;
                }
            }

            // sort distances min to max, so the first item is the next city to visit.
            asort($this->distances[$city1]);
       }

    }


    /*
     * there are too many algorithms to solve the salesman travel problem. I choose select the sort path
     * iterative.
     *
     */
    public function sort()
    {
        // we start in Beijing

        $myCities = $this->getDistances();
        $actual = "Beijing";

        $count = 0;

        do {

            $this->path[$actual] = $this->cities[$actual];
            $keys = array_keys($myCities[$actual]);

            $myCities = $this->clear($myCities, $actual);

            $actual = $keys[0];

            $count++;

        } while (!empty($myCities) && $count < count($this->cities) -1);
    }


    /*
    * Clear all references of actual city in list.
    */
    private function clear($list, $actual)
    {
        unset ($list[$actual]);
        foreach($list as $key =>  $tmp) {
                unset($list[$key][$actual]);
        }

        return $list;
    }

    /**
     * overrides __toString function to print solution to screen
     */
    public function __toString()
    {
        $result = "\nList of Cities:\n";

        foreach ($this->path as $city => $gps) {
            $result.= sprintf("%s %f %f\n", $city, $gps['lat'], $gps['lon']);
        }

        return $result;
    }


    private function pythagoras($point1, $point2)
    {
        // my pythagoras function has errors, so i use an other function find in:
        // http://assemblysys.com/es/calculo-de-la-distancia-en-funcion-de-la-latitud-y-longitud-en-php/
        $h = $this->distanceCalculation($point1['lat'], $point1['lon'], $point2['lat'], $point2['lon']);

        return $h;
    }

    private function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Cálculo de la distancia en grados
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

        // Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
        switch($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
                break;
            case 'nmi':
                $distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
        }
        return round($distance, $decimals);
    }



}


/* main */

$solve = new Solve();
$solve->readFile();
$solve->calculateDistances();
$solve->sort();
printf($solve);