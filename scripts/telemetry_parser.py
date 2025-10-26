#!/usr/bin/env python3
import sys
import json
import irsdk
import debugpy

# Enable debugging only when running in Docker
debugpy.listen(("0.0.0.0", 5678))
debugpy.wait_for_client()  # Pause execution until debugger attaches

def parse_telemetry(file_path):
    try:
        # Initialize irsdk with the .ibt file
        ir = irsdk.IBT()
        ir.startup(test_file=file_path)

        if not ir.is_connected:
            return json.dumps({
                "error": "Failed to process telemetry file"
            })

        # Get session info and data
        laps_completed = ir['LapCompleted']
        # Add the specific telemetry data points you want to extract
        telemetry_data = {
            "laps_completed": laps_completed,
            # Add more data points as needed
        }

        ir.close()

        return json.dumps(telemetry_data)

    except Exception as e:
        return json.dumps({
            "error": str(e)
        })

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(json.dumps({"error": "File path argument required"}))
        sys.exit(1)
    
    print(parse_telemetry(sys.argv[1]))