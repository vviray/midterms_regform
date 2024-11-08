CREATE TABLE users (
    user_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    date_account_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE web_devs (
    web_dev_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE, -- One-to-One relationship with users
    specialization VARCHAR(100) NOT NULL,
    hire_date TIMESTAMP NOT NULL,
    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE projects (
    project_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    project_title VARCHAR(100) NOT NULL,
    technologies_used VARCHAR(255) NOT NULL,
    web_dev_id INT,
    date_started DATE NOT NULL,
    date_finished DATE,
    CONSTRAINT fk_web_dev_id FOREIGN KEY (web_dev_id) REFERENCES web_devs(web_dev_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


DELIMITER //

CREATE PROCEDURE add_user_with_web_dev(
    IN p_username VARCHAR(50),
    IN p_password VARCHAR(255),
    IN p_first_name VARCHAR(50),
    IN p_last_name VARCHAR(50),
    IN p_date_of_birth DATE,
    IN p_specialization VARCHAR(100)
)
BEGIN
    -- Declare variable for last inserted user_id
    DECLARE last_user_id INT;

    -- Insert into users table
    INSERT INTO users (username, password, first_name, last_name, date_of_birth, date_account_created)
    VALUES (p_username, p_password, p_first_name, p_last_name, p_date_of_birth, CURRENT_TIMESTAMP);

    -- Get the last inserted user_id
    SET last_user_id = LAST_INSERT_ID();

    -- Insert into web_devs table using the last inserted user_id
    INSERT INTO web_devs (user_id, specialization, hire_date)
    VALUES (last_user_id, p_specialization, CURRENT_TIMESTAMP);
END //

DELIMITER ;
