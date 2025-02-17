from prophet import Prophet
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import numpy as np

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
           '2025-01-28', '2025-01-31', '2025-01-03', '2025-01-06', '2025-01-09',
           '2025-01-12', '2025-01-15', '2025-01-18', '2025-01-20', '2025-01-29'],
    'y': [5, 3, 6, 2, 7, 4, 8, 9, 5, 6, 4, 7, 1, 9, 3, 5, 6, 8, 2, 7,
          3, 5, 8, 6, 9, 4, 7, 3, 5, 6, 8, 9, 2, 6, 4, 5, 7, 3, 9, 6,
          8, 2, 7, 4, 9, 5, 6, 1, 3, 8]
    
})

# Convert 'ds' to datetime
data['ds'] = pd.to_datetime(data['ds'])

# Safety net Randomization
data['ds'] = data['ds'] + pd.to_timedelta(np.random.uniform(-1, 1, len(data)), unit='D')
data['y'] = data['y'] + np.random.normal(0, 0.01, len(data))

# Initialize the Prophet model
model = Prophet()

# Fit the model on the data
model.fit(data)

# Create a dataframe for future predictions (predict for the next 90 days)
future = model.make_future_dataframe(periods=90)

# Get the forecast
forecast = model.predict(future)

# Find the next maintenance date
latest_known_date = data['ds'].max()
future_forecast = forecast[forecast['ds'] > latest_known_date]  # Only future predictions

# Identify peak sales predictions for maintenance (highest predicted 'yhat')
next_maintenance = future_forecast.nlargest(1, 'yhat')  # Get the highest predicted sales date

# Extract the date and predicted sales value
next_maintenance_date = next_maintenance['ds'].values[0]
predicted_sales = next_maintenance['yhat'].values[0]

# Print the results
print(f"Next predicted maintenance date: {next_maintenance_date}")
print(f"Predicted sales before maintenance: {predicted_sales:.2f}")

# Plot the forecast
fig1 = model.plot(forecast)
plt.title("Forecast with Prophet")
plt.xlabel("Date")
plt.ylabel("Metric (y)")
plt.show()

# Plot the forecast components (trend, weekly, yearly seasonality)
fig2 = model.plot_components(forecast)
plt.show()

# Filter the forecast for the actual data range
forecast_actual = forecast[forecast['ds'].isin(data['ds'])]

# Calculate MAE, MSE, RMSE, and R-squared
mae = mean_absolute_error(data['y'], forecast_actual['yhat'])
mse = mean_squared_error(data['y'], forecast_actual['yhat'])
rmse = np.sqrt(mse)
r2 = r2_score(data['y'], forecast_actual['yhat'])

# Print the metrics
print("Mean Absolute Error (MAE):", mae)
print("Mean Squared Error (MSE):", mse)
print("Root Mean Squared Error (RMSE):", rmse)
print("R-squared:", r2)
