
var options1 = {
    series: [{
        name: 'Total Orders',
        type: 'line',
        data: [40, 45, 22, 22, 35, 35, 50, 65, 50, 56, 34, 57],
    }, {
        name: 'Revenue',
        data: [20, 46, 46, 28, 28, 55, 30, 45, 60, 30, 46, 16],
        type: 'line',
      }, {
        name: 'Profit',
        data: [20, 36, 46, 28, 28, 35, 20, 35, 22, 30, 36, 36],
        type: 'bar',
      }],
    chart: {
        // type: 'area',
        height: 414,
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 5,
            left: 0,
            blur: 3,
            color: "#000",
            opacity: 0.1,
        },
        toolbar: false
    },
    grid: {
        borderColor: '#f2f6f7',
        strokeDashArray: 5,
    },
    colors: ["var(--primary-color)", "rgba(255, 183, 72, 1)", "rgba(53, 189, 170, 1)"],
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
    },
    plotOptions: {
        bar: {
            columnWidth: '20%',
            borderRadius: "4",
        }
    },
    stroke: {
        curve: ["smooth","straight","smooth"],
        width: [3, 2, 0],
        dashArray: [0, 4, 0],
      },
    yaxis: {
        title: {
            style: {
                color: '#adb5be',
                fontSize: '12px',
                fontFamily: 'Poppins, sans-serif',
                fontWeight: 500,
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
    }
};
document.getElementById('earnings').innerHTML = '';
var chart1 = new ApexCharts(document.querySelector("#earnings"), options1);
chart1.render();

//NewCutomers overview//
var options = { 
  series: [{
      name: 'Last Week',
      data: [650, 770, 890, 840, 1380, 1100]
  },{
    name: 'This Week',
    data: [500, 650, 820, 720, 1280, 1050]
}],
  chart: {
      height: 225,
      type: 'bar',
      toolbar: {
          show: false,
      }
  },
  colors: ['var(--primary-color)',"rgba(53, 189, 170, 0.5)"],
  plotOptions: {
      bar: {
          barHeight: '70%',
          horizontal: true,
          borderRadius: 2,
      }
  },
  stroke: {
    width: 1,
  },
  dataLabels: {
      enabled: false
  },
  legend: {
      show: true,
      position: 'top',
      markers: {
        size: 5,
        shape: "circle"
      }
  },
  grid: {
      borderColor: '#f1f1f1',
      strokeDashArray: 3
  },
  xaxis: {
      categories: [
          ['Monday'],
          ['Tuesday'],
          ['Wedensday'],
          ['Thursday'],
          ['Friday'],
          ['Saturday'],
      ],
      labels: {
          show: false,
          style: {
              fontSize: '12px'
          },
      }
  },
  yaxis: {
      offsetX: 30,
      offsetY: 30,
      labels: {
          show: true,
          style: {
              colors: "#8c9097",
              fontSize: '11px',
              fontWeight: 500,
              cssClass: 'apexcharts-yaxis-label',
          },
          offsetY: 8,
      }
  },
  tooltip: {
      enabled: true,
      shared: false,
      intersect: true,
      x: {
          show: false
      },
      theme: "dark",
  },
};
var chart6 = new ApexCharts(document.querySelector("#newCutomers"), options);
chart6.render();
//NewCutomers overview//
