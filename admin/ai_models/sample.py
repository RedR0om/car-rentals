import json
import numpy as np
import pandas as pd
from prophet import Prophet
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Initialize Prophet model (CmdStanPy is the default backend)
model = Prophet()

# Generate a dataset with 50 dates and random sales values
data = pd.DataFrame({
    'ds': pd.date_range(start='2024-10-01', periods=50, freq='3D'),
    'y': np.random.uniform(1, 10, size=50)  # Using uniform instead of randint + noise
})

# Fit the model
model.fit(data)

# Predict for the next 60 days
future = model.make_future_dataframe(periods=60)
forecast = model.predict(future)

# Get next maintenance date (highest predicted sales)
next_maintenance = forecast.loc[forecast['ds'] > data['ds'].max()].nlargest(1, 'yhat')
next_maintenance_date = next_maintenance['ds'].iloc[0]
predicted_sales = round(next_maintenance['yhat'].iloc[0], 2)

# Calculate error metrics
forecast_actual = forecast.loc[forecast['ds'].isin(data['ds']), ['ds', 'yhat']]
metrics = {
    "MAE": mean_absolute_error(data['y'], forecast_actual['yhat']),
    "MSE": mean_squared_error(data['y'], forecast_actual['yhat']),
    "RMSE": np.sqrt(mean_squared_error(data['y'], forecast_actual['yhat'])),
    "R2": r2_score(data['y'], forecast_actual['yhat'])
}

# Output JSON
output = {
    "next_maintenance_date": str(next_maintenance_date),
    "predicted_sales": predicted_sales,
    "error_metrics": {k: round(v, 4) for k, v in metrics.items()}
}

print(json.dumps(output, indent=2))
