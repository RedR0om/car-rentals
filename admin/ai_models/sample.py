import json
import sys
import numpy as np
import pandas as pd
from prophet import Prophet
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import platform
import importlib.metadata
import cmdstanpy

try:
    # Debug info
    python_version = platform.python_version()
    installed_packages = sorted([dist.metadata["Name"] for dist in importlib.metadata.distributions()])

    # Fix cmdstanpy permission issue
    cmdstanpy.set_cmdstan_path("/tmp/cmdstan")

    # Initialize Prophet model
    model = Prophet()

    # Generate a dataset with 50 dates and random sales values
    data = pd.DataFrame({
        'ds': pd.date_range(start='2024-10-01', periods=50, freq='3D'),
        'y': np.random.uniform(1, 10, size=50)  # Using uniform distribution for random sales
    })

    # Fit the model
    model.fit(data)

    # Predict for the next 60 days
    future = model.make_future_dataframe(periods=60)
    forecast = model.predict(future)

    # Get next maintenance date (highest predicted sales)
    next_maintenance = forecast.loc[forecast['ds'] > data['ds'].max()].nlargest(1, 'yhat')
    
    # Check if data exists
    if next_maintenance.empty:
        raise ValueError("No valid predictions found for maintenance date.")

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
        "python_version": python_version,
        "installed_packages": installed_packages,
        "message": "Prediction successful",
        "status": "OK",
        "next_maintenance_date": str(next_maintenance_date),
        "predicted_sales": predicted_sales,
        "error_metrics": {k: round(v, 4) for k, v in metrics.items()}
    }

except Exception as e:
    output = {
        "error": str(e),
        "status": "ERROR"
    }

# Ensure JSON output
print(json.dumps(output))
sys.exit(0)
