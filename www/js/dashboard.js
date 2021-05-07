// Radial Charts

var radialCharts = document.querySelectorAll('#stats .radial.chart');

radialCharts.forEach((chartDiv) => {
    var progress = chartDiv.dataset.progress;
    var label = chartDiv.dataset.label;
    var options = {
        chart: {
            height: '300px',
            type: 'radialBar',
            fontFamily: 'Helvetica',
        },
        plotOptions: {
            radialBar: {
                dataLabels: {
                    show: true,
                    name: {
                        show: true,
                        color: "rgba(0,0,0,0.9)",
                        fontSize: "36px",
                        offsetY: 12,
                    },
                    value: {
                        show: false,
                    }
                },
                track: {
                    margin: 0,
                    background: 'rgba(0,0,0,0.1)',
                    strokeWidth: '100%',
                },
                hollow: {
                    margin: 0,
                    size: '50%',
                },
            },
        },
        stroke: {
            lineCap: 'round',
            width: 0,
        },
        fill: {
            type: 'solid',
            colors: ["rgba(0,0,0,0.7)"]
        },
        states: {
            normal: {
                filter: {
                    type: 'none',
                },
            },
            hover: {
                filter: {
                    type: 'none',
                },
            },
        },
        series: [progress],
        labels: [label],
    }
    var chart = new ApexCharts(chartDiv,options);
    chart.render();
});

// For all line charts
var lineCharts = document.querySelectorAll('#stats .line.chart');

lineCharts.forEach((chartDiv) => {
    var series = JSON.parse(chartDiv.dataset.series);
    var labels = JSON.parse(chartDiv.dataset.labels);

    var options = {
        chart: {
            height: 450,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        colors: ["#764ba2"],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
            colors: ["#764ba2"],
            width: 6,
        },
        fill: {
            type: "gradient",
            gradient: {
                type: "horizontal",
                shadeIntensity: 1,
                gradientToColors: ["#667eea", "#764ba2"],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
            }
        },
        markers: {
            size: 4,
            opacity: 1,
            colors: ["#fff"],
            strokeColor: "#333",
            strokeWidth: 4,
            hover: {
                size: 6,
            }
        },
        grid: {
            borderColor: 'transparent',
            row: {
                colors: ['rgba(0,0,0,0.05)', 'transparent'],
                opacity: 1
            },
        },
        series: [{
            name: "Marks",
            data: series,
        }], 
        xaxis: {
            categories: labels,
            labels: {
                style: {
                    color: "#333",
                    fontSize: "16px",
                    fontFamily: "Helvetica",
                    cssClass: "graph-ticks",
                },
            },
            axisBorder: {
                show: false,
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 5,
            labels: {
                style: {
                    color: "#333",
                    fontSize: "16px",
                    fontFamily: "Helvetica",
                    cssClass: "graph-ticks",
                },
            },
            axisBorder: {
                show: false,
            }
        },
        tooltip: {
            style: {
                fontFamily: "Helvetica",
                fontSize: "16px",
            },
            shared: true,
        },
    }

    var chart = new ApexCharts(chartDiv, options);
    chart.render();
});

// For all multiline charts
var lineCharts = document.querySelectorAll('#stats .multiline.chart');

lineCharts.forEach((chartDiv) => {
    var serieslist= JSON.parse(chartDiv.dataset.series);
    var serieslabels = JSON.parse(chartDiv.dataset.serieslabels);
    var labels = JSON.parse(chartDiv.dataset.labels);

    series = [];
    for(i=0; i<serieslist.length; i+=1) {
        series.push({name:serieslabels[i], data:serieslist[i]})
    }    

    var options = {
        chart: {
            height: 450,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        // colors: ["#667eea", "#764ba2"],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
            // colors: ["#667eea", "#764ba2"],
            width: 6,
        },
        fill: {
            // type: "gradient",
            // gradient: {
            //     type: "horizontal",
            //     shadeIntensity: 1,
            //     gradientToColors: ["#667eea", "#764ba2"],
            //     inverseColors: true,
            //     opacityFrom: 1,
            //     opacityTo: 1,
            // }
        },
        markers: {
            // size: 4,
            size:0,
            opacity: 1,
            colors: ["#fff"],
            strokeColor: "#333",
            strokeWidth: 4,
            hover: {
                size:4,
                // size: 6,
            }
        },
        grid: {
            borderColor: 'transparent',
            row: {
                colors: ['rgba(0,0,0,0.05)', 'transparent'],
                opacity: 1
            },
        },
        series: series, 
        xaxis: {
            categories: labels,
            labels: {
                style: {
                    color: "#333",
                    fontSize: "16px",
                    fontFamily: "Helvetica",
                    cssClass: "graph-ticks",
                },
            },
            axisBorder: {
                show: false,
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 5,
            labels: {
                style: {
                    color: "#333",
                    fontSize: "16px",
                    fontFamily: "Helvetica",
                    cssClass: "graph-ticks",
                },
            },
            axisBorder: {
                show: false,
            }
        },
        tooltip: {
            style: {
                fontFamily: "Helvetica",
                fontSize: "16px",
            },
            shared: true,
        },
    }

    var chart = new ApexCharts(chartDiv, options);
    chart.render();
});