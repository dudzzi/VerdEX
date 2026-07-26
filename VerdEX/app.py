import serial
import json
import time

# Change COM4 to your Arduino port
arduino = serial.Serial("COM3", 9600, timeout=1)

# Wait for the serial connection to stabilize
time.sleep(2)

print("Reading data from Arduino...")

while True:
    if arduino.in_waiting:
        line = arduino.readline().decode("utf-8").strip()

        try:
            temperature, humidity = line.split(",")

            data = {
                "temperature": temperature,
                "humidity": humidity
            }

            with open(r"C:/Users/User/OneDrive/Desktop/VerdEX\sensor.json","w") as file:
                json.dump(data, file, indent=4)

            print(data)

        except ValueError:
            print("Invalid data:", line)