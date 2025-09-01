
/* Performance by category chart */
var options = {
    series: [{
        name: 'Designing',
        data: [44, 55, 41, 67, 22, 43, 44, 55, 41, 67, 22, 43]
    }, {
        name: 'Development',
        data: [13, 23, 20, 8, 13, 27, 13, 23, 20, 8, 13, 27]
    }, {
        name: 'Marketing',
        data: [11, 17, 15, 15, 21, 14, 11, 17, 15, 15, 21, 14]
    }, {
        name: 'Testing',
        type: 'line',
        data: [45, 31, 24, 56, 13, 28, 55, 41, 67, 22, 15, 13]
    }],
    chart: {
        type: 'bar',
        height: 321,
        stacked: true,
        toolbar: {
            show: true
        },
        zoom: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 3,
            left: 0,
            blur: 4,
            color: "#000",
            opacity: 0.1,
        },
    },
    grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 3
    },
    yaxis: {
        max: 100
    },
    responsive: [{
        breakpoint: 480,
        options: {
            legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
            }
        }
    }],
    colors: ["var(--primary-color)", "var(--primary02)", "var(--primary05)", "rgba(var(--success-rgb), 1)"],
    legend: {
        show: true,
        position: 'top',
        markers: {
            size: 5,
            shape: "circle"
        }
    },
    markers: {
        size: 4,
        colors: ['rgba(var(--secondary-rgb), 1)'], 
        strokeColors: '#ffffff',
        strokeWidth: 1,
        hover: {
            size: 5
        }
    },
    stroke: {
        curve: 'smooth',
        width: [0, 0, 0, 2.5],
    },
    plotOptions: {
        bar: {
            columnWidth: "40%",
            borderRadius: 0,
        }
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    fill: {
        opacity: 1
    }
};
document.getElementById('performanceReport').innerHTML = '';
var chart1 = new ApexCharts(document.querySelector("#performanceReport"), options);
chart1.render();
/* Performance by category chart */

/* Hiring Sources chart */
var options = {
    series: [1754, 544, 682, 263],
    labels: ["Jobs Boards", "Referrals", "Media", "Campus"],
    chart: {
        height: 176,
        type: 'donut',
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 5,
            left: 0,
            blur: 3,
            color: "#000",
            opacity: 0.1,
          },
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
    plotOptions: {

        pie: {
            expandOnClick: false,
            donut: {
                size: '70%',
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
    colors: ["var(--primary-color)", "rgba(var(--secondary-rgb), 0.9)", "rgba(var(--success-rgb), 0.85)", "rgba(var(--info-rgb), 0.7)"],
};
document.querySelector("#hiring-src").innerHTML = " ";
var chart = new ApexCharts(document.querySelector("#hiring-src"), options);
chart.render();
/* Hiring Sources chart */


  /* Employees 1 */
  var options = {
    series: [60, 80],
    chart: {
      type: "donut",
      width: 38,
      height: 38,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: "70%",
          background: "transparent",
          labels: {
            show: false,
          },
        },
      },
    },
    colors: ["var(--primary-color)", "var(--primary01)"],
    tooltip: {
      enabled: false,
      fixed: {
        enabled: false,
      },
    },
  };
  var chart = new ApexCharts(document.querySelector("#employee01"), options);
  chart.render();
  /* Employees 1 */

  /* Employees 2 */
  var options = {
    series: [72, 28],
    chart: {
      type: "donut",
      width: 38,
      height: 38,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: "70%",
          background: "transparent",
          labels: {
            show: false,
          },
        },
      },
    },
    colors: ["rgba(var(--secondary-rgb), 1)", "rgba(var(--secondary-rgb), 0.1)"],
    tooltip: {
      enabled: false,
      fixed: {
        enabled: false,
      },
    },
  };
  var chart = new ApexCharts(document.querySelector("#employee02"), options);
  chart.render();
  /* Employees 2*/

  /* Employees 3 */
  var options = {
    series: [66, 34],
    chart: {
      type: "donut",
      width: 38,
      height: 38,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: "70%",
          background: "transparent",
          labels: {
            show: false,
          },
        },
      },
    },
    colors: ["rgba(var(--success-rgb), 1)", "rgba(var(--success-rgb), 0.1)"],
    tooltip: {
      enabled: false,
      fixed: {
        enabled: false,
      },
    },
  };
  var chart = new ApexCharts(document.querySelector("#employee03"), options);
  chart.render();
  /* Employees 3*/

  /* Employees 4 */
  var options = {
    series: [72, 28],
    chart: {
      type: "donut",
      width: 38,
      height: 38,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: "70%",
          background: "transparent",
          labels: {
            show: false,
          },
        },
      },
    },
    colors: ["rgba(var(--info-rgb), 1)", "rgba(var(--info-rgb), 0.1)"],
    tooltip: {
      enabled: false,
      fixed: {
        enabled: false,
      },
    },
  };
  var chart = new ApexCharts(document.querySelector("#employee04"), options);
  chart.render();
  /* Employees 4*/

  /* Employees 5 */
  var options = {
    series: [72, 28],
    chart: {
      type: "donut",
      width: 38,
      height: 38,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
        donut: {
          size: "70%",
          background: "transparent",
          labels: {
            show: false,
          },
        },
      },
    },
    colors: ["rgba(var(--pink-rgb), 1)", "rgba(var(--pink-rgb), 0.1)"],
    tooltip: {
      enabled: false,
      fixed: {
        enabled: false,
      },
    },
  };
  var chart = new ApexCharts(document.querySelector("#employee05"), options);
  chart.render();
  /* Employees 5*/