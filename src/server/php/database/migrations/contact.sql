CREATE TABLE IF NOT EXISTS PhoneContacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(20) NOT NULL,
    link VARCHAR(30) NOT NULL,
    title VARCHAR(100) NOT NULL,  
    position INT NOT NULL DEFAULT 0,
    UNIQUE (phone_number),
    INDEX (position)
);