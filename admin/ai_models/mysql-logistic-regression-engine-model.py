import sys
import json
import numpy as np
import pandas as pd
import mysql.connector
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

# Get input values from command-line arguments
selectedCar_last_maintenance = float(sys.argv[1])  # last maintenance mileage
selectedCar_current_mileage = float(sys.argv[2])  # current mileage

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

# Database connection
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "carrental"
}

def predict_maintenance(last_maintenance, current_mileage, inspection_type, model):
    recommended_gap = INSPECTION_GAPS.get(inspection_type)
    if recommended_gap is None:
        return "Unknown Inspection Type"
    
    mileage_gap = current_mileage - last_maintenance
    if mileage_gap < recommended_gap:
        return "No"
    
    input_data = pd.DataFrame([[last_maintenance, current_mileage]], columns=["last_maintenance_mileage", "current_mileage"])
    prediction = model.predict(input_data)[0]
    return "Yes" if prediction == 1 else "No"

try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    
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
        
        maintenance_prediction = predict_maintenance(selectedCar_last_maintenance, selectedCar_current_mileage, inspection_type, model)
        
        inspection_results[inspection_type] = {
            "model_metrics": {
                "accuracy": f"{accuracy:.2f}",
                "precision": f"{precision:.2f}",
                "recall": f"{recall:.2f}",
                "f1_score": f"{f1:.2f}"
            },
            "class_distribution": class_distribution,
            "maintenance_prediction": maintenance_prediction
        }
    
    cursor.close()
    conn.close()
    
    result = {
        "selectedCar_last_maintenance": selectedCar_last_maintenance,
        "selectedCar_current_mileage": selectedCar_current_mileage,
        "inspection_results": inspection_results
    }
    
    print(json.dumps(result, indent=4))

except mysql.connector.Error as err:
    print(json.dumps({"error": "Database Error", "message": str(err)}))
except Exception as e:
    print(json.dumps({"error": "General Error", "message": str(e)}))
