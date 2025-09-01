/* Project Analysis Chart */
var options = {
    series: [{
        name: 'Total Projects',
        type: 'column',
        data: [18, 25, 25, 33, 25, 28, 38, 20, 29, 37, 35, 44]
    }, {
        name: 'All Tasks',
        type: 'column',
        data: [11, 22, 45, 40, 41, 49, 65, 18, 38, 33, 15, 25]
    }, {
        name: 'Current Projects',
        type: 'line',
        data: [20, 29, 37, 35, 44, 43, 50, 33, 35, 31, 40, 41],
    },
    ],
    chart: {
        toolbar: {
            show: false
        },
        height: 356,
        type: 'line',
        stacked: false,
        fontFamily: 'Poppins, Arial, sans-serif',
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
        borderColor: '#f5f4f4',
        strokeDashArray: 3
    },
    dataLabels: {
        enabled: false
    },
    title: {
        text: undefined,
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: [
        {
            show: true,
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: false,
                color: '#4eb6d0'
            },
            labels: {
                style: {
                    colors: '#4eb6d0',
                }
            },
        },
    ],
    tooltip: {
        enabled: true,
    },
    legend: {
        show: true,
        position: 'top',
        offsetX: 40,
        fontSize: '13px',
        fontWeight: 'normal',
        labels: {
            colors: '#acb1b1',
        },
        markers: {
            width: 10,
            height: 10,
        },
    },
    stroke: {
        width: [0, 0, 3],
        curve: 'straight',
        dashArray: [0, 0, 0],
    },
    plotOptions: {
        bar: {
            columnWidth: "40%",
            borderRadius: 3
        }
    },
    colors: [ "rgba(var(--secondary-rgb), 1)", "var(--primary-color)", "rgba(var(--success-rgb), 1)"]
};
document.querySelector("#projectAnalysis").innerHTML = " ";
var chart1 = new ApexCharts(document.querySelector("#projectAnalysis"), options);
chart1.render();
/* Project Analysis Chart */

/* Active projects */
var options = {
    chart: {
        height: 230,
        type: "radialBar",
        sparkline: {
          enabled: true
        }
    },
    series: [48],
    colors: ["var(--primary-color)"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "70%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 7,
                    color: "#4b9bfa",
                    fontSize: "23px",
                    show: true,
                    fontWeight: 600,
                    fontFamily: "Poppins",

                }
            }
        }
    }, grid: {
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["Active Projects"]
};
var chart5 = new ApexCharts(document.querySelector("#projects-active"), options);
chart5.render();
/* Active projects */

/* new projects */
var options = {
    chart: {
        height: 230,
        type: "radialBar",
        sparkline: {
          enabled: true
        }
    },
    series: [56],
    colors: ["rgba(var(--success-rgb), 1)"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "70%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 7,
                    color: "#4b9bfa",
                    fontSize: "23px",
                    show: true,
                    fontWeight: 600,
                    fontFamily: "Poppins",
                }
            }
        }
    }, grid: {
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["New Projects"]
};
var chart5 = new ApexCharts(document.querySelector("#projects-new"), options);
chart5.render();
/* new projects */
