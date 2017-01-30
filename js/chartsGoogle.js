/**
 * Created by user on 09.10.2016.
 */
var test = {
    abc:"123",
    cde:"456"
};
console.log(test.length);
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
        class:"delete"
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
        fact.addParameter(fact.newParameter());
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
        $.post(baseUrl + '/data/chart/'+fromTimeUnix+'/'+toTimeUnix,this.element.serialize(),null, "JSON").done(function(data){
            console.log(data);
            alert(data);
        });
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
function datePickerUpdate(start, end, label){
    fromTimeUnix = Math.floor(start / 1000);
    toTimeUnix = Math.floor(end / 1000);
}