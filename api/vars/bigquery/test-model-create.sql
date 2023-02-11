CREATE MODEL `weather.forecast_model`
OPTIONS (
   MODEL_TYPE='LOGISTIC_REG',
   DATA_SPLIT_METHOD='SEQ',
   DATA_SPLIT_EVAL_FRACTION=0.3,
   DATA_SPLIT_COL='split_col',
   INPUT_LABEL_COLS=['will_snow']
  ) AS
SELECT
    ws.temperature_air_2m_f,
    ws.temperature_windchill_2m_f,
    ws.temperature_heatindex_2m_f,
    ws.humidity_specific_2m_gpkg,
    ws.humidity_relative_2m_pct,
    ws.wind_speed_10m_mph,
    RAND() AS split_col,
    ws.cloud_cover_pct,
    (
        SELECT IF(ws_inner.snowfall_in IS NOT NULL, ws_inner.snowfall_in, 0)
        FROM `weathersource-com.pub_weather_data_samples.sample_weather_forecast_great_britain_postalcode_hourly` ws_inner
        WHERE ws_inner.hour_utc=ws.hour_utc+4 AND ws_inner.postal_code=ws.postal_code AND ws_inner.doy_utc=ws.doy_utc
    ) AS will_snow
FROM `weathersource-com.pub_weather_data_samples.sample_weather_forecast_great_britain_postalcode_hourly` ws
LIMIT 1000;
