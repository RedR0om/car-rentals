import mysql.connector
import pandas as pd
from prophet import Prophet
import matplotlib.pyplot as plt
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import numpy as np
import json
from datetime import datetime, timedelta

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
    
    # Fetch the latest prediction entry from tbl_predictions
    query_latest = "SELECT prediction_date FROM tbl_predictions ORDER BY id DESC LIMIT 1"
    cursor.execute(query_latest)
    latest_prediction = cursor.fetchone()
    
    # Get the current date
    current_date = datetime.now()

    # Calculate the difference in days
    days_diff = (current_date - latest_prediction['prediction_date']).days
        
    if days_diff < 5:
        # Format the prediction_date to string in the same format
        latest_prediction_date_str = latest_prediction['prediction_date'].strftime('%B %d, %Y')

        # Return JSON output
        result = {
            "next_maintenance_date": latest_prediction_date_str,
            "predicted_sales": 0,
            "mae": 0,
            "mse": 0,
            "rmse": 0,
            "r2": 0
        }
        
        # Print the result and exit the script
        print(json.dumps(result))
        exit()  # End the script if condition is true
 
    else:
        # Fetch data (replace 'sales_table' with your actual table name)
        query = "SELECT LastInspectionDate AS ds, InspectionSalesCount AS y FROM tblvehicles WHERE LastInspectionDate IS NOT NULL AND InspectionSalesCount IS NOT NULL ORDER BY LastInspectionDate ASC"     
        cursor.execute(query)
        data = cursor.fetchall()

        # Convert to DataFrame
        df = pd.DataFrame(data)
        df['ds'] = pd.to_datetime(df['ds'])  # Convert date column to datetime

    # Safety net Randomization
        df['ds'] = df['ds'] + pd.to_timedelta(np.random.uniform(-1, 1, len(df)), unit='D')
        df['y'] = df['y'] + np.random.normal(0, 0.01, len(df))
        
        # Initialize and fit Prophet model
        model = Prophet()
        model.fit(df)

        # Create future dataframe (next 60 days)
        future = model.make_future_dataframe(periods=60)
        
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
        
        # Insert next_maintenance_date into the tbl_predictions table
        query_insert = """
            INSERT INTO tbl_predictions (prediction_date)
            VALUES (%s)
        """
        cursor = conn.cursor()
        cursor.execute(query_insert, (next_maintenance_date,))
        conn.commit()  # Commit the transaction to save the changes

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
        
        cursor.close()
        conn.close()

except mysql.connector.Error as err:
    print(json.dumps({"error": str(err)}))
