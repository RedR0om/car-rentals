import sys
import json
import numpy as np
import pandas as pd
import mysql.connector
from datetime import datetime, timedelta
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

# Get input values from command-line arguments
selectedCar_last_maintenance = float(sys.argv[1])  # last maintenance mileage
selectedCar_current_mileage = float(sys.argv[2])  # current mileage
vehicleId = int(sys.argv[3])  # vehicle ID
inspection_date = sys.argv[4]  # new argument: inspection date (as string)
last_inspection_date = sys.argv[5]  # new argument: last inspection date (as string)

# Recommended mileage gaps based on inspection type (in miles)
INSPECTION_GAPS = {
    "engine_fluids": 5000,
    "battery": 6000,
    "tires": 7500,
    "brakes": 60000,
    "lights_electrical": 6000,
    "air_filters": 30000,
    "belts_hoses": 100000,
    "suspension": 100000,
    "exhaust_system": 20000,
    "alignment_suspension": 20000,
    "wipers_windshield": 12000,
    "timing_belt_chain": 100000,
    "air_conditioning_heater": 24000,
    "cabin_exterior_maintenance": 6000,
    "professional_inspections": 12000
}

MAX_INSPECTION_GAPS = {
    "engine_fluids": 10000,
    "battery": 12000,
    "tires": 15000,
    "brakes": 120000,
    "lights_electrical": 12000,
    "air_filters": 60000,
    "belts_hoses": 200000,
    "suspension": 200000,
    "exhaust_system": 40000,
    "alignment_suspension": 40000,
    "wipers_windshield": 24000,
    "timing_belt_chain": 200000,
    "air_conditioning_heater": 48000,
    "cabin_exterior_maintenance": 12000,
    "professional_inspections": 24000
}

# Recommended date inspection gaps based on inspection type (in months/years)
DATE_INSPECTION_GAPS = {
    "engine_fluids": timedelta(days=180),
    "battery": timedelta(days=180),
    "tires": timedelta(days=180),
    "brakes": timedelta(days=365),
    "lights_electrical": timedelta(days=180),
    "air_filters": timedelta(days=365),
    "belts_hoses": timedelta(days=1095),  # 3 years
    "suspension": timedelta(days=1825),  # 5 years
    "exhaust_system": timedelta(days=365),
    "alignment_suspension": timedelta(days=730),  # 2 years
    "wipers_windshield": timedelta(days=180),
    "timing_belt_chain": timedelta(days=1825),  # 5 years
    "air_conditioning_heater": timedelta(days=730),
    "cabin_exterior_maintenance": timedelta(days=180),
    "professional_inspections": timedelta(days=365)
}

# Database connection
DB_CONFIG = {
    "host": "ballast.proxy.rlwy.net",  # Remove port from here
    "port": 35637,  # Add port separately
    "user": "root",
    "password": "BobDdBAPBobrKyzYicQYaJhDpujZqoKa",
    "database": "railway"
}


def predict_maintenance(last_maintenance, current_mileage, inspection_type, model, date_gap_exceeded):
    
    if date_gap_exceeded:
        return "Yes"  # Automatically return "Yes" if the inspection date gap is exceeded
    
    recommended_gap = INSPECTION_GAPS.get(inspection_type)
    max_gap = MAX_INSPECTION_GAPS.get(inspection_type)
    
    if recommended_gap is None or max_gap is None:
        return "Unknown Inspection Type"
    
    mileage_gap = current_mileage - last_maintenance
    
    # Check if the mileage gap has reached or exceeded the maximum allowable gap
    if mileage_gap >= max_gap:
        return "Yes"  # Automatically return "Yes" if the mileage gap exceeds the max value
    
    # Check if the mileage gap is less than the recommended gap
    if mileage_gap < recommended_gap:
        return "No"
    
    input_data = pd.DataFrame([[last_maintenance, current_mileage]], columns=["last_maintenance_mileage", "current_mileage"])
    prediction = model.predict(input_data)[0]
    return "Yes" if prediction == 1 else "No"


try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    
    # Fetch vehicle data
    vehicle_query = """
    SELECT * FROM tblinspections WHERE id = %s
    """
    cursor.execute(vehicle_query, (vehicleId,))
    vehicle_data = cursor.fetchone()
    
    inspection_results = {}
    
    for inspection_type in INSPECTION_GAPS.keys():
        query = f"""
        SELECT 
            last_mileage AS last_maintenance_mileage, 
            mileage AS current_mileage, 
            ai_is_maintenance AS maintenance
        FROM tblvehicle_maintenance
        WHERE inspection_type = '{inspection_type}'
        """
        cursor.execute(query)
        data = cursor.fetchall()
        
        if not data:
            inspection_results[inspection_type] = {"error": "No data found"}
            continue
        
        df = pd.DataFrame(data)
        X = df[["last_maintenance_mileage", "current_mileage"]]
        y = df["maintenance"]
        class_distribution = y.value_counts().to_dict()
        
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)
        
        model = LogisticRegression()
        model.fit(X_train, y_train)
        y_pred = model.predict(X_test)
        
        accuracy = accuracy_score(y_test, y_pred)
        precision = precision_score(y_test, y_pred, zero_division=1)
        recall = recall_score(y_test, y_pred, zero_division=1)
        f1 = f1_score(y_test, y_pred, zero_division=1)
        
        if isinstance(inspection_date, str):
            inspection_date = datetime.strptime(inspection_date, "%Y-%m-%d")

        if isinstance(last_inspection_date, str):
            last_inspection_date = datetime.strptime(last_inspection_date, "%Y-%m-%d")
        date_gap_exceeded = (inspection_date - last_inspection_date) >= DATE_INSPECTION_GAPS.get(inspection_type, timedelta(days=9999))

        maintenance_prediction = predict_maintenance(selectedCar_last_maintenance, selectedCar_current_mileage, inspection_type, model, date_gap_exceeded)
        
        inspection_results[inspection_type] = {
            "maintenance_prediction": maintenance_prediction
        }
        
        # Merge vehicle_data fields relevant to the inspection type
        if vehicle_data:
            for key, value in vehicle_data.items():
                if key.startswith(inspection_type):
                    if key.endswith("_remarks"):  # Store remarks properly
                        inspection_results[inspection_type]["remarks"] = value
                    else:  # Store status properly
                        inspection_results[inspection_type]["status"] = value
    
    cursor.close()
    conn.close()
    
    result = {
        "selectedCar_last_maintenance": selectedCar_last_maintenance,
        "selectedCar_current_mileage": selectedCar_current_mileage,
        "inspection_date": inspection_date.strftime("%Y-%m-%d") if isinstance(inspection_date, datetime) else inspection_date,
        "last_inspection_date": last_inspection_date.strftime("%Y-%m-%d") if isinstance(last_inspection_date, datetime) else last_inspection_date,
        "inspection_results": inspection_results,
        "vehicle_data": {"id": vehicle_data["id"], "vehicle": vehicle_data["vehicle"]} if vehicle_data else {}
    }

    
    print(json.dumps(result, indent=4))

except mysql.connector.Error as err:
    print(json.dumps({"error": "Database Error", "message": str(err)}))
except Exception as e:
    print(json.dumps({"error": "General Error", "message": str(e)}))
