// =====================================================
// VerdEX Farm Status
// Live sensor updates + Environmental Overview chart
// =====================================================


// =====================================================
// UPDATE LIVE SENSOR DATA
// =====================================================

async function updateLiveSensorData() {

    try {

        // Get latest data from VerdEX API
        const response = await fetch(
            "../api/latest-sensor-data.php",
            {
                cache: "no-store"
            }
        );

        if (!response.ok) {
            throw new Error(
                "HTTP error: " + response.status
            );
        }

        const data = await response.json();


        // Stop if API did not return sensor data
        if (!data.success) {

            console.log(
                "Sensor API:",
                data.message
            );

            return;
        }


        // =================================================
        // CURRENT SENSOR VALUES
        // =================================================

        const temperature =
            Number(data.temperature);

        const humidity =
            Number(data.humidity);

        const soilMoisture =
            Number(data.soil_moisture);


        // =================================================
        // GET PAGE ELEMENTS
        // =================================================

        const temperatureElement =
            document.getElementById(
                "liveTemperature"
            );

        const humidityElement =
            document.getElementById(
                "liveHumidity"
            );

        const soilElement =
            document.getElementById(
                "liveSoilMoisture"
            );


        const temperatureStatus =
            document.getElementById(
                "liveTemperatureStatus"
            );

        const humidityStatus =
            document.getElementById(
                "liveHumidityStatus"
            );

        const soilStatus =
            document.getElementById(
                "liveSoilMoistureStatus"
            );


        const healthElement =
            document.getElementById(
                "liveOverallHealth"
            );

        const healthMessage =
            document.getElementById(
                "liveOverallHealthMessage"
            );


        // =================================================
        // UPDATE CURRENT VALUES
        // =================================================

        if (temperatureElement) {

            temperatureElement.textContent =
                temperature.toFixed(1) + "°C";
        }


        if (humidityElement) {

            humidityElement.textContent =
                humidity.toFixed(1) + "%";
        }


        if (soilElement) {

            soilElement.textContent =
                soilMoisture.toFixed(1) + "%";
        }


        // =================================================
        // TEMPERATURE STATUS
        // =================================================

        const temperatureGood =
            temperature >= 18 &&
            temperature <= 32;


        if (temperatureStatus) {

            temperatureStatus.textContent =
                temperatureGood
                    ? "Normal range"
                    : "Warning level";
        }


        // =================================================
        // HUMIDITY STATUS
        // =================================================

        const humidityGood =
            humidity >= 50 &&
            humidity <= 80;


        if (humidityStatus) {

            humidityStatus.textContent =
                humidityGood
                    ? "Good level"
                    : "Warning level";
        }


        // =================================================
        // SOIL MOISTURE STATUS
        // =================================================

        const soilGood =
            soilMoisture >= 40 &&
            soilMoisture <= 80;


        if (soilStatus) {

            soilStatus.textContent =
                soilGood
                    ? "Good moisture level"
                    : "Warning level";
        }


        // =================================================
        // OVERALL FARM HEALTH
        // =================================================

        const farmHealthy =
            temperatureGood &&
            humidityGood &&
            soilGood;


        if (healthElement) {

            healthElement.textContent =
                farmHealthy
                    ? "Healthy"
                    : "Warning";
        }


        if (healthMessage) {

            healthMessage.innerHTML =
                '<span class="health-dot"></span>' +
                (
                    farmHealthy
                        ? "All systems are operating normally"
                        : "One or more readings need attention"
                );
        }


        // =================================================
        // ENVIRONMENTAL OVERVIEW CHART
        // =================================================

        updateEnvironmentalChart(
            data.readings
        );


    } catch (error) {

        console.error(
            "Unable to retrieve live sensor data:",
            error
        );
    }
}


// =====================================================
// UPDATE ENVIRONMENTAL CHART
// =====================================================

function updateEnvironmentalChart(readings) {

    // Make sure chart exists
    if (!window.realtimeChart) {

        console.log(
            "Environmental chart is not ready."
        );

        return;
    }


    // Make sure readings exist
    if (!Array.isArray(readings)) {

        console.log(
            "No chart readings received."
        );

        return;
    }


    // Nothing to display
    if (readings.length === 0) {

        console.log(
            "Chart readings are empty."
        );

        return;
    }


    const labels = [];

    const temperatures = [];

    const humidities = [];

    const soilMoistures = [];


    // =================================================
    // PREPARE CHART DATA
    // =================================================

    readings.forEach(
        function (reading) {

            // MySQL:
            // 2026-09-25 00:24:35
            //
            // JavaScript-friendly:
            // 2026-09-25T00:24:35

            const dateString =
                String(
                    reading.recorded_at
                ).replace(
                    " ",
                    "T"
                );


            const readingDate =
                new Date(
                    dateString
                );


            // Time label
            if (
                !isNaN(
                    readingDate.getTime()
                )
            ) {

                labels.push(
                    readingDate.toLocaleTimeString(
                        [],
                        {
                            hour:
                                "2-digit",

                            minute:
                                "2-digit",

                            second:
                                "2-digit"
                        }
                    )
                );

            } else {

                labels.push(
                    reading.recorded_at
                );
            }


            // Sensor values
            temperatures.push(
                Number(
                    reading.temperature
                )
            );

            humidities.push(
                Number(
                    reading.humidity
                )
            );

            soilMoistures.push(
                Number(
                    reading.soil_moisture
                )
            );
        }
    );


    // =================================================
    // SEND DATA TO CHART.JS
    // =================================================

    window.realtimeChart.data.labels =
        labels;


    window.realtimeChart
        .data
        .datasets[0]
        .data =
        temperatures;


    window.realtimeChart
        .data
        .datasets[1]
        .data =
        humidities;


    window.realtimeChart
        .data
        .datasets[2]
        .data =
        soilMoistures;


    // Redraw chart
    window.realtimeChart.update();
}


// =====================================================
// START LIVE UPDATES
// =====================================================

// Get data immediately
updateLiveSensorData();


// Then check every 5 seconds
setInterval(
    updateLiveSensorData,
    5000
);