<?php
use \Ogursan\CityMorpher;

return [
    'ярославль' => CityMorpher::EXCEPTION_GENDER_MALE,
    'елец'           => array(
        CityMorpher::CASE_NOM => 'Елец',
        CityMorpher::CASE_GEN => 'Ельца',
        CityMorpher::CASE_DAT => 'Ельцу',
        CityMorpher::CASE_ACC => 'Елец',
        CityMorpher::CASE_INS => 'Ельцем',
        CityMorpher::CASE_ABL => 'Ельце',
    ),
    'великие луки'   => array(
        CityMorpher::CASE_NOM => 'Великие Луки',
        CityMorpher::CASE_GEN => 'Великих Луков',
        CityMorpher::CASE_DAT => 'Великим Лукам',
        CityMorpher::CASE_ACC => 'Великие Луки',
        CityMorpher::CASE_INS => 'Великими Луками',
        CityMorpher::CASE_ABL => 'Великих Луках',
    ),
    'щучье'          => array(
        CityMorpher::CASE_NOM => 'Щучье',
        CityMorpher::CASE_GEN => 'Щучьего',
        CityMorpher::CASE_DAT => 'Щучьему',
        CityMorpher::CASE_ACC => 'Щучье',
        CityMorpher::CASE_INS => 'Щучьим',
        CityMorpher::CASE_ABL => 'Щучьем'
    ),
];