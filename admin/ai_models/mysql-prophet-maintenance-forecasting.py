import mysql.connector
import pandas as pd
from prophet import Prophet
import matplotlib.pyplot as plt
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import numpy as np
import json

# Database connection
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "carrental"
}

try:
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    # Fetch data (replace 'sales_table' with your actual table name)
    query = "SELECT LastInspectionDate AS ds, InspectionSalesCount AS y FROM tblvehicles WHERE LastInspectionDate IS NOT NULL AND InspectionSalesCount IS NOT NULL ORDER BY LastInspectionDate ASC"     
    cursor.execute(query)
    data = cursor.fetchall()

    cursor.close()
    conn.close()

    # Convert to DataFrame
    df = pd.DataFrame(data)
    df['ds'] = pd.to_datetime(df['ds'])  # Convert date column to datetime

    # Initialize and fit Prophet model
    model = Prophet()
    model.fit(df)

    # Create future dataframe (next 90 days)
    future = model.make_future_dataframe(periods=90)
    
    # Get forecast
    forecast = model.predict(future)

    # Find next peak sales (maintenance needed)
    latest_known_date = df['ds'].max()
    future_forecast = forecast[forecast['ds'] > latest_known_date]
    next_maintenance = future_forecast.nlargest(1, 'yhat')

    # Extract results
    next_maintenance_date = next_maintenance['ds'].values[0]
    predicted_sales = next_maintenance['yhat'].values[0]

    # Convert numpy.datetime64 to Python datetime
    next_maintenance_date = pd.to_datetime(next_maintenance_date)

    # Format the datetime to string for JSON serialization
    next_maintenance_date_str = next_maintenance_date.strftime('%B %d, %Y')

    # Plot forecast
    #fig1 = model.plot(forecast)
    #plt.title("Forecast with Prophet")
    #plt.xlabel("Date")
    #plt.ylabel("Sales")
    #plt.show()

    # Plot components
    #fig2 = model.plot_components(forecast)
    #plt.show()

    # Evaluate model accuracy
    forecast_actual = forecast[forecast['ds'].isin(df['ds'])]
    mae = mean_absolute_error(df['y'], forecast_actual['yhat'])
    mse = mean_squared_error(df['y'], forecast_actual['yhat'])
    rmse = np.sqrt(mse)
    r2 = r2_score(df['y'], forecast_actual['yhat'])


    predicted_sales = round(predicted_sales, 2)

    # Return JSON output
    result = {
        "next_maintenance_date": next_maintenance_date_str,
        "predicted_sales": predicted_sales,
        "mae": mae,
        "mse": mse,
        "rmse": rmse,
        "r2": r2
    }

    print(json.dumps(result))

except mysql.connector.Error as err:
    print(json.dumps({"error": str(err)}))
