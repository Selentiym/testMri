/**
 * Created by user on 09.10.2016.
 */
function getLoader() {
    return $('<img/>',{
        src:baseUrl + '/images/loading.gif'
    });
}
function drawAreaChart(data, options, element) {
    data = google.visualization.arrayToDataTable(data);

    var chart = new google.visualization.AreaChart(element);
    chart.draw(data, options);
    return chart;
}
function chartsClickHandler(e){
    var sel = this.getSelection();
    if (sel[0]) {
        var row = sel[0].row;
        var column = sel[0].column;
        var valueId = this.data[row + 1][0];
        console.log("Selected item Id was: " + valueId);
        console.log("Factor config was: ");
        console.log(this.config);
        location.href = baseUrl + '/factorList?'+this.config + '&from='+this.fromTimeUnix + '&to=' + this.toTimeUnix + '&column=' + column + '&row=' + row + '&valueId=' + valueId;
    }
}

function ParameterForm(config){
    this.element = $('<div>',{
        class:"parameter"
    });
    this.text = $('<input>',{
        type:"text",
        name:config.name,
        value: config.value
    });
    this.close = $('<span>',{
        class:"delete",
        title:"Удалить параметр"
    });
    this.destroy = function(){
        this.element.remove();
    };
    this.close.click(bind(function(){this.destroy()},this));
    this.element.append(this.text);
    this.element.append(this.close);
}
baseArrayName = 'graphs';
function bind(func, obj){
    return function(){
        return func.apply(obj, arguments);
    };
}
function getFactorTypeElement(factor){
    //var types = {};
    if (typeof types == 'undefined') {
        types = {};
    }
    console.log(types);
    var ul = $('<select>',{
        name: factor.baseName + '[type]'
    });

    for(var prop in types){
        var selected = '';
        if (factor.config.type == prop) {
            selected = ' selected="selected"';
        }
        $('<option value="' + prop + '" ' + selected + '>' + types[prop] + '</option>').appendTo(ul);
    }
    delete factor.config.type;
    return ul;
}
function FactorForm(config){
    this.baseName = config.baseName;
    delete config.baseName;
    this.config = config;
    console.log('factor:');
    console.log(config);
    this.parameters = [];
    this.nextParameterName = function(){
        return this.baseName+'[param'+this.parameters.length+']';
    };
    this.element = $('<div>',{
        class:"factor"
    });
    this.element.append(getFactorTypeElement(this));
    var convParams = {
        type:"checkbox",
        value:"1",
        name:this.baseName + '[conversion]',
        class:'conversion',
        title:'Конверсия'
    };
    if (this.config.conversion) {
        convParams.checked = "checked";
    }
    delete this.config.conversion;
    this.conversion = $('<input>',convParams);
    this.element.append(this.conversion);
    this.parameterContainer = $("<div>",{
        class:"parameterContainer"
    });
    this.newButton = $('<span>',{
        value:"Новый параметр",
        class:"addParameter add"
    });
    this.newButton.click(bind(function(){
        this.addParameter(this.newParameter());
    },this));
    this.newParameter = function(){
        return new ParameterForm({
            name:this.nextParameterName(),
            value:""
        });
    };
    this.addParameter = function(parameter){
        this.parameterContainer.append(parameter.element);
        this.parameters.push(parameter);
    };
    this.destroy = function(){
        this.element.remove();
    };
    this.deleteFactor = $('<span>', {
        class:"delete",
        title:"Удалить фактор"
    });
    this.deleteFactor.click(bind(function(){
        this.destroy();
    },this));
    this.element.append(this.deleteFactor);
    this.element.append(this.parameterContainer);
    this.element.append(this.newButton);
    console.log('baseName:'+this.nextParameterName());
    for (var prop in config) {
        this.addParameter(new ParameterForm({
            name:this.nextParameterName(),
            value:config[prop]
        }));
    }
    return this;
}
function FactorSet(config){
    this.baseName = config.baseName;
    delete config.baseName;
    console.log('factorSet:');
    console.log(config);
    this.factorsNumber = 0;
    this.factors = [];
    this.element = $("<div>",{
        class:"factorSet"
    });
    this.factorsContainer = $('<div>',{
        class:'factorsContainer'
    });
    this.newButton = $('<span>',{
        class:'addFactor add'
    });
    this.destroy = function(){
        this.element.remove();
    };
    this.newButton.click(bind(function(){
        this.addFactor(this.newFactor());
    },this));
    this.newFactor = function(){
        var fact = new FactorForm({
            baseName: this.baseName + '['+this.factors.length+']'
        });
        this.addFactor(fact);
        //fact.addParameter(fact.newParameter());
        return fact;
    };
    this.element.append(this.factorsContainer);
    this.element.append(this.newButton);

    this.addFactor = function(factor){
        this.factorsNumber += 1;
        this.factors.push(factor);
        this.factorsContainer.append(factor.element);
    };
    for (var prop in config) {
        this.addFactor(new FactorForm($.extend({
            baseName: this.baseName + '['+this.factors.length+']'
        },config[prop])));
    }
    return this;
}
graphs = [];
function GraphForm(config){
    console.log('graph:');
    console.log(config);
    if (config.baseName) {
        this.baseName = config.baseName;
    } else {
        this.baseName = 'graphs[graph'+graphs.length+']';
    }
    this.mainWrapper = $('<div>',{
        class:'graph'
    });
    this.element = $('<form>');
    this.deleteGraph = $('<span>',{
        class:"delete",
        title:"Удалить график"
    });
    this.destroy = function(){
        this.element.remove();
    };
    this.deleteGraph.click(bind(function(){
        this.destroy();
    },this));
    this.element.append(this.deleteGraph);
    this.menuCont = $('<div>',{
        class:'graphMenu'
    });
    this.filterFactors = new FactorSet($.extend({
        baseName:this.baseName + '[filterFactors]'
    },config.filterFactors));
    if (!config.filterFactors) {
        config.filterFactors = {};
    }
    if ($.isEmptyObject(config.filterFactors)) {
        this.filterFactors.addFactor(this.filterFactors.newFactor());
    }
    this.showFactors = new FactorSet($.extend({
        baseName:this.baseName + '[showFactors]'
    },config.showFactors));
    if (!config.showFactors) {
        config.showFactors = {};
    }
    if ($.isEmptyObject(config.showFactors)) {
        this.showFactors.addFactor(this.showFactors.newFactor());
    }
    this.menuCont.append(this.filterFactors.element);
    this.menuCont.append(this.showFactors.element);
    this.graphCont = $('<div>',{
        class:'graphCont'
    });
    this.element.append(this.menuCont);
    this.element.append(this.graphCont);
    GraphsContainer.append(this.element);
    this.draw = function(){
        this.graphCont.html(getLoader());
        $.post(baseUrl + '/data/chart/'+fromTimeUnix+'/'+toTimeUnix,this.element.serialize(),null, "JSON").done(bind(function(data){
            google.charts.setOnLoadCallback(bind(function() {
                this.graphCont.html("");
                this.chart = drawAreaChart(data, {}, this.graphCont.get(0));
                this.chart.data = data;
                this.chart.fromTimeUnix = fromTimeUnix;
                this.chart.toTimeUnix = toTimeUnix;
                this.chart.config = this.element.serialize();
                google.visualization.events.addListener(this.chart, 'select', bind(function(e){chartsClickHandler.call(this.chart, e);},this));
            },this));
        },this));
    };
    this.draw();
    graphs.push(this);
}

function submitMainForm(){
    $form = $("#mainForm");
    $forms = $("form").not("#mainForm").replaceWith(function(){
        return $("<div />", {html: $(this).html()});
    });
    $form.attr("action",baseUrl + '/factorStat/' + start + '/'+ end);
    $form.submit();
}
function formatDate(date){
    return date.getDate() + '.' + (date.getMonth() + 1) + '.' + (1900 + date.getYear());
}
function datePickerUpdate(start, end, label){
    var from = new Date(start);
    var to = new Date(end);
    $("#fromDatepicker").html(formatDate(from));
    $("#toDatepicker").html(formatDate(to));
    fromTimeUnix = Math.floor(start / 1000);
    toTimeUnix = Math.floor(end / 1000);
}