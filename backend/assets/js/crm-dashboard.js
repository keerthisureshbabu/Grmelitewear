/* Total Sales */
var spark1 = {
    chart: {
        type: 'line',
        height: 30,
        width: 140,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: 'var(--primary-color)',
            opacity: 0.5
        }
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
        width: 1.5,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [14, 58, 20, 94, 25, 55, 35,55]
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
    colors: ['var(--primary-color)'],
  
  }
  var spark1 = new ApexCharts(document.querySelector("#crmchart01"), spark1);
  spark1.render();
  /* Total Sales */
/* Total Sales */
var spark2 = {
    chart: {
        type: 'line',
        height: 30,
        width: 140,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#ffb748',
            opacity: 0.5
        }
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
        width: 1.5,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [14, 58, 20, 94, 25, 55, 35,55]
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
    colors: ['#ffb748'],
  
  }
  var spark2 = new ApexCharts(document.querySelector("#crmchart02"), spark2);
  spark2.render();
  /* Total Sales */
/* Total Sales */
var spark3 = {
    chart: {
        type: 'line',
        height: 30,
        width: 140,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#35bdaa',
            opacity: 0.5
        }
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
        width: 1.5,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [14, 58, 20, 94, 25, 55, 35,55]
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
    colors: ['#35bdaa'],
  
  }
  var spark3 = new ApexCharts(document.querySelector("#crmchart03"), spark3);
  spark3.render();
  /* Total Sales */
/* Total Sales */
var spark4 = {
    chart: {
        type: 'line',
        height: 30,
        width: 140,
        stacked: true,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#2e8ef7',
            opacity: 0.5
        }
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
        width: 1.5,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [14, 58, 20, 94, 25, 55, 35,55]
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
    colors: ['#2e8ef7'],
  
  }
  var spark4 = new ApexCharts(document.querySelector("#crmchart04"), spark4);
  spark4.render();
  /* Total Sales */


  /* Revenue Chart */
var options = {
    series: [
      {
        name: "This Year",
        data: [25, 40, 32, 59, 32, 45, 30, 55, 65, 45, 35, 45],
      },
      {
        name: "Last Year",
        data: [-8, -30, -25, -32, -45, -30, -20, -35, -28, -43, -30, -40],
      }
    ],
    chart: {
      type: "bar",
      height: 300,
      toolbar: {
        show: false
      },
      stacked: true,
    },
    colors: ["var(--primary-color)","rgba(var(--success-rgb), 1)",],
    fill: {
      type: ['gradient', 'gradient'],
      gradient: {
        type: "vertical",
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary09)",
              opacity: 1
            },
            {
              offset: 75,
              color: "var(--primary08)",
              opacity: 1
            },
            {
              offset: 100,
              color: 'var(--primary09)',
              opacity: 1
            }
          ],
          [
            {
              offset: 0,
              color: 'rgba(var(--success-rgb), 0.9)',
              opacity: 1
            },
            {
              offset: 75,
              color: 'rgba(var(--success-rgb), 0.8)',
              opacity: 1
            },
            {
              offset: 100,
              color: 'rgba(var(--success-rgb), 0.9)',
              opacity: 1
            }
          ],
        ]
      }
    },
    dataLabels: {
      enabled: false,
    },
    plotOptions: {
        bar: {
            columnWidth: '26%',
        },
    },
    legend: {
      show: true,
      position: "top",
      offsetX: 0,
      offsetY: 8,
      markers: {
        size: 5,
        shape: "circle",
        strokeWidth: 0,
      },
    },
    stroke: {
      curve: 'smooth',
      lineCap: 'round',
    },
    grid: {
      borderColor: "#edeef1",
      strokeDashArray: 2,
    },
    yaxis: {
      axisBorder: {
        show: true,
        color: "rgba(70, 216, 227, 0.05)",
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: "solid",
        color: "rgba(70, 216, 227, 0.05)",
        width: 6,
        offsetX: 0,
        offsetY: 0,
      },
      labels: {
        formatter: function (y) {
          return y.toFixed(0) + "";
        },
      },
    },
    xaxis: {
      type: "month",
      categories: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "sep",
        "oct",
        "nov",
        "dec",
      ],
      axisBorder: {
        show: false,
        color: "rgba(70, 216, 227, 0.05)",
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: false,
        borderType: "solid",
        color: "rgba(70, 216, 227, 0.05)",
        width: 6,
        offsetX: 0,
        offsetY: 0,
      },
      labels: {
        rotate: -90,
      },
    },
};
var chart4 = new ApexCharts(document.querySelector("#salerevenue"), options);
chart4.render();
  /* Revenue Chart */

/* crm Profit Report Chart */
var options = {
series: [1624, 1267, 1153],
labels: ["Profit", "Revenue", "Expenses"],
chart: {
    height: 150,
    type: 'donut',
},
dataLabels: {
    enabled: false,
},
legend: {
    show: false,
},
stroke: {
    show: true,
    curve: 'smooth',
    lineCap: 'round',
    colors: "#fff",
    width: 0,
    dashArray: 0,
},
fill: {
    type: 'solid',
},
plotOptions: {

    pie: {
    expandOnClick: false,
    donut: {
        size: '85%',
        background: 'transparent',
        labels: {
        show: true,
        name: {
            show: true,
            fontSize: '20px',
            color: '#495057',
            offsetY: -4
        },
        value: {
            show: true,
            fontSize: '18px',
            color: undefined,
            offsetY: 8,
            formatter: function (val) {
            return val + "%"
            }
        },
        total: {
            show: true,
            showAlways: true,
            label: 'Total',
            fontSize: '22px',
            fontWeight: 600,
            color: '#495057',
        }

        }
    }
    }
},
colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)", "rgba(var(--secondary-rgb), 1)"],
};
document.querySelector("#crmprofit-report").innerHTML = " ";
var chart = new ApexCharts(document.querySelector("#crmprofit-report"), options);
chart.render();
/* crm Profit Report Chart */

/*Webtraffic Chart */
var options = {
    series: [{
        name: 'Total Visits',
        data: [15000]
    }, {
        name: 'Unique Visitors',
        data: [8000]
    }, {
        name: 'Bounce Rate',
        data: [4000]
    }],
    chart: {
        type: 'bar',
        height: 340,
        toolbar: {
            show : false
        }
    },
    xaxis: {
        categories: ['Traffic Metrics'],
        labels: {
            show: true
        }
    },
    yaxis: {
        title: {
            text: 'Number of Visitors'
        },
        min: 0,
        max: 16000,
        labels: {
            show: false
        }
    },
    dataLabels: {
        enabled: true
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '78%',
            endingShape: 'rounded',
            borderRadius: 0
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'center',
        floating: false,
        markers: {
            size: 6,
            shape: "circle",
        },
    },
    fill: {
        opacity: 0.9,
    },
    colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)", "rgba(var(--secondary-rgb), 1)"],
};
document.querySelector("#crm-webtraffic").innerHTML = " ";
var chart01 = new ApexCharts(document.querySelector("#crm-webtraffic"), options);
chart01.render();

/*Webtraffic Chart */