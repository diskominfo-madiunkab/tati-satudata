<?php

namespace App\Util;

class Rupiah
{
    public static function terbilang($nominal)
    {
        $nominal = abs($nominal);
        $angka = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam',
            'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

        if ($nominal < 12) {
            return ' ' . $angka[$nominal];
        }

        if ($nominal < 20) {
            return rupiah::terbilang($nominal - 10) . ' belas';
        }

        if ($nominal < 100) {
            return rupiah::terbilang($nominal / 10) . ' puluh'
                . rupiah::terbilang($nominal % 10);
        }

        if ($nominal < 200) {
            return ' seratus' . rupiah::terbilang($nominal - 100);
        }

        if ($nominal < 1000) {
            return rupiah::terbilang($nominal / 100) . ' ratus'
                . rupiah::terbilang($nominal % 100);
        }

        if ($nominal < 2000) {
            return ' seribu' . rupiah::terbilang($nominal - 1000);
        }

        if ($nominal < 1000000) {
            return rupiah::terbilang($nominal / 1000) . ' ribu'
                . rupiah::terbilang($nominal % 1000);
        }

        if ($nominal < 1000000000) {
            return rupiah::terbilang($nominal / 1000000) . ' juta'
                . rupiah::terbilang($nominal % 1000000);
        }

        if ($nominal < 1000000000000) {
            return rupiah::terbilang($nominal / 1000000000) . ' milyar'
                . rupiah::terbilang(fmod($nominal, 1000000000));
        }

        if ($nominal < 1000000000000000) {
            return rupiah::terbilang($nominal / 1000000000000) . ' trilyun'
                . rupiah::terbilang(fmod($nominal, 1000000000000));
        }
    }
}
