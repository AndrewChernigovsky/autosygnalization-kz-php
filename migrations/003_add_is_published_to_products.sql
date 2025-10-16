-- Add is_published flag to Products table if it doesn't exist
ALTER TABLE `Products`
  ADD COLUMN `is_published` TINYINT(1) NOT NULL DEFAULT 0 AFTER `price_list`;


