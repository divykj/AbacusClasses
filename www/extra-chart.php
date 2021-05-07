<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Circle Gauge Chart</title>

    <style>
        #chart {
            max-width: 900px;
            margin: 35px auto;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div id="chart">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<script>
    var options = {
        chart: {
            height: 500,
            type: 'area',
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
            width: 8,
        },
        grid: {
            borderColor: 'transparent',
            row: {
                colors: ['rgba(0,0,0,0.02)', 'transparent'], // takes an array which will be repeated on columns
                opacity: 1
            },
        },
        series: [{
            name: "Marks",
            data: [74, 82, 53, 67, 80],
        }],
        xaxis: {
            categories: ['Units 1', 'Units 2', 'Mid Terms', 'Practice', 'Prelims'],
            labels: {
                style: {
                    color: "#333",
                    fontSize: "16px",
                    fontFamily: "Segoe UI",
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
                    fontFamily: "Segoe UI",
                    cssClass: "graph-ticks",
                },
            },
            axisBorder: {
                show: false,
            }
        },
        tooltip: {
            style: {
                fontFamily: "Segoe UI",
                fontSize: "16px",
            },
            markers: {
                show: false,
            },
            shared: true,
        },
        fill: {
            type: 'gradient',
            gradient: {
                type: "vertical",
                gradientToColors: ["#667eea", "#764ba2"],
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#chart"),
        options
    );

    chart.render();
</script>
</body>

</html>
