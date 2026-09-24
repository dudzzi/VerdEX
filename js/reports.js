/* =================================
   VERDEX - REPORTS JAVASCRIPT
================================= */


/* =================================
   CROP PRODUCTION CHART
================================= */

const productionCanvas =
    document.getElementById("productionChart");

if (productionCanvas) {

    new Chart(productionCanvas, {

        type: "line",

        data: {

            labels: [
                "Aug 1",
                "Aug 5",
                "Aug 10",
                "Aug 15",
                "Aug 20",
                "Aug 25",
                "Aug 30"
            ],

            datasets: [{
                label: "Production (kg)",

                data: [
                    28,
                    34,
                    51,
                    45,
                    63,
                    72,
                    82
                ],

                borderWidth: 2,

                tension: 0.4,

                fill: true
            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    grid: {
                        color: "#edf0ed"
                    },

                    ticks: {
                        font: {
                            size: 10
                        }
                    }

                },

                x: {

                    grid: {
                        display: false
                    },

                    ticks: {
                        font: {
                            size: 10
                        }
                    }

                }

            }

        }

    });

}


/* =================================
   SALES CHART
================================= */

const salesCanvas =
    document.getElementById("salesChart");

if (salesCanvas) {

    new Chart(salesCanvas, {

        type: "bar",

        data: {

            labels: [
                "Week 1",
                "Week 2",
                "Week 3",
                "Week 4"
            ],

            datasets: [{

                label: "Sales",

                data: [
                    8200,
                    10500,
                    11250,
                    12900
                ],

                borderWidth: 0,

                borderRadius: 7

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    grid: {
                        color: "#edf0ed"
                    },

                    ticks: {

                        font: {
                            size: 10
                        },

                        callback: function(value) {

                            return "₱" + value.toLocaleString();

                        }

                    }

                },

                x: {

                    grid: {
                        display: false
                    },

                    ticks: {

                        font: {
                            size: 10
                        }

                    }

                }

            }

        }

    });

}


/* =================================
   REPORT PERIOD
================================= */

const reportPeriod =
    document.getElementById("reportPeriod");


if (reportPeriod) {

    reportPeriod.addEventListener("change", function () {

        const selectedPeriod =
            reportPeriod.value;

        /*
        Temporary behavior.

        Later this will load actual
        report data from PHP/MySQL.
        */

        console.log(
            "Selected report period:",
            selectedPeriod
        );

    });

}


/* =================================
   DOWNLOAD REPORT
================================= */

const downloadReport =
    document.getElementById("downloadReport");


if (downloadReport) {

    downloadReport.addEventListener("click", function () {

        /*
        Temporary behavior.

        Later this button can generate
        a PDF or downloadable report.
        */

        alert(
            "Report download will be available when the reporting system is connected to the database."
        );

    });

}