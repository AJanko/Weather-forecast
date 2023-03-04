CREATE TABLE `weatherforecastlr.weather.training_data` (
    `temperature` FLOAT64 NOT NULL,
    `feel_temperature` FLOAT64 NOT NULL,
    `relative_humidity` FLOAT64 NOT NULL,
    `cloud_cover` FLOAT64 NOT NULL,
    `wind_speed` FLOAT64 NOT NULL,
    `wind_gust` FLOAT64 NOT NULL,
    `rain` FLOAT64 NOT NULL,
    `visibility` FLOAT64 NOT NULL,
    `split_col` FLOAT64 NOT NULL,
    `timestamp` INT NOT NULL,
    `will_rain` TINYINT NOT NULL
);

CREATE TABLE `weatherforecastlr.weather.history_training_data` (
    `temperature` FLOAT64 NOT NULL,
    `feel_temperature` FLOAT64 NOT NULL,
    `relative_humidity` FLOAT64 NOT NULL,
    `cloud_cover` FLOAT64 NOT NULL,
    `wind_speed` FLOAT64 NOT NULL,
    `wind_gust` FLOAT64 NOT NULL,
    `rain` FLOAT64 NOT NULL,
    `visibility` FLOAT64 NOT NULL,
    `split_col` FLOAT64 NOT NULL,
    `timestamp` INT NOT NULL,
    `will_rain` TINYINT NOT NULL
);
