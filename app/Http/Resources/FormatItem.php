<?php
/**
 * Created by PhpStorm.
 * User: jiglant
 * Date: 3/1/19
 * Time: 12:34 PM
 */

namespace App\Http\Resources;


class FormatItem
{
    private $name;
    private $price;
    private $moneySign;

    public function __construct($name = '', $price = '', $moneySign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> moneySign = $moneySign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> moneySign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> moneySign ? 'Tzs ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_RIGHT);
        return "$left$right\n";
    }
}