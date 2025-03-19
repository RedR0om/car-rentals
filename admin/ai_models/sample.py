import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from prophet import Prophet

# Create a simple dataset using pandas
data = {
    'ds': pd.date_range('20230101', periods=100),
    'y': np.random.randn(100).cumsum()  # Cumulative sum of random numbers
}

df = pd.DataFrame(data)

# Plot the data using Matplotlib
plt.figure(figsize=(10, 6))
plt.plot(df['ds'], df['y'], label='Data')
plt.title('Random Data Plot')
plt.xlabel('Date')
plt.ylabel('Value')
plt.legend()

# Save the plot to a file (e.g., "random_data_plot.png")
plt.savefig('random_data_plot.png')

# Initialize and fit a Prophet model
model = Prophet()
model.fit(df)

# Make a forecast for the next 30 days
future = model.make_future_dataframe(df, periods=30)
forecast = model.predict(future)

# Plot the forecast
plt.figure(figsize=(10, 6))
model.plot(forecast)
plt.title('Forecast using Prophet')

# Save the forecast plot to a file (e.g., "forecast_plot.png")
plt.savefig('forecast_plot.png')
