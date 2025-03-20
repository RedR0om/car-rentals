import json
import numpy as np
import pandas as pd
from prophet import Prophet
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Hardcoded dataset with 50 date rows and sales numbers between 1 and 10
data = pd.DataFrame({
    'ds': pd.date_range(start='2024-10-01', periods=50, freq='3D'),
    'y': np.random.randint(1, 10, size=50)
})

# Safety net Randomization
data['ds'] = data['ds'] + pd.to_timedelta(np.random.uniform(-1, 1, len(data)), unit='D')
data['y'] = data['y'] + np.random.normal(0, 0.01, len(data))

# Initialize and fit Prophet model
model = Prophet()
model.fit(data)

# Create a dataframe for future predictions (predict for the next 60 days)
future = model.make_future_dataframe(periods=60)
forecast = model.predict(future)

# Find the next maintenance date
latest_known_date = data['ds'].max()
future_forecast = forecast[forecast['ds'] > latest_known_date]
next_maintenance = future_forecast.nlargest(1, 'yhat')

# Extract maintenance details
next_maintenance_date = next_maintenance['ds'].values[0]
predicted_sales = next_maintenance['yhat'].values[0]

# Filter the forecast for actual data range
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

# Print JSON output
print(json.dumps(output))
