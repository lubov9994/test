<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta charset="utf-8" />
    <title>Заголовок</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="css/main.css" />

</head>
<body>

<header>
<!--    header-->
</header>

<article class="container">
    <h1>Операции над доверительными интервалами</h1>
    <form role="form" class="form-inline" action="index.php" method="post">
        <div class="form-group">
            <input name="countIntervals" type="number" min="1" max="10" class="form-control" placeholder="Количество интервалов" style="width: 180px; margin: 0 10px;  padding: 5px; ">
        </div>
        <button type="submit" class="form-control btn btn-default">Підтвердити</button>
    </form>


    <?php

    define('__ROOT__', dirname(dirname(__FILE__)));

    if( isset( $_POST["start_count"] ) ) : ?>

    <div class="calculations">
        <?php
           require_once __ROOT__."\www\core\ExpressionBuilder.php";

            $intervalOperationsObj = new ExpressionBuilder();

            $str .= "[";
            for ( $i = 0; $i < count( $intervalOperationsObj->mainExpr ); $i++ ){
                if( count( $intervalOperationsObj->mainExpr[ $i ] ) > 1 ){
                    $str .= " [" . $intervalOperationsObj->mainExpr[$i][0] . ", ".$intervalOperationsObj->mainExpr[$i][1] . "],";
                }
            }


            $resultSet = $intervalOperationsObj->resolveExpression();

            $str .= " [" . $resultSet[0] . ", " . $resultSet[1]."]";

            $str .= "]";
        ?>
        <div class="ansver">
            <h2>Відповідь: [ <?php echo $resultSet[0]; ?>, <?php echo $resultSet[1]; ?> ]</h2>
        </div>
        <button onclick="drawChart(<?php echo $str; ?>);">Показати графічно</button>
        <table>
            <tr>
                <td><div id="TheChart" style="width:600px;height:300px"></div></td>
                <td>&nbsp;&nbsp;</td>
                <td><div id="choices">Dіаграма </div></td>
            </tr>
        </table>

    </div>

    <?php  endif;
    if( isset($_POST["countIntervals"]) && $_POST["countIntervals"] != 0  && !isset( $_POST["start_count"] )): ?>

        <form role="form" action="http://test/www/index.php" method="post">

        <h1>Введите значения интервалов</h1>

        <?php
            $countIntervals = $_POST["countIntervals"];
        for( $i=0; $i<$countIntervals; $i++ ):
            ?>
            <input type="hidden" id="countIntervals" name="countIntervals" value="<?php echo $countIntervals; ?>">

            <input type="hidden" id="start_count" name="start_count" value="true">

            <div class="form-group int-wrap">
                <label for="int<?php echo $i; ?>">Интервал int<?php echo $i; ?></label>
                <select id="select--int<?php echo $i; ?>" name="select--int<?php echo $i; ?>" class="form-control half-width-select">
                    <option value="none">none</option>
                    <option value="~">Відображення інтервалу довіри</option>
                    <option value="^">Інверсія інтервалу довіри</option>
                </select>
                <div id="int<?php echo $i; ?>" class="form-group">
                    <input id="int<?php echo $i; ?>_1" name="int<?php echo $i; ?>_1" type="number"  class="form-control" placeholder="left">
                    <input id="int<?php echo $i; ?>_2" name="int<?php echo $i; ?>_2" type="number"  class="form-control" placeholder="right">
                </div>

                <span id="error<?php echo $i; ?>" class="error" > </span>
            </div>

            <?php if( $i < $countIntervals-1 ): ?>

                <select name="oper<?php echo $i; ?>" class="form-control oper-select">
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">*</option>
                    <option value="/">/</option>
                    <option value="max">max</option>
                    <option value="min">min</option>
                </select>
            <?php endif; ?>

            <?php endfor; ?>

            <button id="send_intervals" type="submit" class="form-control btn btn-default">Рассчитать</button>

        </form>

    <?php endif; ?>

</article>


<footer>
<!--    footer-->
</footer>



<?php if( isset( $_POST["start_count"] ) ) : ?>

<script src="js/protochart/ProtoChart/prototype.js"></script>
<script src="js/protochart/ProtoChart/ProtoChart.js"></script>
<script src="js/protochart/pc-charts/ColorChats.js"></script>
<script src="js/protochart/pc-data/color.js"></script>
<?php else: ?>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/common.js"></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<?php endif; ?>



</body>
</html>
