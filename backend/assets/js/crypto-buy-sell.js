(function() {
    "use strict"

    var options = {
        series: [{
            type: 'bar',
            name: "Deposits",
            data: [12, 30, 45, 55, 65, 80, 50, 70, 85, 95, 40, 75],
        }, {
            type: 'bar',
            name: "Withdrawals",
            data: [60, 50, 70, 20, 90, 25, 55, 35, 40, 65, 55, 80]
        }],
        chart: {
            height: 316,
            type: 'bar',
            zoom: {
                enabled: false
            },
        },
        plotOptions: {
            bar: {
                columnWidth: "40%",
                borderRadius: 3
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            position: "top",
            horizontalAlign: "center",
            offsetX: -15,
            fontWeight: "bold",
            markers: {
                size: 4,
                strokeWidth: 0,
                shape: "circle",
            }
        },
        stroke: {
            curve: 'smooth',
        },
        markers: {
            size: 5,
            hover: {
                size: 7
            },
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 3
        },
        colors: ["var(--primary-color)", "rgba(53, 189, 170, 0.8)"],
        yaxis: {
            title: {
                text: 'Transaction Amount (in TL)',
                style: {
                    color: '#adb5be',
                    fontSize: '14px',
                    fontFamily: 'poppins, sans-serif',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            },
        },
        xaxis: {
            type: 'month',
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
    document.getElementById('buy_sell-statistics').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#buy_sell-statistics"), options);
    chart.render();

})();
