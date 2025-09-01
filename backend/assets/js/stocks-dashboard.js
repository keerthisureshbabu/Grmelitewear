/* stocks swiper */
var swiper = new Swiper(".swiper-basic", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    speed: 3000, 
    autoplay: {
        delay: 1000,
        disableOnInteraction: false,
    },
    breakpoints: {
        500: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1400: {
            slidesPerView: 4,
            spaceBetween: 20,
        },
        1600: {
            slidesPerView: 4,
            spaceBetween: 20,
        },
        1800: {
            slidesPerView: 4,
            spaceBetween: 20,
        },
    },
  });
    /* stocks swiper */
  
/* Stock Earnings */
var options = {
  series: [{
  name: 'Earnings',
  data: [55, -30, 35, -40, 32, 45, -30
  ]
}],
  chart: {
  type: 'bar',
  height: 256,
  toolbar: {
    show: false
  }
},
grid: {
  borderColor: '#f5f4f4',
  strokeDashArray: 3
},
plotOptions: {
  bar: {
    colors: {
      ranges: [{
        from: -100,
        to: -46,
        color: 'rgba(var(--danger-rgb), 0.9)'
      }, {
        from: -45,
        to: 0,
        color: 'rgba(var(--danger-rgb), 0.9)'
      }]
    },
    columnWidth: '50%',
    borderRadius: 2,
    borderRadiusWhenStacked: 'last',
  }
},
dataLabels: {
  enabled: false,
},
yaxis: {
  tickAmount: 5,
  title: {
    text: 'Earnings',
  },
  labels: {
    formatter: function (y) {
      return y.toFixed(0) + "%";
    }
  }
},
xaxis: {
  type: 'week',
  categories: ['sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri','Sat'],
},
colors: ["rgba(var(--success-rgb), 1)"],
};

var chart = new ApexCharts(document.querySelector("#stocks-earnings"), options);
chart.render();
/* Stock Earnings */

/* Stock Balance */
var options = {
  series: [18780, 16890],
  chart: {
    type: 'donut',
    height: 197,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    pie: {
      donut: {
        size: '72%',
        labels: {
          show: true,
          value: {
            show: true,
            fontSize: '20px',
            fontFamily: "Poppins",
            color: undefined,
            offsetY: 10,
        },
          total: {
            show: true,
            showAlways: true,
            label: 'Total',
            fontSize: '16px',
            fontWeight: 600,
            offsetY: 10,
            fontFamily: "Poppins",
            color: '#495057',
        }
        },
      }
    }
  },
  grid: {
    padding: {
      top: -10
    }
  },
  colors: ["var(--primary-color)", "var(--primary02)"],
  labels: ['Balance', 'Investment'],
};


var chart = new ApexCharts(document.querySelector("#stock-balance"), options);
chart.render();
/* Stock Balance */
  
   /* stocks chart */
    var data = generateDayWiseTimeSeries(new Date("22 Apr 2024").getTime(), 115, {
      min: 30,
      max: 90
    });
    var options1 = {
      chart: {
        id: "chart2",
        type: "area",
        height: 230,
        foreColor: "#ccc",
        toolbar: {
          autoSelected: "pan",
          show: false
        }
      },
      colors: ["var(--primary-color)"],
      stroke: {
        width: 2.5,
        curve: "straight",
        dashArray: 4
      },
      grid: {
        borderColor: "#555",
        clipMarkers: false,
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
        gradient: {
          enabled: true,
          opacityFrom: 0.5,
          opacityTo: 0.1,
          colorStops: [
            [
                {
                    offset: 0,
                    color: "var(--primary05)",
                    opacity: 0.05
                },
                {
                    offset: 75,
                    color: "var(--primary03)",
                    opacity: 1
                },
                {
                    offset: 100,
                    color: 'var(--primary05)',
                    opacity: 1
                }
            ],
        ]
        }
      },
      markers: {
        size: 3,
        strokeWidth: 1.5,
        dashArray: 4
      },
      series: [
        {
          name: 'Investment',
          data: data
        }
      ],
      tooltip: {
        theme: "dark"
      },
      xaxis: {
        type: "datetime"
      },
      yaxis: {
        min: 0,
        tickAmount: 1,
        max: 150
      }
    };
    
    var chart1 = new ApexCharts(document.querySelector("#stockCap-area"), options1);
    
    chart1.render();
    
    var options2 = {
      chart: {
        id: "chart1",
        height: 105,
        type: "bar",
        foreColor: "#ccc",
        brush: {
          target: "chart2",
          enabled: true
        },
        selection: {
          enabled: true,
          fill: {
            color: "#fff",
            opacity: 0.4
          },
          xaxis: {
            min: new Date("05 Jun 2024 10:00:00").getTime(),
            max: new Date("26 Jul 2024 10:00:00").getTime()
          }
        }
      },
      colors: ["rgba(var(--success-rgb), 1)"],
      series: [
        {
          name: 'Market Cap',
          data: data
        }
      ],
      grid: {
        show: false,
        borderColor: "#444"
      },
      markers: {
        size: 0
      },
      xaxis: {
        type: "datetime",
        tooltip: {
          enabled: false
        }
      },
      plotOptions: {
        bar: {
            columnWidth: "70%",
            borderRadius: 3
        }
      },
      yaxis: {
        tickAmount: 2
      }
    };
    
    var chart88 = new ApexCharts(document.querySelector("#stockCap"), options2);
    
    chart88.render();
    
    function generateDayWiseTimeSeries(baseval, count, yrange) {
      var i = 0;
      var series = [];
      while (i < count) {
        var x = baseval;
        var y =
          Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
    
        series.push([x, y]);
        baseval += 76400000;
        i++;
      }
      return series;
    }
     /* stocks chart */