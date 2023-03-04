CREATE TABLE `weatherforecastlr.weather.training_data` (
    `temperature` DECIMAL(4,3) NOT NULL,
    `feel_temperature` DECIMAL(4,3) NOT NULL,
    `relative_humidity` DECIMAL(4,3) NOT NULL,
    `cloud_cover` DECIMAL(4,3) NOT NULL,
    `wind_speed` DECIMAL(4,3) NOT NULL,
    `wind_gust` DECIMAL(4,3) NOT NULL,
    `rain` DECIMAL(4,3) NOT NULL,
    `visibility` DECIMAL(4,3) NOT NULL,
    `split_col` DECIMAL(4,3) NOT NULL,
    `timestamp` INT NOT NULL,
    `will_rain` TINYINT NOT NULL
);

CREATE TABLE `weatherforecastlr.weather.history_training_data` (
    `temperature` DECIMAL(4,3) NOT NULL,
    `feel_temperature` DECIMAL(4,3) NOT NULL,
    `relative_humidity` DECIMAL(4,3) NOT NULL,
    `cloud_cover` DECIMAL(4,3) NOT NULL,
    `wind_speed` DECIMAL(4,3) NOT NULL,
    `wind_gust` DECIMAL(4,3) NOT NULL,
    `rain` DECIMAL(4,3) NOT NULL,
    `visibility` DECIMAL(4,3) NOT NULL,
    `split_col` DECIMAL(4,3) NOT NULL,
    `timestamp` INT NOT NULL,
    `will_rain` TINYINT NOT NULL
);
