<?php

/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 06.09.2015
 * Time: 20:43
 */

//define('__ROOT__', dirname(dirname(__FILE__)));
//include_once __ROOT__."\www\core\TrustIntervalsOperation.php";
//include "TrustIntervalsOperation.php";
include __DIR__."\TrustIntervalsOperation.php";

class ExpressionBuilder
{
    public $operPriority;
    public $intermiddleResults;
    public $operationManager;
//    public $arrExpressions;
    public $binOperations;
    public $allOperations;

    public $mainExpr;
    public $countIntervals;

    public function __construct ( ){
//        echo "constructor EB";
        $this->intermiddleResults = array();
//        $this->arrExpressions = $arrExpressions;
        $this->operationManager = new TrustIntervalsOperation( );

        $this->operPriority = array( "+"=>4,
                            "-"=>4,
                            "*"=>2,
                            "/"=>2,
                            "numbAsInt"=>2, //Представлення чіткого числа у вигляді інтервалу довіри
                            "~"=>1,         //Відображення інтервалу довіри
                            "^"=>1,         //Інверсія інтервалу довіри
                            "k*"=>2,        // Множення (ділення) інтервалу довіри на невід’ємне число
                            "k/"=>2,        // ділення інтервалу довіри на невід’ємне число
                            "max"=>3,       // Знаходження максимуму двох інтервалів
                            "min"=>3 );
        $this->binOperations = array( "-", "+", "*", "/", "k*", "k/", "max", "min");
        $this->allOperations = array( "-", "+", "*", "/", "k*", "k/", "max", "min", "numbAsInt", "~", "^" );

        $this->generateExpr ();

    }

    public function resolveExpression(){

        $intermiddleResult = $this->mainExpr;
        $count = count( $intermiddleResult );

        while ( true ) {

            if( count( $intermiddleResult ) == 1 ){

                return $intermiddleResult[0];
            } else if( count( $intermiddleResult ) == 3 ){
                $intermiddleResult = $this->_applayBinOperation($intermiddleResult[1], $intermiddleResult[0], $intermiddleResult[2]);

                return $intermiddleResult;
            } else {

                $temp = array();
                $temp_tail = array();

                for ( $i = 1; $i < $count-1; $i+=2 ){
                    if( $i < $count-2 ){
                        if( $this->operPriority[ $intermiddleResult[ $i ] ] <= $this->operPriority[ $intermiddleResult[ $i+2 ] ] ){
                            $temp[] = $this->_applayBinOperation($intermiddleResult[ $i ], $intermiddleResult[ $i-1 ], $intermiddleResult[ $i+1 ]);

                            $temp_tail = array_slice( $intermiddleResult, $i+2 );
                            for($j=count($temp)-1;$j>-1;$j--){

                                array_unshift($temp_tail, $temp[$j]);
                            }

                            break;
                        }
                        else {
                            $temp[] = $intermiddleResult[ $i-1 ];
                            $temp[] = $intermiddleResult[ $i ];

                        }
                    } else {
                        if( $this->operPriority[ $intermiddleResult[ $i ] ] <= $this->operPriority[ $intermiddleResult[ $i-2 ] ] ){
                            $temp[] = $this->_applayBinOperation($intermiddleResult[ $i ], $intermiddleResult[ $i-1 ], $intermiddleResult[ $i+1 ]);
                            $temp_tail = $temp;

                            break;
                        }
                    }
                }

                $intermiddleResult = $temp_tail;
                $count = count( $temp_tail );

            }

        }

        return $intermiddleResult;
    }

    private function _applayBinOperation( $oper, $iner1, $iner2 ){
        $inter = array();
        switch( $oper ) {
            case "+":
                $inter = $this->operationManager->addInterval( $iner1, $iner2 );
                break;
            case "-":
                $inter = $this->operationManager->subInterval( $iner1, $iner2 );
                break;
            case "*":
                $inter = $this->operationManager->multInterval( $iner1, $iner2 );
                break;
            case "/":
                $inter = $this->operationManager->divInterval( $iner1, $iner2 );
                break;
            case "min":
                $inter = $this->operationManager->minInterval( $iner1, $iner2 );
                break;
            case "max":
                $inter = $this->operationManager->maxInterval( $iner1, $iner2 );
                break;
        }

        return $inter;
    }

    private function _isOperation( $ceil ){
        if ( in_array ( $ceil , $this->allOperations ) ) {
            return true;
        }
        return false;
    }

    private function _isBinarOperation( $ceil ){
        if ( in_array ( $ceil , $this->binOperations ) ) {
            return true;
        }
        return false;
    }
    
    private function generateExpr (){
        $this->mainExpr = array();
        $this->countIntervals = $_POST["countIntervals"];

        for( $i = 0; $i < $this->countIntervals; $i++ ){
            $unarOperation = "select--int" . $i;
            $unar = $_POST[$unarOperation];

//            echo "Unar $i => $unar <br>";


            $attrLeft = "int" . $i . "_1" ;
            $attrRight = "int" . $i . "_2" ;
            $operation = "oper" . $i;
            //интервал
            if ( $unar == "none" ) {
                $this->mainExpr[] = array( $_POST[$attrLeft], $_POST[$attrRight] );
            } else if ( $unar == "~") {
                $this->mainExpr[] = $this->operationManager->mirrorInetval(
                                                                array( $_POST[$attrLeft], $_POST[$attrRight] )
                                                            );
            } else if ( $unar == "^") {
                $this->mainExpr[] = $this->operationManager->inverseInetval(
                                                                array( $_POST[$attrLeft], $_POST[$attrRight] )
                                                            );
            }
            //операция
            if( $i < $this->countIntervals-1 ){
                $this->mainExpr[] = $_POST[$operation];
            }

        }

    }

    //Privat
    private function modifyIfZero(){
        for( $i = 0; $i < count( $this->intervals ); $i++ ){
            for( $j = 0; $j < count( $this->intervals[ $i ] ); $j++ ){
                if ( $this->intervals[ $i ][ $j ] == 0 ) {
                    $this->intervals[ $i ][ $j ] = 0.001;
                }
            }
        }
    }


}