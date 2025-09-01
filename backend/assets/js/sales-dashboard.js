(function () {
  "use strict";

  /* Revenue Statistics */
  var options1 = {
    chart: {
      height: 225,
      type: 'radialBar',
      responsive: 'true',
      offsetX: 0,
      offsetY: -10,
      zoom: {
        enabled: false
      }
    },
    grid: {
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
        left: 0
      },
    },
    plotOptions: {
      radialBar: {
        startAngle: -135,
        endAngle: 135,
        track: {
          strokeWidth: "80%",
        },
        hollow: {
          size: "55%"
        },
        dataLabels: {
          name: {
            fontSize: '15px',
            color: undefined,
            offsetY: 20,
            fontWeight: [400]
          },
          value: {
            offsetY: -20,
            fontSize: '22px',
            color: undefined,
            fontWeight: [600],
            formatter: function (val) {
              return val + "%";
            }
          }
        }
      }
    },
    colors: ["var(--primary-color)"],
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        type: "horizontal",
        gradientToColors: ["rgb(53, 189, 170)"],
        opacityFrom: 1,
        opacityTo: 1,
        stops: [0, 100]
      }
    },
    stroke: {
      dashArray: 4
    },
    labels: ['Revenue'],
    series: [83],
  };
  var options1 = new ApexCharts(document.querySelector("#revenue-statistics"), options1);
  options1.render();
  /* Revenue Statistics */

  /* Revenue Statistics1 */
  var options6 = {
    series: [{
      name: 'Profit',
      data: [30, 25, 30, 36, 32, 36, 36, 34, 39, 42, 33, 37, 30, 31, 35, 38, 33, 37],
    },
    ],
    chart: {
      type: 'area',
      height: 250,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["var(--primary-color)"],
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
    },
    stroke: {
      width: [0],
      curve: 'smooth',
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        type: "horizontal",
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary-color)",
              opacity: 0.05
            },
            {
              offset: 25,
              color: "var(--primary-color)",
              opacity: 0.05
            },
            {
              offset: 50,
              color: "var(--primary-color)",
              opacity: 0.05
            },
            {
              offset: 75,
              color: "rgb(53, 189, 170)",
              opacity: 0.05
            },
            {
              offset: 100,
              color: 'rgb(53, 189, 170)',
              opacity: 0.05
            }
          ]
        ]
      }
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#revenue-statistics1"), options6);
  chart1.render();
  /* Revenue Statistics1 */

  /* Profit Analysis */
  var options5 = {
    series: [{
      name: 'Profit',
      data: [24, 22, 20, 26, 28, 35, 28, 23, 28, 34, 30, 34, 34, 32, 37, 40, 31, 35],
    },
    ],
    chart: {
      type: 'area',
      height: 120,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["rgba(255,255,255,0.6)"],
    plotOptions: {
      bar: {
        columnWidth: '40%'
      }
    },
    stroke: {
      width: [0],
      curve: 'smooth',
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary-color)",
              opacity: 0.3
            },
            {
              offset: 75,
              color: "var(--primary-color)",
              opacity: 0.3
            },
            {
              offset: 100,
              color: 'var(--primary-color)',
              opacity: 0.3
            }
          ]
        ]
      }
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#profit-analysis"), options5);
  chart1.render();
  /* Profit Analysis */

  /* Profit Analysis 1 */
  var options5 = {
    series: [{
      name: 'Profit',
      data: [34, 32, 37, 36, 38, 35, 38, 43, 48, 64, 60, 54, 54, 42, 57, 66, 41, 55, 60, 54, 66, 75, 82, 75, 43, 31, 42, 47, 33, 42, 57, 41, 64, 42, 65, 55],
    },
    ],
    chart: {
      type: 'bar',
      height: 90,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["var(--primary-color)"],
    plotOptions: {
      bar: {
        columnWidth: '50%'
      }
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#profit-analysis1"), options5);
  chart1.render();
  /* Profit Analysis 1 */

  /* Profit Analysis 2 */
  var options6 = {
    series: [{
      name: 'Profit',
      data: [30, 25, 30, 36, 32, 36, 36, 34, 39, 42, 33, 37, 30, 31, 35, 38, 33, 37],
    },
    ],
    chart: {
      type: 'area',
      height: 140,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["var(--primary-color)"],
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
    },
    stroke: {
      width: [0],
      curve: 'smooth',
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary-color)",
              opacity: 0.2
            },
            {
              offset: 75,
              color: "var(--primary-color)",
              opacity: 0.2
            },
            {
              offset: 100,
              color: 'var(--primary-color)',
              opacity: 0.2
            }
          ]
        ]
      }
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#profit-analysis2"), options6);
  chart1.render();
  /* Profit Analysis 2 */

  /* Sales Statistics */
  var options = {
    series: [{
      name: 'Profit',
      data: [44, 55, 41, 67, 42, 22, 43, 21, 41, 56, 27, 43],
      type: 'column',
    }, {
      name: 'Revenue',
      data: [30, 25, 46, 28, 21, 45, 35, 64, 52, 59, 36, 39],
      type: 'area',
    }, {
      name: 'Sales',
      data: [23, 11, 22, 35, 17, 28, 22, 37, 21, 44, 22, 30],
      type: 'area',
    },],
    chart: {
      height: 352,
      type: 'line',
      toolbar: {
        show: false,
      },zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 6,
        left: 0,
        blur: 4,
        color: ["transparent", "rgb(255, 183, 72)", "rgb(53, 189, 170)"],
        opacity: 0.15
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 3,
        columnWidth: "30%",
      }
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
    dataLabels: {
      enabled: false
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary-color)",
              opacity: 1
            },
            {
              offset: 75,
              color: "var(--primary-color)",
              opacity: 1
            },
            {
              offset: 100,
              color: 'var(--primary-color)',
              opacity: 1
            }
          ],
          [
            {
              offset: 0,
              color: "rgb(255, 183, 72)",
              opacity: 0.025
            },
            {
              offset: 75,
              color: "rgb(255, 183, 72)",
              opacity: 0.025
            },
            {
              offset: 100,
              color: 'rgb(255, 183, 72)',
              opacity: 0.025
            }
          ],
          [
            {
              offset: 0,
              color: 'rgb(53, 189, 170)',
              opacity: 0.025
            },
            {
              offset: 75,
              color: 'rgb(53, 189, 170)',
              opacity: 0.025
            },
            {
              offset: 100,
              color: 'rgb(53, 189, 170)',
              opacity: 0.025
            }
          ],
        ]
      }
    },
    legend: {
      position: 'top',
      fontSize: '14px',
      fontWeight: 500,
      fontFamily: 'Poppins, sans-serif',
      markers: {
        width: 9,
        height: 9,
        strokeWidth: 0,
        strokeColor: '#fff',
        fillColors: undefined,
        radius: 12,
        customHTML: undefined,
        onClick: undefined,
        offsetX: 0,
        offsetY: 0
      },
    },
    colors: ["var(--primary-color)", "rgb(255, 183, 72)", "rgb(53, 189, 170)"],
    stroke: {
      width: [0, 2.5, 2.5],
      curve: 'smooth',
    },
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    tooltip: {
      shared: true,
    }
  };
  var chart = new ApexCharts(document.querySelector("#sales-statistics"), options);
  chart.render();
  /* Sales Statistics */

  /* Expense */
  var options7 = {
    series: [75],
    chart: {
      type: 'radialBar',
      width: 65,
      height: 65,
      sparkline: {
        enabled: true
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
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
      radialBar: {
        hollow: {
          margin: 0,
          size: '70%',
        },
        track: {
          margin: 0
        },
        dataLabels: {
          show: false
        }
      }
    },
    colors: ["rgb(255, 183, 72)"],
  };
  var chart7 = new ApexCharts(document.querySelector("#expense"), options7);
  chart7.render();
  /* Expense */

  /* Income */
  var options7 = {
    series: [75],
    chart: {
      type: 'radialBar',
      width: 65,
      height: 65,
      sparkline: {
        enabled: true
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'round',
      colors: "#fff",
      width: 0,
    },
    plotOptions: {
      radialBar: {
        hollow: {
          margin: 0,
          size: '70%',
        },
        track: {
          margin: 0
        },
        dataLabels: {
          show: false
        }
      }
    },
    colors: ["rgb(53, 189, 170)"],
  };
  var chart7 = new ApexCharts(document.querySelector("#income"), options7);
  chart7.render();
  /* Income */

  /* Total Income Background */
  var options5 = {
    series: [{
      name: 'Profit',
      data: [34, 32, 37, 36, 38, 35, 38, 43, 48, 64, 60, 54, 54, 42, 57, 66, 41, 55, 60, 54, 66, 75, 82, 75, 43, 31, 42, 47, 33, 42, 57, 41, 64, 42, 65, 55],
    },
    ],
    chart: {
      type: 'area',
      height: 80,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["rgba(53, 189, 170,0.6)"],
    plotOptions: {
      bar: {
        columnWidth: '40%'
      }
    },
    stroke: {
      width: [0],
      curve: 'smooth',
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
          [
            {
              offset: 0,
              color: "rgb(53, 189, 170)",
              opacity: 0.15
            },
            {
              offset: 75,
              color: "rgb(53, 189, 170)",
              opacity: 0.15
            },
            {
              offset: 100,
              color: 'rgb(53, 189, 170)',
              opacity: 0.15
            }
          ]
        ]
      }
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#income-chart"), options5);
  chart1.render();
  /* Total Income Background */

  /* Total Expenditure Background */
  var options5 = {
    series: [{
      name: 'Profit',
      data: [34, 32, 37, 36, 38, 35, 38, 43, 48, 64, 60, 54, 54, 42, 57, 66, 41, 55, 60, 54, 66, 75, 82, 75, 43, 31, 42, 47, 33, 42, 57, 41, 64, 42, 65, 55],
    },
    ],
    chart: {
      type: 'bar',
      height: 50,
      stacked: true,
      sparkline: {
        enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["rgba(255, 183, 72, 0.15)"],
    plotOptions: {
      bar: {
        columnWidth: '40%'
      }
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
      position: 'top',
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
          return y.toFixed(0) + "";
        }
      }
    },
    xaxis: {
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
      axisBorder: {
        show: true,
        color: 'rgba(119, 119, 142, 0.05)',
        offsetX: 0,
        offsetY: 0,
      },
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: 'rgba(119, 119, 142, 0.05)',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      labels: {
        rotate: -90
      }
    },
    tooltip: {
      enabled: false,
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#expenditure-chart"), options5);
  chart1.render();
  /* Total Expenditure Background */

})();