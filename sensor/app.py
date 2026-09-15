import serial
import json
import time
import os

# ----------------------------
# Arduino Settings
# ----------------------------
COM_PORT = "COM4"      # Change if your Arduino uses another COM port
BAUD_RATE = 9600

# ----------------------------
# JSON File Location
# ----------------------------
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
JSON_FILE = os.path.join(BASE_DIR, "sensor.json")

print("Writing sensor data to:")
print(JSON_FILE)

# ----------------------------
# Connect to Arduino
# ----------------------------
try:
    arduino = serial.Serial(COM_PORT, BAUD_RATE, timeout=1)
    time.sleep(2)
    print(f"Connected to {COM_PORT}")
except Exception as e:
    print("Failed to connect:", e)
    exit()

# ----------------------------
# Read Arduino Data
# ----------------------------
while True:
    try:
        line = arduino.readline().decode("utf-8").strip()

        if not line:
            continue

        print("Received:", line)

        values = line.split(",")

        # Expected format:
        # temperature,humidity,water_moisture
        if len(values) == 3:

            sensor_data = {
                "temperature": values[0],
                "humidity": values[1],
                "water_moisture": values[2]
            }

            with open(JSON_FILE, "w") as file:
                json.dump(sensor_data, file, indent=4)

            print("sensor.json updated!")

        else:
            print("Invalid data:", line)

    except Exception as e:
        print("Error:", e)

    time.sleep(1)