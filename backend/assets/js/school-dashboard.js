  /*  Start::students */
  var options = {
    series: [
      {
        data: [10, 25, 28, 35, 28, 45, 35, 40, 50, 35, 55, 55, 65],
      },
    ],
    chart: {
      height: 120,
      width: 180,
      type: 'area',
      zoom: {
          enabled: false
      },
      sparkline: {
          enabled: true
      }
  },
  tooltip: {
      enabled: true,
      x: {
          show: false
      },
      y: {
          title: {
              formatter: function (seriesName) {
                  return ''
              }
          }
      },
      marker: {
          show: false
      }
  },
  dataLabels: {
      enabled: false
  },
  grid: {
      borderColor: 'transparent',
  },
  xaxis: {
      crosshairs: {
          show: false,
      }
  },
  yaxis: { 
    min: 10
  },
  colors: ["var(--primary-color)"],
  stroke: {
      width: [2],
  },
  fill: {
    opacity: 0.001,
    type: ['gradient'],
    gradient: {
      shade: 'light',
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: ['var(--primary01)'],
      inverseColors: true,
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
                opacity: 0.5
            },
            {
                offset: 100,
                color: 'var(--primary02)',
                opacity: 1
            }
        ],
      ]
    }
  }
  };
  var chart = new ApexCharts(document.querySelector("#schl-chart1"), options);
  chart.render();
    /*  End::students */

  /*  Start::teachers */
  var options = {
    series: [
      {
        data: [10, 25, 28, 35, 28, 45, 35, 40, 50, 35, 55, 55, 65],
      },
    ],
    chart: {
      height: 120,
      width: 180,
      type: 'area',
      zoom: {
          enabled: false
      },
      sparkline: {
          enabled: true
      }
  },
  tooltip: {
      enabled: true,
      x: {
          show: false
      },
      y: {
          title: {
              formatter: function (seriesName) {
                  return ''
              }
          }
      },
      marker: {
          show: false
      }
  },
  dataLabels: {
      enabled: false
  },
  grid: {
      borderColor: 'transparent',
  },
  xaxis: {
      crosshairs: {
          show: false,
      }
  },
  yaxis: { 
    min: 10
  },
  colors: ["rgba(var(--secondary-rgb), 1)"],
  stroke: {
      width: [2],
  },
  fill: {
    opacity: 0.001,
    type: ['gradient'],
    gradient: {
      shade: 'light',
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: ['rgba(var(--secondary-rgb), .1)'],
      inverseColors: true,
      opacityFrom: 0.4,
      opacityTo: 0.1,
      stops: [0, 90, 100],
      colorStops: [
        [
            {
                offset: 0,
                color: "rgba(var(--secondary-rgb), .9)",
                opacity: 1
            },
            {
                offset: 75,
                color: "rgba(var(--secondary-rgb), .8)",
                opacity: 0.5
            },
            {
                offset: 100,
                color: 'rgba(var(--secondary-rgb), .2)',
                opacity: 1
            }
        ],
      ]
    }
  }
  };
  var chart = new ApexCharts(document.querySelector("#schl-chart2"), options);
  chart.render();
    /*  End::teachers */

  /*  Start::staff */
  var options = {
    series: [
      {
        data: [10, 25, 28, 35, 28, 45, 35, 40, 50, 35, 55, 55, 65],
      },
    ],
    chart: {
      height: 120,
      width: 180,
      type: 'area',
      zoom: {
          enabled: false
      },
      sparkline: {
          enabled: true
      }
  },
  tooltip: {
      enabled: true,
      x: {
          show: false
      },
      y: {
          title: {
              formatter: function (seriesName) {
                  return ''
              }
          }
      },
      marker: {
          show: false
      }
  },
  dataLabels: {
      enabled: false
  },
  grid: {
      borderColor: 'transparent',
  },
  xaxis: {
      crosshairs: {
          show: false,
      }
  },
  yaxis: { 
    min: 10
  },
  colors: ["rgba(var(--success-rgb), 1)"],
  stroke: {
      width: [2],
  },
  fill: {
    opacity: 0.001,
    type: ['gradient'],
    gradient: {
      shade: 'light',
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: ['rgba(var(--success-rgb), .1)'],
      inverseColors: true,
      opacityFrom: 0.4,
      opacityTo: 0.1,
      stops: [0, 90, 100],
      colorStops: [
        [
            {
                offset: 0,
                color: "rgba(var(--success-rgb), .9)",
                opacity: 1
            },
            {
                offset: 75,
                color: "rgba(var(--success-rgb), .8)",
                opacity: 0.5
            },
            {
                offset: 100,
                color: 'rgba(var(--success-rgb), .2)',
                opacity: 1
            }
        ],
      ]
    }
  }
  };
  var chart = new ApexCharts(document.querySelector("#schl-chart3"), options);
  chart.render();
    /*  End::staff */

  /*  Start::revenue */
  var options = {
    series: [
      {
        data: [10, 25, 28, 35, 28, 45, 35, 40, 50, 35, 55, 55, 65],
      },
    ],
    chart: {
      height: 120,
      width: 180,
      type: 'area',
      zoom: {
          enabled: false
      },
      sparkline: {
          enabled: true
      }
  },
  tooltip: {
      enabled: true,
      x: {
          show: false
      },
      y: {
          title: {
              formatter: function (seriesName) {
                  return ''
              }
          }
      },
      marker: {
          show: false
      }
  },
  dataLabels: {
      enabled: false
  },
  grid: {
      borderColor: 'transparent',
  },
  xaxis: {
      crosshairs: {
          show: false,
      }
  },
  yaxis: { 
    min: 10
  },
  colors: ["rgba(var(--info-rgb), 1)"],
  stroke: {
      width: [2],
  },
  fill: {
    opacity: 0.001,
    type: ['gradient'],
    gradient: {
      shade: 'light',
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: ['rgba(var(--info-rgb), .1)'],
      inverseColors: true,
      opacityFrom: 0.4,
      opacityTo: 0.1,
      stops: [0, 90, 100],
      colorStops: [
        [
            {
                offset: 0,
                color: "rgba(var(--info-rgb), .9)",
                opacity: 1
            },
            {
                offset: 75,
                color: "rgba(var(--info-rgb), .8)",
                opacity: 0.5
            },
            {
                offset: 100,
                color: 'rgba(var(--info-rgb), .2)',
                opacity: 1
            }
        ],
      ]
    }
  }
  };
  var chart = new ApexCharts(document.querySelector("#schl-chart4"), options);
  chart.render();
    /*  End::revenue */
  
  /*  Start::audienceMetric */
  var options4 = {
    series: [
      {
        type: "bar",
        name: "Income",
        data: [50, 35, 30, 70, 56, 84, 90, 56, 85, 95, 75, 86]
      },
      {
        type: "area",
        name: "Expenses",
        data: [32, 56, 28, 48, 30, 56, 56, 100, 40, 50, 68, 40]
      },
    ],
    chart: {
      type: "area",
      height: 280,
      animations: {
        speed: 500,
      },
      toolbar: {
        show: false,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 8,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    colors: ["var(--primary-color)", "rgba(53, 189, 170, 1)"],
    dataLabels: {
      enabled: false,
    },
    grid: {
      borderColor: "#f1f1f1",
      strokeDashArray: 3,
    },
    markers: {
      size: 4,
      hover: {
          size: 6
      },
      discrete: [{
        fillColor: '#fff',
        strokeColor: 'var(--primary-color)',
        size: 3,
        shape: "circle"
      },
      ],
    },
    fill: {
      type: ['soild','gradient'],
      shade: "dark",
            shadeIntensity: 0.1,
            inverseColors: false,
            gradientToColors: ["var(--primary-color)" ,"rgb(255, 183, 72)"],
            opacityFrom: [1, 0.4],
            opacityTo: [1, 0.07],
            stops: [0, 90, 100]
    },
    stroke: {
      curve: ["smooth", "smooth"],
      width: [0, 2],
      dashArray: [0, 3],
    },
    xaxis: {
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return "$" + value;
        },
      },
      max: 110
    },
    plotOptions: {
      bar: {
        columnWidth: "18%",
        borderRadius: "3",
      },
    },
    legend: {
      show: true,
      position: "top",
      inverseOrder: true,
      markers: {
        size: 5,
        shape: "circle",
        strokeWidth: 0
      }
    },
  };
  document.getElementById("audienceMetric").innerHTML = "";
  var chart4 = new ApexCharts(document.querySelector("#audienceMetric"), options4);
  chart4.render();
  /*  End::audienceMetric */

  /* attendance chart */
var options = {
  series: [800, 300, 347],
  labels: ["Present", "Absent","On leave"],
  chart: {
      height: 225,
      type: 'donut',
      dropShadow: {
          enabled: true,
          enabledOnSeries: undefined,
          top: 5,
          left: 0,
          blur: 3,
          color: "#000",
          opacity: 0.01,
        },
  },
  dataLabels: {
    enabled: true,
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
              size: '63%',
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
  colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)", "rgba(var(--primary-rgb), .08)"],
};
document.querySelector("#attendance").innerHTML = " ";
var chart = new ApexCharts(document.querySelector("#attendance"), options);
chart.render();
/* attendance chart */
