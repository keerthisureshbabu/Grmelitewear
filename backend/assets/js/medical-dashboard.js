/*Medical Revenue Chart */
var options = {
    series: [
        {
            name: 'Profit',
            data: [20, 23, 26, 22, 25, 26, 28, 26, 22, 27, 25, 26],
            type: 'bar',
        },
        {
            name: 'Income',
            data: [24, 23, 20, 25, 27, 26, 24, 23, 23, 25, 23, 23],
            type: 'area',
        },
    ],
    chart: {
        height: 336,
        zoom: {
            enabled: false
        },
    },
    dataLabels: {
        enabled: false,
        show: true,
    },
    grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 5
    },
    legend: {
        show: true,
        position: 'top',
    },
    markers: {
      size: 7,
  },
  fill: {
    type: ['gradient','gradient'],
    gradient: {
        type: 'vertical',
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100],
        colorStops: [
            [
              {
                offset: 0,
                color: "var(--primary-color)",
                opacity: 0.7
              },
              {
                offset: 10,
                color: "var(--primary08)",
                opacity: 0.5
              },
              {
                offset: 100,
                color: 'var(--primary03)',
                opacity: 0.3
              }
            ],
            [
              {
                offset: 0,
                color: 'rgba(var(--success-rgb), .5)',
                opacity: 0.6
              },
              {
                offset: 10,
                color: 'rgba(var(--success-rgb), 0.3)',
                opacity: 0.5
              },
              {
                offset: 100,
                color: 'rgba(var(--success-rgb), 0.1)',
                opacity: 0.3
              }
            ],
          ]
      },
      
  },
    plotOptions: {
        bar: {
            borderRadius: 6,
            columnWidth: "25%",
        }
    },
    colors: ["var(--primary-color)", "rgba(53, 189, 170, 1)"],
    stroke: {
        curve: ['smooth','smooth'],
        width: [0 , 3],
        colors: ["rgba(var(--secondary-rgb), 1)", "rgba(var(--secondary-rgb), 1)"],
        dashArray:[0, 4]
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: {
        min: 0
    }
  };
  document.getElementById('medical-revenue').innerHTML = '';
  var chart3 = new ApexCharts(document.querySelector("#medical-revenue"), options);
  chart3.render();
  function countrySessions() {
    chart3.updateOptions({
        colors: ["rgb(" + myVarVal + ")", "rgba(53, 189, 170, 1)"],
    })
  }
  /*Medical Revenue Chart */


  //Patients//
  var options = {
    series: [90, 80],
    chart: {
      height: 282,
      type: 'radialBar',
    },
    plotOptions: {
      radialBar: {
        background: '#000',
        startAngle: -180,
        endAngle: 180,
        hollow: {
          margin: 15,
          size: '70%',
          background: '#000'
        },
        track: {
          background: '#000', 
          strokeWidth: '10',
          margin: 6,
        },
        dataLabels: {
          name: {
            fontSize: '25px',
          },
          value: {
            fontSize: '16px',
            fontWeight: "bold"
          },
          total: {
            show: true,
            label: 'Total',
            formatter: function (w) {
              return 249;
            }
          }
        }
      }
    },
    stroke: {
      dashArray: 4,
      width:5
    },
    colors: [
      "var(--primary-color)",
      "rgba(var(--success-rgb), 1)",
    ],
    labels: ['Female', 'Male'],
  };
  
  
  var chart = new ApexCharts(document.querySelector("#patientsdailyvisit"), options);
  chart.render();
  //Patients//