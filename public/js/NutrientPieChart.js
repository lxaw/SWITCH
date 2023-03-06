"use strict"

/*
This class represents a NutrientPieChart.
It is a wrapper over a Chart.js Pie (Doughnut) object.
*/
class NutrientPieChart{
    constructor(arrLabels,arrData,strChartId,intNutrientLimit,strColorProgress,strColorNonProgress,strColorOver,boolIsGoUp,idSpanUpdateSpan){
        // the labels of the pie chart
        // for instance, "Remaining, Used"
        this.arrLabels = arrLabels;

        // the data of the pie chart
        // for instance, at the start of Calories it may be 
        // [1200,0]
        // the first index indicates what is remaining, while the second what is used.
        this.arrData = arrData;

        // the html id of the chart in the DOM.
        // used so that jquery can update the correct parts of the chart.
        // for instance, an example id would be `canvas__fat-totals`
        // the id is the chart's id, which is likely a canvas.
        this.strChartId = strChartId;

        // the limit to the nutrients that a user can consume
        // before the chart changes color, indicating an over-consumption.
        // this value is likely going to be the same as the first index of 
        // this.arrData, but I allowed it to be different just in case that need arose.
        this.intNutrientLimit = intNutrientLimit

        // the span to update the totals for.
        // for instance, if that chart is for Total Fat, this may be `#span__total-fat`
        // This value is what appears in the `Used` section of the pie chart.
        this.idSpanUpdateSpan = idSpanUpdateSpan

        // the chart object itself, as represented with Chart.js.
        this.chart = null; 

        // if over nutrient limit or not.
        // when over limit, the chart changes colors.
        this.boolOverLimit = false;

        // if the chart is to "Go Up" or not.
        // for potassium and fiber, we want the chart to be different color from fat and calories.
        // fat and calorie charts go "down", meaning that they start from a nonprogress color and then head to a progress color
        this.boolIsGoUp = boolIsGoUp;

        // colors

        // progress color is the color that grows as the macronutrient is added.
        // nonprogress color is the other color.
        // colorover is the color the entire graph changes to when the macronutrient limit is reached.
        this.strColorNonProgress = strColorNonProgress;
        this.strColorProgress = strColorProgress;
        this.strColorOver = strColorOver;
    }
    /*
    Create the chart object
    */
    createChart(){
        // need a reference to this
        var self = this;
        this.chart = new Chart(self.strChartId,{
            type:"doughnut",
            data: {
                labels:self.arrLabels,
                datasets:[{
                    backgroundColor:[self.strColorNonProgress,self.strColorProgress],
                    data: self.arrData,
                    borderWidth:0,
                    borderColor: self.COLOR_BLACK,
                    hoverBorderWidth:0,
                }]
            },
            plugins: [
                {
                beforeDraw: function(chart){
                    var w = chart.width;
                    var h = chart.height;
                    var ctx = chart.ctx;
                    var fontSize = h / 112;
                    ctx.font = fontSize + 'em sans-serif';
                    ctx.textBaseline = 'middle';
                    var text = "0%";
                    // if is go up, start at 0, else start at 100
                    if(!self.boolIsGoUp){
                        text = "100%";
                    }
                    var textX = Math.round((w- ctx.measureText(text).width)/2);
                    var textY = h / 2;

                    ctx.fillText(text,textX,textY);
                    ctx.save();
                },
            },
        ],
            options:{
                legend:{
                    display:false,
                }
            }
        });
    }
    /*
    Getters, setters.
    */
    setNonProgressColor(strColor){
        this.chart.data.datasets[0].backgroundColor[0] = strColor;
    }
    getNonProgressColor(){
        return this.chart.data.datasets[0].backgroundColor[0];
    }
    setProgressColor(strColor){
        this.chart.data.datasets[0].backgroundColor[1] = strColor;
    }
    getProgressColor(){
        return this.chart.data.datasets[0].backgroundColor[1];
    }
    setBorderThickness(intBorder){
        this.chart.data.datasets[0].borderWidth = intBorder;
    }
    setIsOverLimit(bool){
        this.boolOverLimit = bool;
    }
    setProgressVal(intVal){
        /*
        intVal represents the total amount of the nutrient used.

        As users enter nutrient data, they take away from the limit that they have for that nutrient.
        Since arrData[1] is the used amount of the nutrient, we set it to the intVal, while setting 
        arrData[0] (the remaining amount of nutrient) to the limit - the total amount used.
        */
        this.arrData[1] = intVal;
        this.arrData[0] = this.intNutrientLimit - intVal;
    }
    updateUiOver(){
        // if not red, change to red
        //
        this.setNonProgressColor(this.strColorOver);
        this.setProgressColor(this.strColorOver);
        // update text to be 100%
        this.setProgressVal(this.intNutrientLimit);
        // update total span to be red
        $(this.idSpanUpdateSpan).css("color",this.strColorOver)

        this.updateText();
    }
    // this function depends on the html
    // we assume that all spans that hold the totals are in format
    // #span__total-[NUTRIENT_NAME]
    updateNutrientTotalSpan(intNewVal){
        $(this.idSpanUpdateSpan).text(intNewVal)
    }
    updateData(intVal){
        this.setProgressVal(intVal);
        this.updateText();
    }
    updateUiNotOver(){
        // if red, change to green
        this.setProgressColor(this.strColorProgress);
        this.setNonProgressColor(this.strColorNonProgress);
        $(this.idSpanUpdateSpan).css("color",this.strColorProgress)
    }
    updateText(){
        var self = this;
        this.chart.config.plugins[0].beforeDraw = function(chart,options){
            var w = self.chart.width;
            var h = self.chart.height;
            var ctx =self.chart.ctx;
            var fontSize = h / 112;
            ctx.font = fontSize + 'em sans-serif';
            ctx.textBaseline = 'middle';

            var perc = Math.ceil((self.arrData[1] / self.intGetLimit())*100)
            // if go up, just use regular perc, else use 100 - perc
            if(!self.boolIsGoUp){
                perc = Math.floor(100-perc);
            }

            var text = perc + "%";
            var textX = Math.round((w - ctx.measureText(text).width)/2);
            var textY = h / 2;

            ctx.fillText(text,textX,textY);
            ctx.save();
        }
        this.chart.update();
    }
    intGetLimit(){
        return this.intNutrientLimit;
    }
    boolGetOverLimit(){
        return this.boolOverLimit;
    }
    updateVal(intFinalVal){
        this.updateNutrientTotalSpan(intFinalVal)
        if(intFinalVal >= this.intGetLimit()){
            if(!this.boolGetOverLimit()){
                // final value is greater than max but we are not yet over
                // thus now we are over
                this.setIsOverLimit(true);
                this.updateUiOver();
            }
            return;
        }
        // check if we are getting out of the limit
        if(this.boolGetOverLimit()){
            this.setIsOverLimit(false);
            this.updateUiNotOver();
        }

        this.updateData(intFinalVal);
    }
}