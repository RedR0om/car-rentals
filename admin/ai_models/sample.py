import json
import os
import numpy as np
import pandas as pd
from prophet import Prophet
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Set a writable directory for Matplotlib configuration
os.environ["MPLCONFIGDIR"] = "/tmp/matplotlib"
if not os.path.exists("/tmp/matplotlib"):
    os.makedirs("/tmp/matplotlib", exist_ok=True)

# Force Prophet to use the PyStan backend instead of cmdstanpy
model = Prophet(stan_backend='pystan')

# Hardcoded dataset with 50 date rows and sales numbers between 1 and 10
data = pd.DataFrame({
    'ds': ['2024-10-01', '2024-10-03', '2024-10-06', '2024-10-09', '2024-10-12',
           '2024-10-15', '2024-10-18', '2024-10-21', '2024-10-24', '2024-10-27',
           '2024-10-30', '2024-11-02', '2024-11-05', '2024-11-08', '2024-11-11',
           '2024-11-14', '2024-11-17', '2024-11-20', '2024-11-23', '2024-11-26',
           '2024-11-29', '2024-12-02', '2024-12-05', '2024-12-08', '2024-12-11',
           '2024-12-14', '2024-12-17', '2024-12-20', '2024-12-23', '2024-12-26',
           '2024-12-29', '2025-01-01', '2025-01-04', '2025-01-07', '2025-01-10',
           '2025-01-13', '2025-01-16', '2025-01-19', '2025-01-22', '2025-01-25',
           '2025-01-28', '2025-01-31', '2025-02-03', '2025-02-06', '2025-02-09',
           '2025-02-12', '2025-02-15', '2025-02-18', '2025-02-21', '2025-02-24'],
    'y': [5, 3, 6, 2, 7, 4, 8, 9, 5, 6, 4, 7, 1, 9, 3, 5, 6, 8, 2, 7,
          3, 5, 8, 6, 9, 4, 7, 3, 5, 6, 8, 9, 2, 6, 4, 5, 7, 3, 9, 6,
          8, 2, 7, 4, 9, 5, 6, 1, 3, 8]
})

# Convert 'ds' to datetime
data['ds'] = pd.to_datetime(data['ds'])

# Apply a slight randomization (safety net)
data['ds'] = data['ds'] + pd.to_timedelta(np.random.uniform(-1, 1, len(data)), unit='D')
data['y'] = data['y'] + np.random.normal(0, 0.01, len(data))

# Fit the model on the data
model.fit(data)

# Create future dataframe (next 60 days)
future = model.make_future_dataframe(periods=60)
forecast = model.predict(future)

# Find the next maintenance date based on highest predicted value after the latest known date
latest_known_date = data['ds'].max()
future_forecast = forecast[forecast['ds'] > latest_known_date]
next_maintenance = future_forecast.nlargest(1, 'yhat')
next_maintenance_date = next_maintenance['ds'].values[0]
predicted_sales = next_maintenance['yhat'].values[0]

# Filter forecast for the actual data range
forecast_actual = forecast[forecast['ds'].isin(data['ds'])]

# Calculate error metrics
mae = mean_absolute_error(data['y'], forecast_actual['yhat'])
mse = mean_squared_error(data['y'], forecast_actual['yhat'])
rmse = np.sqrt(mse)
r2 = r2_score(data['y'], forecast_actual['yhat'])

# Prepare JSON output
output = {
    "next_maintenance_date": str(next_maintenance_date),
    "predicted_sales": round(predicted_sales, 2),
    "error_metrics": {
        "MAE": round(mae, 4),
        "MSE": round(mse, 4),
        "RMSE": round(rmse, 4),
        "R2": round(r2, 4)
    }
}

# Print JSON output so PHP can capture it
print(json.dumps(output))
