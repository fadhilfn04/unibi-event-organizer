<?php

if(!function_exists('normalize_date_format'))
{
    function normalize_date_format($from_format, $date)
    {
        if(!is_null($date) && $date != '' && $datetime = \DateTime::createFromFormat($from_format, $date))
        {
            return $datetime->format('Y-m-d');
        }
    }
}

if(!function_exists('normalize_hour_format'))
{
    function normalize_hour_format($from_format, $date)
    {
        if(!is_null($date) && $date != '' && $datetime = \DateTime::createFromFormat($from_format, $date))
        {
            return $datetime->format('H:i:s');
        }
    }
}

if(!function_exists('to_date_format'))
{
    function to_date_format($to_format, $date)
    {
        if(!is_null($date) && $date != '' && $datetime = new \DateTime($date))
        {
            return $datetime->format($to_format);
        }
    }
}

if(!function_exists('id_date_format'))
{
    function id_date_format($date)
    {
        $datetime = new \DateTime($date);
        $day_of_week = (int) $datetime->format('w');
        $month = (int) $datetime->format('m');
        $month_list = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        $day_list = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];
        return $day_list[$day_of_week] . ', '
            . $datetime->format('d ')
            . $month_list[$month] . ' '
            . $datetime->format('Y');
    }
}

if(!function_exists('generate_date_range'))
{
    function generate_date_range($from_date, $to_date)
    {
        $from_datetime = new \DateTime($from_date);
        $to_datetime = new \DateTime($to_date);
        $dates = [];
        while($from_datetime <= $to_datetime) {
            $interval = new \DateInterval('P1D');
            $dates[] = $from_datetime->format('Y-m-d');
            $from_datetime->add($interval);
        }

        return $dates;
    }
}