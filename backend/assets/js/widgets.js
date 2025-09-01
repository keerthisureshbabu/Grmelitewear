 /* Total Sales */
 var spark1 = {
    chart: {
        type: 'line',
        height: 60,
        width: 120,
        sparkline: {
            enabled: true
        },
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    series: [{
        name: 'Value',
        data: [14, 38, 26, 44, 20, 65, 35, 40, 14]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgba(var(--pink-rgb))'],

}
var spark1 = new ApexCharts(document.querySelector("#widget-chart-1"), spark1);
spark1.render();
/* Total Sales */

 /* Total Profit */
 var spark1 = {
    chart: {
        type: 'line',
        height: 60,
        width: 120,
        sparkline: {
            enabled: true
        },
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    series: [{
        name: 'Value',
        data: [14, 38, 26, 44, 20, 65, 35, 40, 14]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgba(var(--primary-rgb))'],

}
var spark1 = new ApexCharts(document.querySelector("#widget-chart-2"), spark1);
spark1.render();
/* Total Profit */

 /* Total Revenue */
 var spark1 = {
    chart: {
        type: 'line',
        height: 60,
        width: 120,
        sparkline: {
            enabled: true
        },
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    series: [{
        name: 'Value',
        data: [14, 38, 26, 44, 20, 65, 35, 40, 14]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgba(var(--secondary-rgb))'],

}
var spark1 = new ApexCharts(document.querySelector("#widget-chart-3"), spark1);
spark1.render();
/* Total Revenue */
 /* Total Sales */
 var spark1 = {
    chart: {
        type: 'line',
        height: 60,
        width: 120,
        sparkline: {
            enabled: true
        },
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    series: [{
        name: 'Value',
        data: [14, 38, 26, 44, 20, 65, 35, 40, 14]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgba(var(--success-rgb))'],

}
var spark1 = new ApexCharts(document.querySelector("#widget-chart-4"), spark1);
spark1.render();
/* Total Sales */


    /* profit */
    var options = {
        series: [
            {
                name: "Profit",
                data: [20, 38, 38, 72, 55, 63, 43],
                type: "column",
            }
        ],
        chart: {
            height: 200,
            type: "line",
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
        },
        plotOptions: {
            bar: {
                columnWidth: "35%",
                borderRadiusApplication: "end",
                borderRadiusWhenStacked: "all",
                borderRadius: 5,
                colors: {
                    ranges: [{
                        from: 0,
                        to: 65,
                        color: 'var(--primary03)'
                    }, {
                        from: 70,
                        to: 100,
                        color:'var(--primary-color)'
                    }]
                },
            }
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            position: "top",
            horizontalAlign: "center",
        },
        stroke: {
            curve: "smooth",
            width: ["0"],
        },
        grid: {
            borderColor: "#f1f1f1",
            strokeDashArray: 2,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        colors: ["var(--primary-color)"],
        xaxis: {
            type: "month",
            categories: [
                "Mon",
                "Tue",
                "Wed",
                "Thu",
                "Fri",
                "Sat",
                "sun"
            ],
            axisBorder: {
                show: true,
                color: "rgba(119, 119, 142, 0.05)",
                offsetX: 0,
                offsetY: 0,
            },
            axisTicks: {
                show: true,
                borderType: "solid",
                color: "rgba(119, 119, 142, 0.05)",
                width: 6,
                offsetX: 0,
                offsetY: 0,
            },
            labels: {
                rotate: -90,
            },
        },
    };
    var chart = new ApexCharts(document.querySelector("#widget-profit"), options);
    chart.render();
    /* profit */
    /* Revenue */
    var spark04 = {
        chart: {
          type: 'area',
          height: 70,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          show: true,
          width:[2],
          curve: 'smooth',
          lineCap: 'butt',
          colors: undefined,
          dashArray: 0,
        },
        tooltip: {
          enabled: false,
        },
        series: [{
          name: 'Value',
          data: [5, 4, 3, 8, 5, 6, 3, 8, 6, 9, 5, 7, 3, 8]
        }],
        yaxis: {
          min: 0,
          show: false
        },
        plotOptions: {
          bar: {
            columnWidth: "40%",
            borderRadiusApplication: "around",
          },
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                type: "horizontal",
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "rgba(var(--secondary-rgb), 1)",
                            opacity: 0.03
                        },
                        {
                            offset: 90,
                            color: "rgba(var(--secondary-rgb), 1)",
                            opacity: 0.03
                        }
                    ]
                ]
            }
        },
        xaxis: {
          axisBorder: {
            show: false
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
        },
        colors: ['rgba(var(--secondary-rgb), 1)'],
      
      }
      document.getElementById('widget-chart-5').innerHTML = '';
      var spark04 = new ApexCharts(document.querySelector("#widget-chart-5"), spark04);
      spark04.render();
    /* Revenue */
    /* sales */
    var spark06 = {
        chart: {
          type: 'area',
          height: 70,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          show: true,
          width:[2],
          curve: 'smooth',
          lineCap: 'butt',
          colors: undefined,
          dashArray: 0,
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                type: "horizontal",
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "rgba(var(--success-rgb), 1)",
                            opacity: 0.03
                        },
                        {
                            offset: 90,
                            color: "rgba(var(--success-rgb), 1)",
                            opacity: 0.03
                        }
                    ]
                ]
            }
        },
        tooltip: {
          enabled: false,
        },
        series: [{
          name: 'Value',
          data: [5, 4, 3, 8, 5, 6, 3, 8, 6, 9, 5, 7, 3, 8]
        }],
        yaxis: {
          min: 0,
          show: false
        },
        plotOptions: {
          bar: {
            columnWidth: "40%",
            borderRadiusApplication: "around",
          },
        },
        xaxis: {
          axisBorder: {
            show: false
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
        },
        colors: ['rgba(var(--success-rgb), 1)'],
      
      }
      document.getElementById('widget-chart-6').innerHTML = '';
      var spark06 = new ApexCharts(document.querySelector("#widget-chart-6"), spark06);
      spark06.render();
    /* sales */


    /* widget-chart-7 */
var options = {
    series: [{
        name: "Profit",
        data: [56, 44, 66, 55, 68, 90, 55, 68, 55, 66, 44, 56],
        type: 'area',
    },
    {
        name: "Sales",
        data: [45, 50, 42, 59, 53, 74, 43, 43, 30, 55, 74, 49],
        type: 'area',
    },
    {
        name: "Revenue",
        data: [53, 40, 63, 50, 65, 80, 50, 65, 50, 63, 45, 50],
        type: 'bar',
    }],
    chart: {
        type: 'area',
        height: 355,
        toolbar: {
          show: false,
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 7,
            left: 0,
            blur: 1,
            color: ["var(--primary-color)",  'rgba(var(--success-rgb), 1)'],
            opacity: 0.05,
          },
    },
    grid: {
        borderColor: 'rgba(167, 180, 201 ,0.2)',
        strokeDashArray: 3
    },
    colors: [  "rgba(var(--secondary-rgb), 1)", "rgba(var(--success-rgb), 1)", "var(--primary-color)"],
    stroke: {
        width: [3, 2, 0],
        curve: ["smooth", "smooth", "smooth"],
        dashArray: [4, 0, 0], 
      },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
        position: 'top',
        labels: {
            colors: '#74767c',
        },
        markers:  {
          size: 5,
          shape: "circle", 
          strokeWidth: 0
        }
    },
    fill: {
        type: ["gradient","gradient","soild"],
        gradient: {
            opacityFrom: 0.5,
            opacityTo: 0.2,
            stops: [0, 60],
            colorStops: [
                [
                    {
                        offset: 0,
                        color: "rgba(var(--secondary-rgb), .3)",
                        opacity: 0.2
                      },
                      {
                        offset: 50,
                        color: "rgba(var(--secondary-rgb), .3)",
                        opacity: 0.2
                      },
                      {
                        offset: 100,
                        color: 'rgba(var(--secondary-rgb), .3)',
                        opacity: 0.5
                      }
                ],
                [
                    {
                        offset: 0,
                        color: "rgba(var(--success-rgb), .1)",
                        opacity: 1
                      },
                      {
                        offset: 50,
                        color: "rgba(var(--success-rgb), .1)",
                        opacity: 1
                      },
                      {
                        offset: 100,
                        color: 'rgba(var(--success-rgb), .1)',
                        opacity: 0.5
                      }
                ],
            ],
        },
    },
    yaxis: {
        labels: {
            formatter: function (y) {
                return y.toFixed(0) + "";
            }
        },
        labels: {
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        },
        min: 0
    },
    plotOptions: {
        bar: {
            columnWidth: "18%",
            borderRadiusApplication: "end",
            borderRadiusWhenStacked: "all",
            borderRadius: 5,
        }
    },
    xaxis: {
        type: 'month',
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
        axisBorder: {
            show: true,
            color: 'rgba(167, 180, 201 ,0.2)',
            offsetX: 0,
            offsetY: 0,
        },
        axisTicks: {
            show: true,
            borderType: 'solid',
            color: 'rgba(167, 180, 201 ,0.2)',
            width: 6,
            offsetX: 0,
            offsetY: 0
        },
        labels: {
            rotate: -90,
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    }
  };
  var chart2 = new ApexCharts(document.querySelector("#widget-chart-7"), options);
  chart2.render();
  /* widget-chart-7 */


     /* widget-visitors */
     var options = {
        series: [
            {
                name: "Hot Leads",
                data: [80, 50, 30, 40, 100, 20],
            },
            {
                name: "Warm Leads",
                data: [20, 30, 40, 80, 20, 80],
            },
            {
                name: "Cold Leads",
                data: [15, 60, 50, 20, 30, 40],
            }
        ],
        chart: {
            height: 165,
            type: "radar",
            toolbar: {
                show: false,
            },
        },
        colors: ["var(--primary01)", "rgba(53, 189, 170, 0.1)", "rgba(255, 183, 72, 0.1)"],
        stroke: {
            width: 2,
            colors: ["var(--primary-color)", "rgb(53, 189, 170)", "rgb(255, 183, 72)"],
        },
        fill: {
            opacity: 0.1,
        },
        markers: {
            size: 0,
        },
        legend: {
            show: false,
            offsetX: 0,
            offsetY: 0,
            fontSize: "12px",
            markers: {
                width: 6,
                height: 6,
                strokeWidth: 0,
                strokeColor: "#fff",
                fillColors: undefined,
                radius: 5,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0,
            },
        },
        xaxis: {
            categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            axisBorder: { show: false },
        },
        yaxis: {
            axisBorder: { show: false },
        },
        grid: {
            padding: {
                bottom: -10
            }
        },
    };
    var chart = new ApexCharts(document.querySelector("#widget-visitors"), options);
    chart.render();
    /* widget-visitors */