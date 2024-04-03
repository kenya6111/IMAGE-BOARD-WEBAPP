-- CREATE TABLE IF NOT EXISTS car12 (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     make VARCHAR(50),
--     model VARCHAR(50),
--     year INT,
--     color VARCHAR(20),
--     price FLOAT,
--     mileage FLOAT,
--     transmission VARCHAR(20),
--     engine VARCHAR(20),
--     status VARCHAR(10)
-- );

CREATE TABLE IF NOT EXISTS table_migrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    script_filename VARCHAR(50)
);