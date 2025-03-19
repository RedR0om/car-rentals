import sys
import json
import numpy as np
import pandas as pd
import mysql.connector
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

# Get input values from command line arguments
selectedCar_last_maintenance = float(sys.argv[1])
selectedCar_current_mileage = float(sys.argv[2])
vehicleId = int(sys.argv[3])

# Recommended mileage gaps based on inspection type (in miles)
INSPECTION_GAPS = {...}  # Keep your existing dictionary
MAX_INSPECTION_GAPS = {...}  # Keep your existing dictionary

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
    
    output = {
        "last_maintenance_mileage": selectedCar_last_maintenance,
        "current_mileage": selectedCar_current_mileage,
        "vehicle_data": vehicle_data if vehicle_data else "No vehicle data found",
        "inspection_results": inspection_results
    }
    
    print(json.dumps(output))
    
except mysql.connector.Error as err:
    print(json.dumps({"error": "Database Error", "details": str(err)}))
except Exception as e:
    print(json.dumps({"error": "General Error", "details": str(e)}))
