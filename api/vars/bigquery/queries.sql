-- Move training data to history table
INSERT INTO `weatherforecastlr.weather.history_training_data` (temperature, feel_temperature, relative_humidity, cloud_cover, wind_speed, wind_gust, rain, visibility, split_col, timestamp, will_rain) SELECT * FROM `weatherforecastlr.weather.training_data`;

-- Clear already trained data
DELETE FROM `weatherforecastlr.weather.training_data` WHERE 1=1;
