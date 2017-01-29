/**
 * Created by user on 09.10.2016.
 */
function drawAreaChart(data, options, element) {
    data = google.visualization.arrayToDataTable(data);

    var chart = new google.visualization.AreaChart(element);
    chart.draw(data, options);
    return chart;
}
function chartsClickHandler(e){
    var sel = this.getSelection();
    var row = sel[0].row;
    var valueId = this.data[row + 1][0];
    console.log("Selected item Id was: "+valueId);
}