$(window).on("load", function () {
  let total = jobApplications;

  let dataSetColor = "#00E090";

  var ctx = $("#jobApplication-chart");

  // Chart Option
  var chartOptions = {
    elements: {
      rectangle: {
        borderWidth: 2,
        borderColor: "rgb(0, 255, 0)",
        borderSkipped: "bottom",
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    responsiveAnimationDuration: 500,
    legend: {
      position: "top",
    },
    scales: {
      xAxes: [
        {
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
          },
        },
      ],
      yAxes: [
        {
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
          },
          ticks: {
            stepSize: 1,
            beginAtZero: true,
          },
        },
      ],
    },
  };

  // Chart Data
  var chartData = {
    labels: [
      "January",
      "February",
      "March",
      "April",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        label: "Lamaran Kerja",
        data: [
          total["jan"],
          total["feb"],
          total["mar"],
          total["apr"],
          total["mei"],
          total["jun"],
          total["jul"],
          total["aug"],
          total["sep"],
          total["oct"],
          total["nov"],
          total["dec"],
        ],
        backgroundColor: dataSetColor,
        hoverBackgroundColor: "rgba(22,211,154,.9)",
        borderColor: "transparent",
      },
    ],
  };

  var config = {
    type: "bar",
    options: chartOptions,
    data: chartData,
  };

  var barChart = new Chart(ctx, config);
});
