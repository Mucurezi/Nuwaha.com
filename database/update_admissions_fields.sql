-- Add additional fields to admissions table
USE muteesaiidb;

-- Add new columns for complete admission information
ALTER TABLE admissions 
ADD COLUMN gender ENUM('male', 'female') NULL AFTER date_of_birth,
ADD COLUMN relationship VARCHAR(50) NULL AFTER parent_name,
ADD COLUMN address TEXT NULL AFTER email,
ADD COLUMN previous_school VARCHAR(200) NULL AFTER address,
ADD COLUMN medical_conditions TEXT NULL AFTER previous_school,
ADD COLUMN special_needs TEXT NULL AFTER medical_conditions;

-- Update existing records to have NULL for new fields (they're already NULL by default)
-- This is just for documentation purposes
