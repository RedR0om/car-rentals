import sys
import json
import numpy as np
import pandas as pd
import mysql.connector
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

# Get input values from user
selectedCar_last_maintenance = float(input("Enter last maintenance mileage: "))
selectedCar_current_mileage = float(input("Enter current mileage: "))
vehicleId = int(input("Enter vehicle ID: "))

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

# Database connection
DB_CONFIG = {
    "host": "ballast.proxy.rlwy.net:35637",
    "user": "root",
    "password": "BobDdBAPBobrKyzYicQYaJhDpujZqoKa",
    "database": "railway"
}

def predict_maintenance(last_maintenance, current_mileage, inspection_type, model):
    recommended_gap = INSPECTION_GAPS.get(inspection_type)
    max_gap = MAX_INSPECTION_GAPS.get(inspection_type)
    
    if recommended_gap is None or max_gap is None:
        return "Unknown Inspection Type"
    
    mileage_gap = current_mileage - last_maintenance
    
    if mileage_gap >= max_gap:
        return "Yes"
    
    if mileage_gap < recommended_gap:
        return "No"
    
    input_data = pd.DataFrame([[last_maintenance, current_mileage]], columns=["last_maintenance_mileage", "current_mileage"])
    prediction = model.predict(input_data)[0]
    return "Yes" if prediction == 1 else "No"

try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    
    vehicle_query = "SELECT * FROM tblinspections WHERE id = %s"
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
        
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)
        
        model = LogisticRegression()
        model.fit(X_train, y_train)
        
        y_pred = model.predict(X_test)
        accuracy = accuracy_score(y_test, y_pred)
        precision = precision_score(y_test, y_pred, zero_division=1)
        recall = recall_score(y_test, y_pred, zero_division=1)
        f1 = f1_score(y_test, y_pred, zero_division=1)
        
        maintenance_prediction = predict_maintenance(selectedCar_last_maintenance, selectedCar_current_mileage, inspection_type, model)
        
        inspection_results[inspection_type] = {
            "maintenance_prediction": maintenance_prediction,
            "model_accuracy": round(accuracy, 2),
            "precision": round(precision, 2),
            "recall": round(recall, 2),
            "f1_score": round(f1, 2)
        }
        
        if vehicle_data:
            for key, value in vehicle_data.items():
                if key.startswith(inspection_type):
                    if key.endswith("_remarks"):
                        inspection_results[inspection_type]["remarks"] = value
                    else:
                        inspection_results[inspection_type]["status"] = value
    
    cursor.close()
    conn.close()
    
    # Print the results in a readable format
    print("\n===== Maintenance Prediction Results =====\n")
    print(f"Last Maintenance Mileage: {selectedCar_last_maintenance}")
    print(f"Current Mileage: {selectedCar_current_mileage}\n")
    
    if vehicle_data:
        print(f"Vehicle ID: {vehicle_data['id']}")
        print(f"Vehicle Name: {vehicle_data['vehicle']}\n")
    
    print("=== Inspection Results ===")
    for inspection, details in inspection_results.items():
        print(f"\nInspection Type: {inspection}")
        for key, value in details.items():
            print(f"  {key.replace('_', ' ').title()}: {value}")

except mysql.connector.Error as err:
    print("\n[ERROR] Database Error:", err)
except Exception as e:
    print("\n[ERROR] General Error:", e)
