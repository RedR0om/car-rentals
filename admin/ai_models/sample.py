import json
import numpy as np
import pandas as pd
from prophet import Prophet
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Initialize Prophet with PyStan backend
model = Prophet(stan_backend='PYTHON-2')
# If you encounter issues, try 'PYTHON-2' or 'PYTHON-3'

# Hardcoded dataset with 50 date rows and sales numbers between 1 and 10
data = pd.DataFrame({
    'ds': pd.date_range(start='2024-10-01', periods=50, freq='3D'),
    'y': np.random.randint(1, 10, size=50) + np.random.normal(0, 0.01, 50)
})

# Fit the model
model.fit(data)

# Create future dataframe (next 60 days) and predict
future = model.make_future_dataframe(periods=60)
forecast = model.predict(future)

# Find the next maintenance date based on the highest predicted 'yhat'
latest_known_date = data['ds'].max()
future_forecast = forecast[forecast['ds'] > latest_known_date]
next_maintenance = future_forecast.nlargest(1, 'yhat')
next_maintenance_date = next_maintenance['ds'].values[0]
predicted_sales = next_maintenance['yhat'].values[0]

# Calculate error metrics
forecast_actual = forecast[forecast['ds'].isin(data['ds'])]
mae = mean_absolute_error(data['y'], forecast_actual['yhat'])
mse = mean_squared_error(data['y'], forecast_actual['yhat'])
rmse = np.sqrt(mse)
r2 = r2_score(data['y'], forecast_actual['yhat'])

# Output JSON
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

print(json.dumps(output))
