<?php

namespace Ogursan;

/**
 * Class CityMorpher
 *
 * Класс, склоняющий города по падежам. С очень большой вероятностью требует основательной доработки, но на данном этапе уже неплохо справляется
 * nom - Именительный
 * gen - Родительный
 * dat - Дательный
 * acc - Винительный
 * ins - Творительный
 * abl - Предложный
 */
class CityMorpher
{
    const CASE_NOM = 1; // Именительный
    const CASE_GEN = 2; // Родительный
    const CASE_DAT = 3; // Дательный
    const CASE_ACC = 4; // Винительный
    const CASE_INS = 5; // Творительный
    const CASE_ABL = 6; // Предложный

    const EXCEPTION_GENDER_MALE = 'm'; // Рассматривается как слово мужского рода

    private $_cases = array(
        self::CASE_NOM,
        self::CASE_GEN,
        self::CASE_DAT,
        self::CASE_ACC,
        self::CASE_INS,
        self::CASE_ABL,
    );

    private $endings = array(
        self::CASE_GEN => array(
            'f' => array(
                'ая' => 'ой',
                'яя' => 'ей',
                'ка' => 'ки',
                'га' => 'ги',
                'а'  => 'ы',
                'я'  => 'и',
                'ь'  => 'и',
            ),
            'u' => array(
                'ое' => 'ого',
                'ее' => 'его',
                'ие' => 'их',
                'ые' => 'ых',
                'о'  => 'а',
                'е'  => 'я',
            ),
            'm' => array(
                'ой'      => 'ого',
                'ый'      => 'ого',
                'ий'      => 'его',
                'ай'      => 'ая',
                'ец'      => 'ца',
                'ок'      => 'ка',
                'ь'       => 'я',
                'и'       => 'ей',
                'ы'       => 'ов',
                'default' => 'а',
            ),
        ),
        self::CASE_DAT => array(
            'f' => array(
                'ая' => 'ой',
                'яя' => 'ей',
                'ка' => 'ке',
                'га' => 'ге',
                'а'  => 'е',
                'я'  => 'е',
                'ь'  => 'и',
            ),
            'u' => array(
                'ое' => 'ому',
                'ее' => 'ему',
                'ие' => 'им',
                'ые' => 'ым',
                'о'  => 'у',
                'е'  => 'ю',
            ),
            'm' => array(
                'ой'      => 'ому',
                'ый'      => 'ому',
                'ий'      => 'ему',
                'ай'      => 'аю',
                'ец'      => 'цу',
                'ок'      => 'ку',
                'ь'       => 'ю',
                'и'       => 'ям',
                'ы'       => 'ам',
                'default' => 'у'
            ),
        ),
        self::CASE_ACC => array(
            'f' => array(
                'ая' => 'ую',
                'яя' => 'юю',
                'ка' => 'ку',
                'га' => 'гу',
                'а'  => 'у',
                'я'  => 'ю',
                'ь'  => 'ь',
            ),
            'u' => array(
                'ое' => 'ое',
                'ее' => 'ее',
                'ие' => 'ие',
                'ые' => 'ые',
                'о'  => 'о',
                'е'  => 'е',
            ),
            'm' => array(
                'ой'      => 'ой',
                'ый'      => 'ый',
                'ий'      => 'ий',
                'ай'      => 'ай',
                'ец'      => 'ец',
                'ок'      => 'ок',
                'ь'       => 'ь',
                'и'       => 'и',
                'ы'       => 'ы',
                'default' => '',
            ),
        ),
        self::CASE_INS => array(
            'f' => array(
                'ая' => 'ой',
                'яя' => 'ей',
                'ка' => 'кой',
                'га' => 'гой',
                'а'  => 'ой',
                'я'  => 'ей',
                'ь'  => 'ью',
            ),
            'u' => array(
                'ое' => 'им',
                'ее' => 'им',
                'ие' => 'ими',
                'ые' => 'ыми',
                'о'  => 'ым',
                'е'  => 'ем',
            ),
            'm' => array(
                'ой'      => 'ым',
                'ый'      => 'ым',
                'ий'      => 'им',
                'ай'      => 'аем',
                'ец'      => 'цом',
                'ок'      => 'ком',
                'ь'       => 'ем',
                'и'       => 'ями',
                'ы'       => 'ами',
                'default' => 'ом',
            ),
        ),
        self::CASE_ABL => array(
            'f' => array(
                'ая' => 'ой',
                'яя' => 'ей',
                'ка' => 'ке',
                'га' => 'ге',
                'а'  => 'е',
                'я'  => 'е',
                'ь'  => 'и',
            ),
            'u' => array(
                'ое' => 'ом',
                'ее' => 'ем',
                'ие' => 'их',
                'ые' => 'ых',
                'о'  => 'е',
                'е'  => 'е',
            ),
            'm' => array(
                'ой'      => 'ом',
                'ый'      => 'ом',
                'ий'      => 'ем',
                'ай'      => 'ае',
                'ец'      => 'це',
                'ок'      => 'ке',
                'ь'       => 'е',
                'и'       => 'ях',
                'ы'       => 'ах',
                'default' => 'е',
            ),
        ),
    );

