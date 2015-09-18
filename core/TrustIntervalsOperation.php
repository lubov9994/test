<?php

/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 06.09.2015
 * Time: 19:45
 */

class TrustIntervalsOperation
{
    public $intervals;
    public $countIntervals;

    public function __construct(  ){
//        $this->countIntervals = count ( $arrInterval );
//        $this->intervals = $arrInterval;
    }

    public function addInterval( $inter1, $inter2 ) {
        $res = array( $inter1[0]+$inter2[0], $inter1[1]+$inter2[1] );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    public function subInterval( $inter1, $inter2 ) {
        $res = array( $inter1[0]+$inter2[1], $inter1[1]+$inter2[0] );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in sub operation";
        }
        return -1;
    }

    //Представлення чіткого числа у вигляді інтервалу довіри
    public function clearNumbAsInetval( $numb ) {
        $res = array( $numb, $numb );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in clearNumbAsInetval operation";
        }
        return -1;
    }

    //Відображення інтервалу довіри
    public function mirrorInetval( $inter ) {
        $res = array( -$inter[1], -$inter[0] );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in mirrorInetval operation";
        }
        return -1;
    }

    // Множення інтервалів довіри
    public function multInterval( $inter1, $inter2 ) {
        $res = array( $this->minMultCombination( $inter1, $inter2 ), $this->maxMultCombination( $inter1, $inter2 ) );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    //Ділення інтервалів довіри
    public function divInterval( $inter1, $inter2 ) {
        $res = array( $this->minDivCombination( $inter1, $inter2 ), $this->maxDivCombination( $inter1, $inter2 ) );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    //Інверсія інтервалу довіри
    public function inverseInetval( $inter ) {
        $res = array( 1 / $inter[1], 1 / $inter[0] );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    public function multPositivConst( $inter, $k ) {
        $res = array( $k * $inter[0], $k * $inter[1] );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    public function divPositivConst( $inter, $k ) {
        $res = $this-> multPositivConst( $inter, 1 / $k );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in add operation";
        }
        return -1;
    }

    //Знаходження максимуму двох інтервалів
    public function maxInterval( $inter1, $inter2 ) {
        $a = ( $inter1[0] < $inter2[0] ) ?  $inter2[0] :  $inter1[0];
        $b = ( $inter1[1] < $inter2[1] ) ?  $inter2[1] :  $inter1[1];

        $res = array( $a, $b );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in maxInterval operation";
        }
        return -1;
    }

    //Знаходження min двох інтервалів
    public function minInterval( $inter1, $inter2 ) {
        $a = ( $inter1[0] > $inter2[0] ) ?  $inter2[0] :  $inter1[0];
        $b = ( $inter1[1] > $inter2[1] ) ?  $inter2[1] :  $inter1[1];

        $res = array( $a, $b );
        if ( $this->revisInterval( $res ) ){
            return $res;
        } else {
            echo "Error in minInterval operation";
        }
        return -1;
    }

    private function revisInterval( $inter ){
        if( $inter[0] <= $inter[1]){
            return true;
        }

        return false;
    }

    private function minMultCombination( $a, $b ){
        $min = $a[0] * $b[0];
        for( $i = 0; $i < count( $a ); $i++ ){
            for( $j = 0; $j < count( $b ); $j++ ) {
                if( $a[ $i ] * $b[ $j ] < $min ){
                    $min = $a[ $i ] * $b[ $j ];
                }
            }
        }

        return $min;
    }

    private function maxMultCombination( $a, $b ){
        $max = $a[0] * $b[0];
        for( $i = 0; $i < count( $a ); $i++ ){
            for( $j = 0; $j < count( $b ); $j++ ) {
                if( $a[ $i ] * $b[ $j ] > $max ){
                    $max = $a[ $i ] * $b[ $j ];
                }
            }
        }

        return $max;
    }

    private function minDivCombination( $a, $b ){
        $min = $a[0] / $b[0];
        for( $i = 0; $i < count( $a ); $i++ ){
            for( $j = 0; $j < count( $b ); $j++ ) {
                if( $a[ $i ] / $b[ $j ] < $min ){
                    $min = $a[ $i ] / $b[ $j ];
                }
            }
        }

        return $min;
    }

    private function maxDivCombination( $a, $b ){
        $max = $a[0] / $b[0];
        for( $i = 0; $i < count( $a ); $i++ ){
            for( $j = 0; $j < count( $b ); $j++ ) {
                if( $a[ $i ] / $b[ $j ] > $max ){
                    $max = $a[ $i ] / $b[ $j ];
                }
            }
        }

        return $max;
    }
}
