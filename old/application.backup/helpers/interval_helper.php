<?php

if(!function_exists('age_format'))
{
    function age_format($datetime)
    {
        $date_object = new DateTime($datetime);
        $interval = date_diff($date_object, new DateTime());
        $sequence = ['y' => 'Tahun', 'm' => 'Bulan', 'd' => 'Hari', 'h' => 'Jam', 'i' => 'Menit', 's' => 'Detik'];
        foreach($sequence as $key => $item)
        {
            $val = abs($interval->$key);
            if($val > 0)
            {
                return $val . ' ' . $item;
            }
        }
    }
}