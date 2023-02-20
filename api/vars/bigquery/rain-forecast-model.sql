CREATE MODEL `weatherforecastlr.weather.rain_forecast_model`
OPTIONS (
   MODEL_TYPE='LOGISTIC_REG',
   DATA_SPLIT_METHOD='SEQ',
   DATA_SPLIT_EVAL_FRACTION=0.3,
   DATA_SPLIT_COL='split_col',
   INPUT_LABEL_COLS=['will_rain']
  ) AS
SELECT
    temperature,  -- celsius
    feel_temperature, -- celsius (apparent temp, feelslike)
    relative_humidity, -- percent
    cloud_cover, -- percent
    wind_speed, --km/h
    wind_gust, -- km/h
    rain, -- mm
    visibility, -- km
    RAND() AS split_col,
    will_rain -- bool
FROM `weatherforecastlr.weather.training_data` td;
