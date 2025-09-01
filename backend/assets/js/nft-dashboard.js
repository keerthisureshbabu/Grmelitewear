/*  sales-nft chart */
var options = {
    series: [75],
    chart: {
        height: 283,
        type: 'radialBar',
        toolbar: {
            show: true
        }
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 225,
            hollow: {
                margin: 0,
                size: '65%',
                background: '#fff',
                image: undefined,
                imageOffsetX: 0,
                imageOffsetY: 0,
                position: 'front',
            },
            track: {
                background: '#fff',
                strokeWidth: '67%',
                margin: 0,
            },

            dataLabels: {
                show: true,
                name: {
                    offsetY: -10,
                    show: true,
                    color: '#888',
                    fontSize: '17px'
                },
                value: {
                    formatter: function (val) {
                        return parseInt(val);
                    },
                    color: '#111',
                    fontSize: '36px',
                    show: true,
                }
            }
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            type: 'horizontal',
            shadeIntensity: 0.5,
            gradientToColors: ['var(--primary-color)'],
            inverseColors: true,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100]
        }
    },
    stroke: {
        lineCap: 'round'
    },
colors: ["rgb(53, 189, 170)"],
    labels: ['Percent'],
};
var chart = new ApexCharts(document.querySelector("#sales-nft"), options);
chart.render();
/* sales-nft  chart*/

/* NFT balance Chart */
var options = {
    series: [{
      data: [98, 110, 80, 145, 105, 112, 87, 150, 102, 98, 110, 80,]
    }],
    chart: {
      height: 115,
      type: 'area',
      fontFamily: 'Roboto, Arial, sans-serif',
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
    title: {
      text: undefined,
    },
    grid: {
      borderColor: 'transparent',
    },
    xaxis: {
      crosshairs: {
        show: false,
      }
    },
    colors: ["var(--primary-color)"],
    stroke: {
      width: [1.75],
      curve: 'straight',
      dashArray: [2]
    },
    markers: {
        size: 4,
        hover: {
          size: 9
        },
        dropShadow: {
            enabled: true, 
            top: 1,
            left: 1, 
            blur: 3,
            opacity: 0.2
        }
    },
    fill: {
      gradient: {
        enabled: true,
        opacityFrom: 0.5,
        opacityTo: 0.1,
        colorStops: [
          [
              {
                  offset: 0,
                  color: "var(--primary01)",
                  opacity: 0.05
              },
              {
                  offset: 75,
                  color: "var(--primary01)",
                  opacity: 1
              },
              {
                  offset: 100,
                  color: 'var(--primary01)',
                  opacity: 1
              }
          ],
      ]
      }
    },
};
document.getElementById('nft-balance').innerHTML = '';
var chart1 = new ApexCharts(document.querySelector("#nft-balance"), options);
chart1.render();
/* NFT balance Chart */