    /**
     * @var array - города-исключения
     */
    private $exceptions = array(
        'елец'           => array(
            self::CASE_NOM => 'Елец',
            self::CASE_GEN => 'Ельца',
            self::CASE_DAT => 'Ельцу',
            self::CASE_ACC => 'Елец',
            self::CASE_INS => 'Ельцем',
            self::CASE_ABL => 'Ельце',
        ),
        'великие луки'   => array(
            self::CASE_NOM => 'Великие Луки',
            self::CASE_GEN => 'Великих Луков',
            self::CASE_DAT => 'Великим Лукам',
            self::CASE_ACC => 'Великие Луки',
            self::CASE_INS => 'Великими Луками',
            self::CASE_ABL => 'Великих Луках',
        ),
        'щучье'          => array(
            self::CASE_NOM => 'Щучье',
            self::CASE_GEN => 'Щучьего',
            self::CASE_DAT => 'Щучьему',
            self::CASE_ACC => 'Щучье',
            self::CASE_INS => 'Щучьим',
            self::CASE_ABL => 'Щучьем'
        ),

    );

    function __construct()
    {
        $this->exceptions = require_once 'exceptions.php';
        mb_internal_encoding('utf8');
    }

    /**
     * @param $cityName - Название города в именительном падеже
     * @param int $case - Код падежа
     *
     * @return string
     * @throws \Exception
     */
    public function getCase($cityName, $case = self::CASE_NOM)
    {
        if (isset($this->exceptions[mb_strtolower($cityName)])) {
            if (is_array($this->exceptions[mb_strtolower($cityName)])) {
                return $this->exceptions[mb_strtolower($cityName)][$case];
            }
        }

        if (preg_match('~^(.+)(-на-.+)$~iu', $cityName, $match)) {
            return $this->getWordInCase($match[1], $case) . $match[2];
        }

        if (trim($cityName) == '') {
            return '';
        }

        $case = strtolower($case);

        // Разбиваем название на слова
        $words = explode(' ', $cityName);
        foreach ($words as &$word) {
            $word = $this->getWordInCase($word, $case);
        }
        unset($word);

        return implode(' ', $words);
    }

    /**
     * Возвращает условный род города
     * m - мужской
     * f - женский
     * u - средний
     * l - имя записано латинскими буквами, не склоняется по правилам русского языка
     *
     * @param $word - название города
     *
     * @return string
     */
    private function getGender($word)
    {
        $word = mb_strtolower($word);

        if (isset($this->exceptions[$word]) && is_string($this->exceptions[$word])) {
            return $this->exceptions[$word];
        }

        $lastLetter = mb_substr($word, -1, 1);
        if (preg_match('/[a-z]/i', $lastLetter)) {
            return 'l';
        }

        $femaleEndings  = array('ь', 'а', 'я');
        $unknownEndings = array('е', 'о');

        if (in_array($lastLetter, $femaleEndings)) {
            return 'f';
        }

        if (in_array($lastLetter, $unknownEndings)) {
            return 'u';
        }

        return 'm';
    }

    /**
     * Возвращает слово в заданном падеже
     *
     * @param $word
     * @param $case
     *
     * @return string
     * @throws
     */
    private function getWordInCase($word, $case = self::CASE_NOM)
    {
        if ($case == self::CASE_NOM) {
            return $word;
        }

        if (!in_array($case, $this->_cases)) {
            throw new \Exception('Unknown case «' . $case . '»');
        }

        $lowerWord = mb_strtolower($word);

        $gender = $this->getGender($word);

        if ($gender == 'l') {
            return $word;
        }

        $ending = mb_substr($lowerWord, -2, 2);
        if (isset($this->endings[$case][$gender][$ending])) {
            return mb_substr($word, 0, -2) . $this->endings[$case][$gender][$ending];
        }

        $ending = mb_substr($lowerWord, -1, 1);
        if (isset($this->endings[$case][$gender][$ending])) {
            return mb_substr($word, 0, -1) . $this->endings[$case][$gender][$ending];
        }

        if ($gender == 'm') {
            return $word . $this->endings[$case][$gender]['default'];
        }

        return $word;
    }
}
