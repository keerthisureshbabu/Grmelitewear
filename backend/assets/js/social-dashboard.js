/* Audience */
var options = {
    series: [{
        name: "Followers",
        data: [56, 44, 66, 55, 68, 90, 55, 68, 55, 66, 44, 56],
        type: 'area',
    },
    {
        name: "Total Views",
        data: [45, 50, 42, 59, 53, 74, 43, 43, 30, 55, 74, 49],
        type: 'area',
    }],
    chart: {
        type: 'area',
        height: 348,
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
    colors: ['var(--primary-color)', "rgba(var(--success-rgb), 1)"],
    stroke: {
        width: [3, 2],
        curve: ["smooth", "smooth"],
        dashArray: [0, 4], 
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
        type: "gradient",
        gradient: {
            opacityFrom: 0.5,
            opacityTo: 0.2,
            stops: [0, 60],
            colorStops: [
                [
                    {
                        offset: 0,
                        color: "var(--primary01)",
                        opacity: 0.2
                      },
                      {
                        offset: 50,
                        color: "var(--primary01)",
                        opacity: 0.2
                      },
                      {
                        offset: 100,
                        color: 'var(--primary01)',
                        opacity: 0.5
                      }
                ],
                [
                    {
                        offset: 0,
                        color: "rgba(var(--success-rgb), .05)",
                        opacity: 1
                      },
                      {
                        offset: 50,
                        color: "rgba(var(--success-rgb), .05)",
                        opacity: 1
                      },
                      {
                        offset: 100,
                        color: 'rgba(var(--success-rgb), .05)',
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
        max: 90,
        min: 20
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
  var chart2 = new ApexCharts(document.querySelector("#audience"), options);
  chart2.render();
  /* Audience */

   /* Profit Analysis 2 */
   var options6 = {
    series: [
        {
            type: 'bar',
            name: 'Profit',
            data: [20, 25, 30, 26, 32, 26, 26, 24, 29, 25, 33, 27],
        },
        {
            type: 'bar',
            name: 'Revenue',
            data: [37, 33, 38, 35, 31, 30, 37, 33, 42, 39, 34, 36],
        }
    ],
    chart: {
        type: 'bar',
        height: 258,
        stacked: true,
        sparkline: {
            enabled: true,
        },
    },
    grid: {
        borderColor: '#f2f6f7',
    },
    colors: ["var(--primary-color)", "var(--primary02)"],
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
        position: 'top',
        offsetY: -5,
        markers: {
            size: 5,
            shape: "circle"
        }
    },
    stroke: {
        width: 0,
    },
    plotOptions: {
        bar: {
            columnWidth: '30%',
            borderRadius: 2,
        },
    },
    yaxis: {
        title: {
            style: {
                color: '#adb5be',
                fontSize: '14px',
                fontFamily: 'poppins, sans-serif',
                fontWeight: 600,
                cssClass: 'apexcharts-yaxis-label',
            },
        },
        labels: {
            formatter: function (y) {
                return y.toFixed(0); 
            },
            
        },
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: true,
            color: 'rgba(119, 119, 142, 0.05)',
        },
        axisTicks: {
            show: true,
            color: 'rgba(119, 119, 142, 0.05)',
        },
        labels: {
            rotate: -45,
        },
    },
    tooltip: {
        enabled: true,
    },
};

var chart1 = new ApexCharts(document.querySelector("#profit-earn-social"), options6);
chart1.render();

  /* Profit Analysis 2 */


      /* Follow-on  device */
      var options = {
        series: [1754, 1234],
        labels: ["This Month", "Last Month"],
        chart: {
            height: 209,
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
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: '80%',
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
                            label: 'Facebook',
                            fontSize: '20px',
                            fontWeight: 600,
                            color: '#495057',
                        }
  
                    }
                }
            }
        },
        colors: ["var(--primary-color)", "var(--primary01)"],
    };
    var chart = new ApexCharts(document.querySelector("#media-traffic"), options);
    chart.render();
    /* Follow-on  device */