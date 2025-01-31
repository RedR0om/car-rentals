import numpy as np
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score
from datetime import datetime

# Dataset
data = {
    'last_maintenance_mileage': [
        25000, 15000, 2000, 35000, 15000, 22000, 2500, 28000, 16000, 23000,
        14000, 3000, 12000, 38000, 19000, 12000, 29000, 4500, 19000, 14000,
        4500, 33000, 19000, 2500, 28000, 2500, 10500, 21000, 19500, 39000,
        10500, 19000, 24000, 20500, 20500, 34000, 29000, 10500, 29000, 14500,
        10500, 1000, 34000, 10500, 4500, 2500, 2500, 19000, 4500, 10500
    ],
    'current_mileage': [
        28000, 18000, 4000, 38000, 21000, 25000, 4000, 30000, 17000, 24000,
        13000, 3500, 13000, 40000, 21000, 12000, 29000, 5500, 21000, 15000,
        5500, 34000, 21000, 3000, 29000, 3000, 11000, 22000, 20000, 40000,
        11500, 20000, 24500, 21500, 21000, 34000, 29500, 11500, 29500, 15000,
        11000, 1500, 34000, 11500, 5500, 2500, 2500, 20000, 5500, 11500
    ],
    'year_model': [
        2018, 2024, 2021, 2020, 2020, 2021, 2021, 2020, 2018, 2016, 2020, 2020,
        2016, 2020, 2020, 2019, 2019, 2017, 2021, 2017, 2024, 2017, 2022, 2022,
        2024, 2023, 2017, 2022, 2016, 2015, 2024, 2021, 2020, 2022, 2020, 2015,
        2016, 2015, 2023, 2019, 2015, 2022, 2019, 2017, 2022, 2016, 2021, 2021,
        2023, 2022
    ],
    'maintenance': [
        1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 0, 1, 1, 1, 1, 0
    ]
}

# Convert to DataFrame
df = pd.DataFrame(data)

# Calculate the car age: current year - year_model
current_year = datetime.now().year
df['car_age'] = current_year - df['year_model']

# Features and Target
X = df[["last_maintenance_mileage", "current_mileage", "car_age"]]  # Features
y = df["maintenance"]  # Target (maintenance already given)

# Check label distribution
print("Class Distribution in Dataset:", y.value_counts())

# Split Data (80% Train, 20% Test) with stratification
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42, stratify=y
)

# Check class distribution in training data
print("Class Distribution in Training Set:", y_train.value_counts())

# Train Logistic Regression Model
model = LogisticRegression()
model.fit(X_train, y_train)

# Predictions
y_pred = model.predict(X_test)

# Model Performance Metrics
accuracy = accuracy_score(y_test, y_pred)
precision = precision_score(y_test, y_pred, zero_division=1)
recall = recall_score(y_test, y_pred, zero_division=1)
f1 = f1_score(y_test, y_pred, zero_division=1)

print(f"Model Accuracy: {accuracy:.2f}")
print(f"Precision: {precision:.2f}")
print(f"Recall: {recall:.2f}")
print(f"F1 Score: {f1:.2f}")

# Function to Predict Maintenance
def predict_maintenance(last_maintenance, current_mileage, car_age):
    # Create a DataFrame with correct feature names
    input_data = pd.DataFrame([[last_maintenance, current_mileage, car_age]], columns=["last_maintenance_mileage", "current_mileage", "car_age"])
    
    # Make the prediction
    prediction = model.predict(input_data)[0]
    
    return "Yes" if prediction == 1 else "No"


# Replace with actual data from the system (This will be used to for checking)
selectedCar_last_maintenance = 15000
selectedCar_current_mileage = 26000
selectedCar_car_age = current_year - 2020
print(f"Maintenance Needed for {selectedCar_current_mileage} miles (Car Age: {selectedCar_car_age} years): {predict_maintenance(selectedCar_last_maintenance, selectedCar_current_mileage, selectedCar_car_age)}")
