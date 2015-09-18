/**
 * Created by Администратор on 10.09.2015.
 */

$(document).ready( function(){

    function activate_btn ( bool ) {

        if ( bool ) {
            $("#send_intervals").css("pointer-events", "auto");
        } else  {
            $("#send_intervals").css("pointer-events", "none");
        }
    }

    function revisIntForInvers( countIntervals ){

        for( var i = 0; i < countIntervals; i++ ){
            var left = +$("#int" + i + "_1").val();
            var right = +$("#int" + i + "_2").val();
            var unar_oper = $("select[name='select--int" + i + "'] option:selected").val();

            var mess = "";
            if( ( left < 0 || right < 0 ) && ( -left < -right ) &&
                ( unar_oper == "~" ) ){
                mess +=" Значення не можуть бути відємними.";
            }
            if( ( left == 0 || right == 0 ) &&
                ( unar_oper == "^" ) ){
                mess +=" Значення не можуть бути нульовими.";
            }
            if( left > right ){
                mess +=" Ліва границя повинна бути менша правої.";
            }

            //if( ( ( left < 0 || right < 0 ) &&
            //    ( unar_oper == "^" ) ) ||
            //    ( left > right ) ){
            //
            //    console.log("block btn");
            //
            //    $("#error" + i).css("display", "inline-block");
            //    $("#error" + i).text("Перевірте коректність даних");
            //    activate_btn ( false );
            //    return;
            //}

            if( mess != "" ) {
                console.log("block btn");
                $("#error" + i).css("display", "inline-block");
                $("#error" + i).text("Перевірте коректність даних: "+mess);
                console.log( mess );
                activate_btn ( false );
                mess = "";
                return;
            }



        }
        $(".error").css("display", "none");
        console.log("unblock");
        activate_btn ( true );

    }

    var countIntervals = $("#countIntervals").val();
    console.log("count="+countIntervals)

        for (var i = 0; i < countIntervals; i++) {

            $("input[name='int" + i + "_1']").focus(function () {

                revisIntForInvers(countIntervals);
            });
            $("input[name='int" + i + "_2']").focus(function () {

                revisIntForInvers(countIntervals);
            });
        }

});