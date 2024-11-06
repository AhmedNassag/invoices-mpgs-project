(function($) {
    "use strict";

    var ctx = document.getElementById('canvas');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Auguest', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Income',
                data: dashboard_income,
                backgroundColor: 'rgb(28, 200, 138)',
                borderColor: 'rgba(17,17,17,0.8)',
                borderWidth: 1
            }, {
                label: 'Expense',
                data: dashboard_expense,
                backgroundColor: 'rgb(231, 74, 59)',
                borderColor: 'rgba(17,17,17,0.8)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    // Pie Chart Example
    var ctx = document.getElementById("incomeExpenseChart");
    var incomeExpenseChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Income", "Expense"],
            datasets: [{
                data: [totalIncomeAmount, totalExpenseAmount],
                backgroundColor: ['#1cc88a', '#e74a3b'],
                hoverBackgroundColor: ['#0abb7b','#d62e1e'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 70,
        },
    });
})(jQuery);