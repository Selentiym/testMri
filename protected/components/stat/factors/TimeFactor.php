<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.01.2017
 * Time: 22:45
 */
class TimeFactor extends aFactor implements iCallFunc{
    public $name = 'Время';
    use tCallFunc;
    public $format;
    /**
     * TimeFactor constructor.
     * @param int $timeRange - how long you would like your time interval to be in seconds
     */
    private $_interval;

    /**
     * TimeFactor constructor.
     * @param $interval
     * @param string|callback|null $format
     * @throws AccessException
     * @throws StatisticalException
     */
    public function __construct($interval, $format = null) {
        if ($interval < 1) {
            throw new StatisticalException("Cannot split time into intervals fewer than one second. Wanted $interval seconds.");
        }
        $this -> _interval = $interval;
        $this -> format = $format ? $format : 'H:i';
        $time = new DateTime();
        $int = new DateInterval("PT{$interval}S");
        //$int -> s = $interval;
        $time -> setTime(0,0,0);
        $s = 0;
        while($s < 60 * 60 * 24) {
            $this -> addNewPossibleValue($time -> format($this -> getAttribute('format')));
            $time -> add($int);
            $s += $interval;
        }
    }

    /**
     * @param iFactorable $obj
     * @return bool
     */
    public function checkApplicability(iFactorable $obj) {
        return ($obj instanceof iTimeFactorable);
    }

    /**
     * @param iTimeFactorable $obj
     * @return string
     * @throws AccessException
     */
    public function applyCore(iTimeFactorable $obj) {
        $unix = $obj -> getDateTime() -> getTimestamp();
        $int = new DateInterval("PT".$unix % $this -> _interval."S");
        $int -> invert = 1;
        return $obj -> getDateTime() -> add($int) -> format($this -> getAttribute('format'));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function _isAllowedToEvaluate($name) {
        return $name == 'format';
    }
}