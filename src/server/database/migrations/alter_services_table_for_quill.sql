-- Alter Services table to store HTML content for the service list
ALTER TABLE `Services`
CHANGE COLUMN `service_list` `services` TEXT; 