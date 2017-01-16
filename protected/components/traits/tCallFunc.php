<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.01.2017
 * Time: 10:01
 */
trait tCallFunc {
    protected $_evaluatedAttributes = [];
    //protected static $_allowed = [];

    /**
     * @param string $name of the attribute
     * @return mixed
     * @throws AccessException
     */
    public function getAttribute($name) {
        if (!$this -> _evaluatedAttributes[$name]) {
            if (!$this -> _isAllowedToEvaluate($name)) {
                throw new AccessException("$name is not allowed for evaluation!");
            }
            $this -> _evaluatedAttributes[$name] = $this -> evaluateAttribute($name);
        }
        return $this -> _evaluatedAttributes[$name];
    }

    protected function evaluateAttribute($name) {
        return $this -> evaluate($this -> $name);
    }
    /**
     * Finds out whether argument is any kind of a callable and evaluates it
     * If the argument is not a callable, it will returned unchanged
     * @param callable|mixed $param
     * @return mixed
     */
    /*protected function evaluate($param) {
        //Если просто анонимная функция, то вызываем ее, передавая себя внутрь
        if (is_callable($param)) {
            return $param($this);
        }
        $f = [$this, $param];
        //Если метод объекта, то просто вызываем
        if (is_callable($f, false)) {
            return call_user_func($f);
        }
        $f = [get_class($this), $param];
        //Если статический метод класса, то вызываем
        if (is_callable($f)) {
            return call_user_func($f);
        }
        return $param;
    }*/
    protected function evaluate($param) {
        //Если просто анонимная функция, то вызываем ее, передавая себя внутрь
        if (is_callable($param)) {
            return $param($this);
        }
        $f = [$this, $param];
        try {
            //Если метод не существует, то полетит исключение.
            new ReflectionMethod($f[0],$f[1]);
            return call_user_func($f);
        } catch (ReflectionException $e) {
            return $param;
        }
    }
}