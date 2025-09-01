/* Earnings Report Chart */
var options = {
    series: [
        {
          name: "This Year",
          type: "line",
          data: [80, 60, 90, 50, 100, 70, 90, 60, 85, 50, 80, 50],
        },
        {
          name: "Last Year",
          type: "line",
          data: [70, 80, 60, 80, 60, 100, 60, 90, 60, 80, 70, 80],
        },
      ],
    chart: {
        height: 309,
        type: "line",
        toolbar: {
          show: false,
        },
        dropShadow: {
          enabled: true,
          top: 0,
          left: 1,
          blur: 2,
          opacity: .1,
          color: '#000'
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: [4, 2],
        show: true,
        curve: ['smooth', 'smooth'],
        dashArray: [0,5]
    },
    grid: {
        borderColor: '#f3f3f3',
        strokeDashArray: 2,
    },
    yaxis: {
        min: 38,
    },
    xaxis: {
        axisBorder: {
            color: 'rgba(119, 119, 142, 0.05)',
        },
    },
    legend: {
        show: false
    },
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    markers: {
        size: 0
    },
    colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)"],
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadius: 2,
        }
    },
};
document.getElementById('courses-earnings').innerHTML = ''
var chart1 = new ApexCharts(document.querySelector("#courses-earnings"), options);
chart1.render();
/* Earnings Report Chart */

/* Earnings Report Chart */

    /* bar chart with negative values */
    var options = {
        series: [{
            name: 'Income',
            data: [4, 8, 10, 15, 8, 15, 5]
        },
        {
            name: 'Expenses',
            data: [-4, -8, -10, -15, -8, -15, -5]
        }
        ],
        chart: {
            type: 'bar',
            height: 340,
            stacked: true,
            toolbar: {
              show: false,
            },
        },
        colors: [ 'var(--primary-color)', 'rgba(var(--success-rgb), 1)'],
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '38%',
                borderRadius: 1,
            },
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: true,
            position: 'bottom',
            floating: false,
            markers: {
                size: 4,
                shape: "circle",
            },
        },
        grid: {
            show: true,
            borderColor: "rgba(119, 119, 142, 0.1)",
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: false
                }
            }, 
            row: {
                colors: ["rgba(var(--light-rgb), 1)", "transparent"],
                opacity: 0.7
            },
        },
        xaxis: {
            title: {
                text: 'Percent',
                style: {
                    color: "#8c9097",
                }
            },
            labels: {
                formatter: function (val) {
                    return Math.abs(Math.round(val)) + "%"
                },
                style: {
                    colors: "#8c9097",
                    fontSize: '11px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-xaxis-label',
                },
            },
            axisBorder: {
                show: false,
            }
        },
        yaxis: {
            labels: {
                show: true,
                style: {
                    colors: "#8c9097",
                    fontSize: '11px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            },
        }
    };
    var chart = new ApexCharts(document.querySelector("#financial-summary"), options);
    chart.render();
/* Earnings Report Chart */

/* Visitors Chart */
var spark02 = {
    chart: {
      type: 'bar',
      height: 25,
      width: 100,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false
      }
    },
    tooltip: {
      enabled: false,
    },
    series: [{
      name: 'Value',
      data: [5, 4, 3, 8, 5, 6, 8]
    }],
    yaxis: {
      min: 0,
      show: false
    },
    plotOptions: {
      bar: {
        columnWidth: "55%",
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
    colors: ['var(--primary-color)'],
  
  }
  document.getElementById('new-visitors').innerHTML = '';
  var spark02 = new ApexCharts(document.querySelector("#new-visitors"), spark02);
  spark02.render();
/* Visitors Chart */

/* Payouts Chart */
var options2 = {
    series: [{
        name: 'Paid',
        data: [60, 50, 40, 45, 60, 50, 37, 39, 55, 52, 33, 30],
        type: 'area',
    }, {
        name: 'UnPaid',
        data: [30, 40, 45, 50, 40, 45, 50, 46, 38, 35, 40, 43],
        type: "area",
        
    }],
    chart: {
        height: 318,
        toolbar: {
            show: false,
        },
        background: 'none',
        fill: "#fff",
    },
    grid: {
        borderColor: '#f2f6f7',
    },
    colors: ["var(--primary03)", "rgba(var(--success-rgb), 0.6)"],
    background: 'transparent',
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 2,
        dashArray: [0, 5],
    },
    xaxis: {
        type: 'month',
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Aug", "Sep", "Oct", "Nov", "Dec"]
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
        position: 'top',
    },
    xaxis: {
        show: false,
        axisBorder: {
            show: false,
            color: 'rgba(119, 119, 142, 0.05)',
            offsetX: 0,
            offsetY: 0,
        },
        axisTicks: {
            show: false,
            borderType: 'solid',
            color: 'rgba(119, 119, 142, 0.05)',
            width: 6,
            offsetX: 0,
            offsetY: 0
        },
        labels: {
            rotate: -90,
        }
    },
    yaxis: {
        show: true,
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        }
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
};
document.getElementById('course-payouts').innerHTML = ''
var chart2 = new ApexCharts(document.querySelector("#course-payouts"), options2);
chart2.render();
function coursePayouts() {
    chart2.updateOptions({
        colors: ["rgb(" + myVarVal + ")", "rgba(var(--success-rgb), 0.6)"],
    })
}
/* Payouts Chart */
