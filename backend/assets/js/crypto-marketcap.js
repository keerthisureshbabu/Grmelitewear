(function () {
  "use strict";

  /* Bitcoin Chart */
  var spark1 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          16, 11, 14, 19, 31, 23, 15
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(244, 138, 167)"],
  };
  document.getElementById("btc-chart").innerHTML = "";
  var spark1 = new ApexCharts(document.querySelector("#btc-chart"), spark1);
  spark1.render();

  /* Etherium Chart */
  var spark2 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          20, 15, 20, 25, 35, 30, 20
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("eth-chart").innerHTML = "";
  var spark2 = new ApexCharts(document.querySelector("#eth-chart"), spark2);
  spark2.render();

  /* Golem Chart */
  var spark3 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          12, 7, 12, 17, 27, 22, 12
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("glm-chart").innerHTML = "";
  var spark3 = new ApexCharts(document.querySelector("#glm-chart"), spark3);
  spark3.render();

  /* Dash Chart */
  var spark4 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          25, 15, 10, 30, 20, 15, 15
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("dash-chart").innerHTML = "";
  var spark4 = new ApexCharts(document.querySelector("#dash-chart"), spark4);
  spark4.render();

  /* Litecoin Chart */
  var spark5 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          10, 15, 20, 15, 25, 15, 30
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(244, 138, 167)"],
  };
  document.getElementById("lite-chart").innerHTML = "";
  var spark5 = new ApexCharts(document.querySelector("#lite-chart"), spark5);
  spark5.render();
  
  /* Ripple Chart */
  var spark6 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          18, 12, 20, 22, 28, 26, 17
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("ripple-chart").innerHTML = "";
  var spark6 = new ApexCharts(document.querySelector("#ripple-chart"), spark6);
  spark6.render();

  /* Eos Chart */
  var spark7 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          22, 12, 18, 26, 20, 28, 17
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("eos-chart").innerHTML = "";
  var spark7 = new ApexCharts(document.querySelector("#eos-chart"), spark7);
  spark7.render();

  /* IOTA Chart */
  var spark9 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          14, 9, 16, 21, 29, 24, 13
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(244, 138, 167)"],
  };
  document.getElementById("iota-chart").innerHTML = "";
  var spark9 = new ApexCharts(document.querySelector("#iota-chart"), spark9);
  spark9.render();

  /* Monero Chart */
  var spark10 = {
    chart: {
      type: "line",
      height: 30,
      width: 100,
      sparkline: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        enabledOnSeries: undefined,
        top: 0,
        left: 0,
        blur: 3,
        color: "#000",
        opacity: 0.1,
      },
    },
    stroke: {
      show: true,
      curve: "smooth",
      lineCap: "butt",
      colors: undefined,
      width: 1.5,
      dashArray: 0,
    },
    fill: {
      gradient: {
        enabled: false,
      },
    },
    series: [
      {
        name: "Value",
        data: [
          13, 21, 9, 24, 16, 29, 14
        ],
      },
    ],
    yaxis: {
      min: 0,
      show: false,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
    },
    tooltip: {
      enabled: false,
    },
    colors: ["rgb(53, 189, 170)"],
  };
  document.getElementById("monero-chart").innerHTML = "";
  var spark10 = new ApexCharts(document.querySelector("#monero-chart"), spark10);
  spark10.render();

  /* Start:: Main cards charts */
var spark1 = {
  chart: {
    type: 'line',
    height: 40,
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
  stroke: {
    show: true,
    curve: 'smooth',
    lineCap: 'butt',
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    gradient: {
      enabled: false
    }
  },
  series: [{
    name: 'Value',
    data: [8, 52, 60, 42, 70, 26, 80, 30, 36, 38, 64, 53, 43, 47, 34, 28, 90, 55, 63, 26, 60, 45, 20, 50]
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
  colors: ['var(--primary-color)'],

}
document.getElementById('bitcoin-chart').innerHTML = '';
var spark1 = new ApexCharts(document.querySelector("#bitcoin-chart"), spark1);
spark1.render();

var spark2 = {
  chart: {
    type: 'line',
    height: 40,
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
  stroke: {
    show: true,
    curve: 'smooth',
    lineCap: 'butt',
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    gradient: {
      enabled: false
    }
  },
  series: [{
    name: 'Value',
    data: [8, 52, 60, 42, 70, 26, 80, 30, 36, 38, 64, 53, 43, 47, 34, 28, 90, 55, 63, 26, 60, 45, 20, 50]
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
  colors: ['rgb(244, 138, 167)'],

}
document.getElementById('etherium-chart').innerHTML = '';
var spark2 = new ApexCharts(document.querySelector("#etherium-chart"), spark2);
spark2.render();

var spark3 = {
  chart: {
    type: 'line',
    height: 40,
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
  stroke: {
    show: true,
    curve: 'smooth',
    lineCap: 'butt',
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    gradient: {
      enabled: false
    }
  },
  series: [{
    name: 'Value',
    data: [10, 55, 64, 48, 66, 34, 75, 41, 47, 49, 72, 61, 45, 51, 45, 37, 103, 63, 71, 37, 64, 53, 29, 56]
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
  colors: ['rgb(53, 189, 170)'],

}
document.getElementById('dashcoin-chart').innerHTML = '';
var spark3 = new ApexCharts(document.querySelector("#dashcoin-chart"), spark3);
spark3.render();
var spark13 = {
  chart: {
    type: 'line',
    height: 40,
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
  stroke: {
    show: true,
    curve: 'smooth',
    lineCap: 'butt',
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    gradient: {
      enabled: false
    }
  },
  series: [{
    name: 'Value',
    data: [15, 50, 62, 34, 75, 31, 78, 40, 45, 37, 66, 55, 48, 49, 39, 30, 95, 58, 70, 29, 62, 46, 25, 52]
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
  colors: ['rgb(46, 142, 247)'],

}
document.getElementById('litecoin-chart').innerHTML = '';
var spark13 = new ApexCharts(document.querySelector("#litecoin-chart"), spark13);
spark13.render();
/* End:: Main cards charts */

})();
