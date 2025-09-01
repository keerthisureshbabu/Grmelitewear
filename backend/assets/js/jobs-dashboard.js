var myElement1 = document.getElementById('recent-jobs');
new SimpleBar(myElement1, { autoHide: true });

/* Candidates Chart */
var options = {
    series: [1657, 1645],
    labels: ["Female", "Male"],
    chart: {
        height: 248,
        type: 'donut'
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
                size: '75%',
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
    colors: ["var(--primary-color)", "rgb(53, 189, 170)"],

};
document.querySelector("#candidates-chart").innerHTML = " ";
var chart = new ApexCharts(document.querySelector("#candidates-chart"), options);
chart.render();
/* Candidates Chart */


var options = {
    series: [{
        name: 'Interviews',
        type: 'column',
        data: [200, 170, 250, 240, 220, 280, 170, 155, 130, 242, 160, 80],
    }, {
        name: 'Rejected',
        type: 'area',
        data: [150, 110, 300, 170, 190, 230, 120, 120, 270, 200, 200, 150],
    }],
    chart: {
        type: 'area',
        height: 315,
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: ['smooth', 'smooth'],
        dashArray: [0, 3],
        width: [0, 1],
    },
    plotOptions: {
        bar: {
            columnWidth: "20%",
            borderRadius: 6,
        }
    },
  
    colors: ["var(--primary-color)", "rgb(255, 183, 72)"],
    fill: {
        type: ["solid", "gradient"],
        gradient: {
            shade: "dark",
            shadeIntensity: 0.1,
            inverseColors: false,
            gradientToColors: ["var(--primary-color)" ,"rgb(255, 183, 72)"],
            opacityFrom: [1, 0.4],
            opacityTo: [1, 0.07],
            stops: [0, 90, 100]
        }
    },
    grid: {
        borderColor: "#f1f1f1",
        strokeDashArray: 3,
      },
  };
  
document.querySelector("#salerevenue").innerHTML = " ";
  var chart = new ApexCharts(document.querySelector("#salerevenue"), options);
  chart.render();