<pre>
    <?php
foreach ($rows as $row) {
    if(!isset($resultados[$row['nomeMateria']])){
        $resultados[$row['nomeMateria']] = (object) [
            'totalTrabalhos' => 0, 
            'entregues' => 0,
            'entreguesNaData' => 0,
            'naoEntregues' => 0,
            'aindaNoPrazo' => 0
        ];
    }


    $resultados[$row['nomeMateria']]->totalTrabalhos += 1;
    if($row['files']!=""){
        $resultados[$row['nomeMateria']]->entregues += 1;

        if(strtotime($row['prazo'] . " 23:59:59") >= strtotime($row['dtEntrega'])){
            $resultados[$row['nomeMateria']]->entreguesNaData += 1;
        }
    }
    else{
        if(strtotime($row['prazo'] . " 23:59:59") <= strtotime(date("Y-m-d H:i:s"))){
            $resultados[$row['nomeMateria']]->naoEntregues += 1;
        }
        else{
            $resultados[$row['nomeMateria']]->aindaNoPrazo += 1;
        }
    }

}

if($rows){
    foreach ($resultados as $key => $value) {
    ?>

        <div id="piechart<?php echo $key ?>"></div>
        <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Trabalho', 'Quantidade'],
        ['Entregues', <?php echo $value->entreguesNaData ?>],
        ['Não entregues', <?php echo $value->naoEntregues ?>],
        ['Entregas atrasados', <?php echo $value->entregues - $value->entreguesNaData ?>],
        ['Ainda no prazo', <?php echo $value->aindaNoPrazo ?>]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {'title':'<?php echo $key ?>',
                        'width':640, 
                        'height':400,
                        is3D: false,
                        pieSliceText: 'value',
                        legend: {/*position: 'top'*/}};

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart<?php echo $key ?>'));
        chart.draw(data, options);
        }
        </script>

    <?php
    }
}
?>  </pre>