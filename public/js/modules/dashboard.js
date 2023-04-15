$(window).on("load", function(){

    $('.detail-late').on('click', function(){
        let today = new Date();
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        $('#modal-attendance .modal-content .modal-header .modal-title').html('Late Attendance at ' + today.getDate() + ' ' + month[today.getMonth()] + ', ' + today.getFullYear())

        let content = '';
        if(late.length){
            late.forEach((el, i)=> {
                content += `<tr>
                                <td>${ i + 1}</td>
                                <td>${el.fullname}</td>
                                <th>${el.clock_in ? el.clock_in.substring(0, 5) : '--:--'}</th>
                                <th>${el.clock_out ? el.clock_out.substring(0, 5) : '--:--'}</th>
                            </tr>`
            });
        } else {
            content += `<tr>
                            <td colspan="4" class="text-center">No Data Found</td>
                        </tr>`
        }

        let $table = `<table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fullname</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                            </tr>
                        </thead>
                        <tbody>
                        ${content}
                        </tbody>
                    </table>` 
        $('#modal-attendance .modal-content .modal-body').html($table);
        $('#modal-attendance').modal('show');
    })

    $('.detail-not-present').on('click', function(){
        let today = new Date();
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        $('#modal-attendance .modal-content .modal-header .modal-title').html('Not Present List at ' + today.getDate() + ' ' + month[today.getMonth()] + ', ' + today.getFullYear())

        let content = '';
        if(notPresent.length){
            notPresent.forEach((el, i)=> {
                content += `<tr>
                                <td>${ i + 1}</td>
                                <td>${el.fullname}</td>
                                <td>${el.job_title_name}</td>
                                <td>${el.phone}</td>
                                <td>${el.email}</td>
                            </tr>`
            });
        } else {
            content += `<tr>
                            <td colspan="5" class="text-center">No Data Found</td>
                        </tr>`
        }

        let $table = `<table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fullname</th>
                                <th>Job Title</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        ${content}
                        </tbody>
                    </table>` 
        $('#modal-attendance .modal-content .modal-body').html($table);
        $('#modal-attendance').modal('show');
    })

    let total = attendances;

    let dataSetColor1 = '#00E090';
    let dataSetColor2 = '#FF4961';

    var ctx = $("#attendance-chart");

    // Chart Option
    var chartOptions = {
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'bottom'
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        legend: {
            position: 'top',
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
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
            }]
        },
    };

    // Chart Data
    var chartData = {
        labels: ["January", "February", "March", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Present",
            data: [total['jan'], total['feb'], total['mar'], total['apr'], total['mei'], total['jun'], total['jul'], total['aug'], total['sep'], total['oct'], total['nov'], total['dec']],
            backgroundColor: dataSetColor1,
            hoverBackgroundColor: "rgba(22,211,154,.9)",
            borderColor: "transparent"
        }, {
            label: "Not Present",
            data: [(totalEmployee - total['jan']), (totalEmployee - total['feb']), (totalEmployee - total['mar']), (totalEmployee - total['apr']), (totalEmployee - total['mei']), (totalEmployee - total['jun']), (totalEmployee - total['jul']), (totalEmployee - total['aug']), (totalEmployee - total['sep']), (totalEmployee - total['oct']), (totalEmployee - total['nov']), (totalEmployee - total['dec'])],
            backgroundColor: dataSetColor2,
            hoverBackgroundColor: "rgba(249,142,118,.9)",
            borderColor: "transparent"
        }]
    };

    var config = {
        type: 'bar',
        options : chartOptions,
        data : chartData
    };

    var barChart = new Chart(ctx, config);
});