<?php
    /*    "Системный администратор в офис"
            Системный администратор в
            Системный администратор
        'системный администратор'|'сисадмин'|'systems administrator' в офис
     ('системный'|'system'|'системний' & 'администратор')|'сисадмин'|'systems administrator' в офис
    */

//    "Системный администратор баз данных и безопасности"
//    "Системный администратор БД и безопасности"

//    find word
//    replace to synonyms
//

    class buildQuery
    {
        private $dic = [
            1 => ['системный администратор', 'сисадмин', 'systems administrator', 'DevOps engineer'],
            2 => ['баз данных', 'БД', 'database'],
            3 => ['безопасности', 'безпеки', 'security'],
            4 => ['администратор баз данных', 'адміністратор БД', 'администратор БД', 'database administrator', 'dba'],
            5 => ['системный', 'system', 'системный']
        ];

        private $wordToType = [
            'системный администратор'  => 1,
            'сисадмин'                 => 1,
            'systems administrator'    => 1,
            'DevOps engineer'          => 1,
            'баз данных'               => 2,
            'БД'                       => 2,
            'database'                 => 2,
            'безопасности'             => 3,
            'безпеки'                  => 3,
            'security'                 => 3,
            'администратор баз данных' => 4,
            'адміністратор БД'         => 4,
            'администратор БД'         => 4,
            'database administrator'   => 4,
            'dba'                      => 4,
            'системный'                => 5,
            'system'                   => 5,
            'системний'                => 5
        ];

        public $query = "";

        public function __construct($query)
        {
            $this->query = $query;
        }

        public function buildSearchQuery($line)
        {
            $words = $this->explodeByWord($line);
            $this->wordSelection($words);
        }

        public function wordSelection($words)
        {
            $line = implode(' ', $words);
            var_dump($line);
            die;

            $type = $this->findTypeInDic($line);

            if ($type) {
                $this->replaceQuery($type, $line);
            } else {
                array_pop($words);
                $this->wordSelection($words);
            }
        }

        public function replaceQuery($type, $line)
        {
            $words = implode('|', $this->dic[$type]);
            $this->query = str_replace($line, '(' . $words . ')', $this->query);
        }

        public function findTypeInDic($word)
        {
            return isset($this->wordToType[$word]) ? $this->wordToType[$word] : false;
        }

        public function explodeByWord($line)
        {
            preg_match_all("#([A-Za-zА-Яа-я][A-Za-zА-Яа-я]+) #u", $line . ' ', $found);

            return $found[1];
        }
    }

    $line = "Системный администратор в офис";
    $q = new buildQuery($line);
    $q->buildSearchQuery($line);

    var_dump($q->query);