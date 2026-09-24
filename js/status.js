/* =================================
   VERDEX - FARM STATUS
================================= */


/* =================================
   CURRENT ENVIRONMENTAL DATA
================================= */

const currentData = {
    labels: [
        "5 min ago",
        "4 min ago",
        "3 min ago",
        "2 min ago",
        "1 min ago",
        "Now"
    ],

    temperature: [
        27.8,
        28.2,
        28.6,
        29.1,
        28.9,
        28.5
    ],

    humidity: [
        75,
        74,
        73,
        71,
        70,
        72
    ],

    soilMoisture: [
        68,
        67,
        66,
        64,
        63,
        64
    ]
};


/* =================================
   HISTORICAL DATA
================================= */

const historyData = {

    "2026-08": {

        labels: [
            "Aug 1",
            "Aug 5",
            "Aug 10",
            "Aug 15",
            "Aug 20",
            "Aug 25",
            "Aug 31"
        ],

        temperature: [
            27.8,
            28.4,
            29.1,
            30.2,
            28.7,
            27.9,
            28.3
        ],

        humidity: [
            74,
            71,
            69,
            67,
            73,
            76,
            72
        ],

        soilMoisture: [
            62,
            65,
            61,
            55,
            68,
            70,
            66
        ]
    }

};


/* =================================
   CHART OPTIONS
================================= */

const commonOptions = {

    responsive: true,

    maintainAspectRatio: false,

    interaction: {
        mode: "index",
        intersect: false
    },

    plugins: {

        legend: {
            display: true,

            labels: {
                usePointStyle: true,
                padding: 20,

                font: {
                    size: 12
                }
            }
        },

        tooltip: {
            backgroundColor: "#26352b",
            padding: 12,

            titleFont: {
                size: 12
            },

            bodyFont: {
                size: 12
            }
        }
    },

    scales: {

        x: {
            grid: {
                display: false
            },

            ticks: {
                color: "#89938b",
                font: {
                    size: 11
                }
            }
        },

        y: {
            beginAtZero: false,

            grid: {
                color: "#edf0ed"
            },

            ticks: {
                color: "#89938b",
                font: {
                    size: 11
                }
            }
        }
    }
};


/* =================================
   CREATE CURRENT STATUS CHART
================================= */

function createRealtimeChart() {

    const canvas = document.createElement("canvas");

    const container = document.getElementById("realtimeChart");

    if (!container) {
        return;
    }

    container.innerHTML = "";

    container.appendChild(canvas);


    new Chart(canvas, {

        type: "line",

        data: {

            labels: currentData.labels,

            datasets: [

                {
                    label: "Temperature (°C)",

                    data: currentData.temperature,

                    borderColor: "#4c9658",

                    backgroundColor: "rgba(76, 150, 88, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "temperature"
                },

                {
                    label: "Humidity (%)",

                    data: currentData.humidity,

                    borderColor: "#5b9bd5",

                    backgroundColor: "rgba(91, 155, 213, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "percentage"
                },

                {
                    label: "Soil Moisture (%)",

                    data: currentData.soilMoisture,

                    borderColor: "#c5964b",

                    backgroundColor: "rgba(197, 150, 75, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "percentage"
                }

            ]

        },

        options: {

            ...commonOptions,

            scales: {

                x: {
                    grid: {
                        display: false
                    },

                    ticks: {
                        color: "#89938b",
                        font: {
                            size: 11
                        }
                    }
                },

                temperature: {

                    type: "linear",

                    position: "left",

                    min: 20,

                    max: 35,

                    title: {
                        display: true,
                        text: "Temperature °C"
                    },

                    grid: {
                        color: "#edf0ed"
                    },

                    ticks: {
                        color: "#89938b"
                    }
                },

                percentage: {

                    type: "linear",

                    position: "right",

                    min: 0,

                    max: 100,

                    title: {
                        display: true,
                        text: "Percentage"
                    },

                    grid: {
                        drawOnChartArea: false
                    },

                    ticks: {
                        color: "#89938b"
                    }
                }

            }

        }

    });

}


/* =================================
   CREATE HISTORY CHART
================================= */

function createHistoryChart(month) {

    const container = document.getElementById("historyChart");

    if (!container) {
        return;
    }

    const data = historyData[month];

    container.innerHTML = "";


    /*
    | No data available
    */

    if (!data) {

        container.innerHTML = `
            <div style="
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #89938b;
                font-size: 13px;
            ">
                No historical data available for this month.
            </div>
        `;

        return;
    }


    const canvas = document.createElement("canvas");

    container.appendChild(canvas);


    new Chart(canvas, {

        type: "line",

        data: {

            labels: data.labels,

            datasets: [

                {
                    label: "Temperature (°C)",

                    data: data.temperature,

                    borderColor: "#4c9658",

                    backgroundColor: "rgba(76, 150, 88, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "temperature"
                },

                {
                    label: "Humidity (%)",

                    data: data.humidity,

                    borderColor: "#5b9bd5",

                    backgroundColor: "rgba(91, 155, 213, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "percentage"
                },

                {
                    label: "Soil Moisture (%)",

                    data: data.soilMoisture,

                    borderColor: "#c5964b",

                    backgroundColor: "rgba(197, 150, 75, 0.08)",

                    borderWidth: 2,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    tension: 0.35,

                    yAxisID: "percentage"
                }

            ]

        },

        options: {

            ...commonOptions,

            scales: {

                x: {
                    grid: {
                        display: false
                    },

                    ticks: {
                        color: "#89938b",
                        font: {
                            size: 11
                        }
                    }
                },

                temperature: {

                    type: "linear",

                    position: "left",

                    min: 20,

                    max: 35,

                    title: {
                        display: true,
                        text: "Temperature °C"
                    },

                    grid: {
                        color: "#edf0ed"
                    },

                    ticks: {
                        color: "#89938b"
                    }
                },

                percentage: {

                    type: "linear",

                    position: "right",

                    min: 0,

                    max: 100,

                    title: {
                        display: true,
                        text: "Percentage"
                    },

                    grid: {
                        drawOnChartArea: false
                    },

                    ticks: {
                        color: "#89938b"
                    }
                }

            }

        }

    });

}


/* =================================
   MONTH SELECTOR
================================= */

const monthSelector = document.getElementById("historyMonth");

if (monthSelector) {

    monthSelector.addEventListener("change", function () {

        createHistoryChart(this.value);

    });

}


/* =================================
   LOAD CHARTS
================================= */

document.addEventListener("DOMContentLoaded", function () {

    createRealtimeChart();

    createHistoryChart("2026-08");

});