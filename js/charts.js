/**
 * Created by user on 09.10.2016.
 */
function drawAreaChart(data, options, element) {
    data = google.visualization.arrayToDataTable(data);

    var chart = new google.visualization.AreaChart(element);
    chart.draw(data, options);
    return chart;
}
function chartsClickHandler(){
    alert("123");
}