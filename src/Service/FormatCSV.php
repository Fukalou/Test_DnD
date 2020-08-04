<?php
namespace App\Service;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Console\Descriptor\Descriptor;

class FormatCSV {

    public function CSVToArray($input)
    {
        $csv_string = file_get_contents($input);

        $delimiter = ';';
        $enclosure = '"';
        $lines = explode("\n", $csv_string);
        $headers = str_getcsv(array_shift($lines),$delimiter, $enclosure);
        $array = array();
        foreach ($lines as $line){
            $csv = str_getcsv($line, $delimiter, $enclosure);
            $array[] = array_combine($headers, $csv);
        }

        return $array;
    }

    public function formateDate(array $array):array
    {
        $arrayFormate = array();

        foreach($array as $lignes)
        {
            if(key_exists('created_at', $lignes))
            {
                $date = new DateTime($lignes['created_at']);
                $dateFormate = date_format($date, 'l, d-M-Y H:i:s e');
                $lignes['created_at'] = $dateFormate;
            }
            $arrayFormate[] = $lignes;
        }

        return $arrayFormate;
    }

    public function formateDescription(array $array): array
    {
        //  var_dump($array[1]['description']);
        echo "Hello world". '<br />' ."Hello world";

        return $arrayFormate;
    }

    public function formatePrice(array $array):array
    {
        $arrayFormate = array();

        foreach($array as $lignes)
        {
            if(key_exists('price', $lignes) && key_exists('currency', $lignes))
            {
                $price = $lignes['price'];
                $currency = $lignes['currency'];

                $priceFormate = number_format(round($price, 2), 2, ',', ' ') . $currency;

                unset($lignes['currency']);

                $lignes['price'] = $priceFormate;
            }
            $arrayFormate[] = $lignes;
        }

        return $arrayFormate;
    }

    public function formateEnable(array $array):array
    {
        $arrayFormate = array();

        foreach($array as $lignes)
        {
            if(key_exists('is_enabled', $lignes))
            {
                $is_enabled = $lignes['is_enabled'];

                switch ($is_enabled) {
                    case 0:
                        $is_enabled = 'Enable';
                        break;
                    case 1:
                        $is_enabled = 'Disable';
                        break;
                    default:
                        $is_enabled = 'NaN';
                }

                $lignes['is_enabled'] = $is_enabled;
            }
            $lignes['Status'] = $lignes['is_enabled'];
            unset($lignes['is_enabled']);
            $arrayFormate[] = $lignes;
        }

        return $arrayFormate;
    }

    public function formateTitle(array $array):array
    {
        $arrayFormate = array();

        foreach($array as $lignes)
        {
            if(key_exists('title', $lignes))
            {
                $title = $lignes['title'];

                $title = str_replace(' ', '-', $title);
                $title = str_replace(',', '', $title);
                $lignes['slug'] = $lignes['title'];
                $lignes['slug'] = $title;

                unset($lignes['title']);
            }
            $arrayFormate[] = $lignes;
        }

        return $arrayFormate;
    }

}