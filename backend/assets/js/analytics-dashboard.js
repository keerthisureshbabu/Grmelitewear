/* Total Users Chart */
var spark1 = {
    chart: {
        type: 'line',
        height: 50,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
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
        curve: 'straight',
        width: 2,
        dashArray: 1,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [0, 21, 54, 38, 56, 24, 65, 25, 53, 33, 70]
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
document.getElementById('analytics-users').innerHTML = '';
var spark1 = new ApexCharts(document.querySelector("#analytics-users"), spark1);
spark1.render();
/* Total Users Chart */

/* Total Income Chart */
var spark2 = {
    chart: {
        type: 'line',
        height: 50,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
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
        curve: 'straight',
        width: 2,
        dashArray: 1,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [0, 21, 54, 38, 56, 24, 65, 25, 56, 33, 70]
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
    colors: ['rgba(var(--success-rgb), 1)'],

}
document.getElementById('analytics-income').innerHTML = '';
var spark2 = new ApexCharts(document.querySelector("#analytics-income"), spark2);
spark2.render();
/* Total Income Chart */

/* Total Revenue Chart */
var spark3 = {
    chart: {
        type: 'line',
        height: 50,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
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
        curve: 'straight',
        width: 2,
        dashArray: 1,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [0, 21, 54, 38, 56, 24, 65, 25, 52, 33, 10, 70]
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
    colors: ['rgba(var(--secondary-rgb), 1)'],

}
document.getElementById('analytics-revenue').innerHTML = '';
var spark3 = new ApexCharts(document.querySelector("#analytics-revenue"), spark3);
spark3.render();
/* Total Revenue Chart */

/* Bounce rate Chart */
var total = {
    chart: {
        type: 'line',
        height: 66,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 1,
            color: '#fff',
            opacity: 0.05
        }
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        width: 2,
        dashArray: [0, 4],
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [65, 38, 56, 22, 65, 96, 53, 45, 62, 80, 35, 48]
    },
    {
        name: 'Value',
        data: [25, 88, 62, 80, 35, 48, 54, 100, 56, 35, 65, 50]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        axisBorder: {
            show: false
        },
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ["var(--primary-color)", "rgba(var(--secondary-rgb), 1)"],
    tooltip: {
        enabled: false,
    }
}
document.getElementById('analytics-bouncerate').innerHTML = '';
var total = new ApexCharts(document.querySelector("#analytics-bouncerate"), total);
total.render();
/* Bounce rate Chart */

/* Sessions Chart */
var totalConfig = {
    chart: {
        type: 'area',
        height: 66,
        sparkline: {
            enabled: true
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        width: 2,
        dashArray: [0, 4],
    },
    fill: {
        type: 'gradient', 
        gradient: {
            shade: 'light',
            shadeIntensity: 0.5,
            gradientToColors: ['#35bdaa'],
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0.05,
            stops: [0, 100]
        },
    },
    series: [{
        name: 'Value',
        data: [65, 38, 56, 22, 65, 96, 53, 45, 62, 80, 35, 48]
    }],
    yaxis: {
        min: 0,
        show: false,
        axisBorder: {
            show: false
        },
    },
    xaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ["#35bdaa"],
    tooltip: {
        enabled: false,
    }
};

document.getElementById('analytics-session').innerHTML = '';
var totalChart = new ApexCharts(document.querySelector("#analytics-session"), totalConfig);
totalChart.render();

/* Sessions Chart */

/* Avg Sessions Chart */
var total = {
    chart: {
        type: 'bar',
        height: 278,
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        dashArray: [0],
    },
    series: [{
        name: 'Value',
        data: [65, 38, 56, 22, 65, 96, 53]
    }],
    yaxis: {
        min: 0,
        show: false,
        axisBorder: {
            show: false
        },
    },
    xaxis: {
        categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        axisBorder: {
            show: false
        },
    },
    plotOptions: {
        bar: {
            borderRadius: 5,
            columnWidth: "30%",
        },
    },
    dataLabels: {
        enabled: false,
      },
    colors: ["var(--primary-color)"],
    tooltip: {
        enabled: false,
    }
}
document.getElementById('analytics-avgsession').innerHTML = '';
var total = new ApexCharts(document.querySelector("#analytics-avgsession"), total);
total.render();
/* Avg Sessions Chart */

/* Impression By Device Chart */
var options = {
    series: [1800, 987, 752, 368],
    labels: ["Email", "Social", "Search", "Direct"],
    chart: {
        height: 235,
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
        lineCap: 'butt',
        colors: "#fff",
        width: 3,
        dashArray: 0,
    },
    plotOptions: {
        pie: {
            expandOnClick: false,
            donut: {
                size: '85%',
                background: 'rgba(var(--light-rgb), 1)',
                labels: {
                    show: true,
                    name: {
                        show: true,
                        fontSize: '20px',
                        color: '#495057',
                        offsetY: 0
                    },
                    value: {
                        show: true,
                        fontSize: '12px',
                        fontFamily: "Poppins",
                        color: undefined,
                        offsetY: 10,
                        formatter: function (val) {
                            return val + "%"
                        }
                    },
                    total: {
                        show: true,
                        showAlways: true,
                        label: 'Impression',
                        fontSize: '14px',
                        fontWeight: 600,
                        fontFamily: "Poppins",
                        color: '#495057',
                    }

                }
            }
        }
    },
    colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)", "rgba(var(--secondary-rgb), 1)", "rgba(var(--info-rgb), 1)",],
};
document.querySelector("#impression").innerHTML = " ";
var chart = new ApexCharts(document.querySelector("#impression"), options);
chart.render();
/* Impression By Device Chart */

/* Audience report Chart */
var options = {
    series: [
        {
            name: 'Views',
            type: 'column',
            data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 45, 35]
        },
        {
            name: 'Followers',
            type: 'column',
            data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43, 27]
        },
    ],
    chart: {
        toolbar: {
            show: false
        },
        type: 'line',
        height: 328,
    },
    grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 3
    },
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: [1, 1.1],
        curve: ['straight', 'smooth'],
    },
    legend: {
        show: true,
        position: 'top',
        markers: {
            shape: "diamond",
            size: 5
        }
    },
    xaxis: {
        axisBorder: {
            color: '#e9e9e9',
        },
    },
    plotOptions: {
        bar: {
            columnWidth: "45%",
            borderRadius: 2
        }
    },
    colors: ["var(--primary-color)", "rgba(var(--success-rgb), 1)"],
};
document.querySelector("#audienceReport").innerHTML = ""
var chart2 = new ApexCharts(document.querySelector("#audienceReport"), options);
chart2.render();
/* Audience report Chart */