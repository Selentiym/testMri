<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 20:42
 */
class TestClass implements iFactorable, iTimeFactorable {
    public $a;
    public $b;
    public $c;
    public $date;

    public $weight;
    public function __construct($t,$a, $b, $c, $weight = 1) {
        $this -> date = new DateTime(date('c',strtotime($t)));
        $this -> a = 'a'.$a;
        $this -> b = 'b'.$b;
        $this -> c = 'c'.$c;
        $this -> weight = $weight;
    }

    /**
     * @return DateTime
     * @throws StatisticalException
     */
    public function getDateTime() {
        if (!$this -> date) {
            throw new StatisticalException('DateTime is not specified in test class');
        }
        return $this -> date;
    }
}