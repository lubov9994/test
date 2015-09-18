function drawChart( input ) {
    var waves = [];
    var keyedWaves = {};
    var waveNum = 0;
    //for ( var offset = 0; offset < 6.28; offset += 0.42 ) {
    for ( var i = 0; i < input.length; i += 1 ) {
        waveNum++;
        var aWave = [];
        //for ( var i = 0; i < input.length; i += 1 ) {
            //aWave.push( [i, Math.sin( i + offset )] );
            aWave.push( [ input[i][0], waveNum ] );
            aWave.push( [ input[i][1], waveNum ] );

        //}
        var aLabel = "Відрізок #" + waveNum;
        var waveSet = {
            data:aWave,
            label:aLabel,
            color:colorfullsequence[waveNum - 1]
        };
        waves.push( waveSet );
        keyedWaves[ aLabel ] = waveSet;
    }

    // insert checkbox/legend
    var choiceContainer = $("choices");
    var checkboxTable = "<table>";
    for(key in keyedWaves) {
        var val = keyedWaves[key];
        checkboxTable += '<tr>';
        checkboxTable += '<td><div class="legendBorder"><div class="legendColor" style="background-color: ' + val.color + ';"/></div></td>';
        checkboxTable += '<td><input type="checkbox" name="' + key + '"'
            + ' checked="checked"/></td>';
        checkboxTable += '<td>' + val.label + '</td>';
        checkboxTable += '</tr>';
    }
    checkboxTable += "</table>";
    choiceContainer.insert( checkboxTable );
    var attachRefreshFunction = function( x ) {
        x.observe( 'click', plotAccordingToChoices );
    };
    choiceContainer.select("input").each( attachRefreshFunction );


    function plotAccordingToChoices() {
        var data = [];

        var includeData = function (s) {
            var key = s.name;
            if (key && keyedWaves[key]) {
                data.push( keyedWaves[key] );
            }
        }
        choiceContainer.select("input:checked").each( includeData );

        if (data.length > 0) {
            var targetDiv = $("TheChart");
            var settings =  {
                lines: {show: true, lineWidth:5},
                // legend: {show:true}
            };
            new Proto.Chart(targetDiv, data, settings);
        }
    }

    plotAccordingToChoices();
}
