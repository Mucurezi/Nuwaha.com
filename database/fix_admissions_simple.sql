-- Simple fix for admissions table
-- Copy and paste this into phpMyAdmin SQL tab

USE muteesaiidb;

-- Add all missing columns (will show error if column exists, but that's okay)
ALTER TABLE admissions ADD COLUMN gender ENUM('male', 'female', 'other') NULL AFTER date_of_birth;
ALTER TABLE admissions ADD COLUMN relationship VARCHAR(50) NULL AFTER parent_name;
ALTER TABLE admissions ADD COLUMN address TEXT NULL AFTER email;
ALTER TABLE admissions ADD COLUMN previous_school VARCHAR(200) NULL;
ALTER TABLE admissions ADD COLUMN medical_conditions TEXT NULL;
ALTER TABLE admissions ADD COLUMN special_needs TEXT NULL;
ALTER TABLE admissions ADD COLUMN submitted_by INT NULL;

-- Add foreign key
ALTER TABLE admissions ADD CONSTRAINT fk_admissions_user FOREIGN KEY (submitted_by) REFERENCES users(user_id) ON DELETE SET NULL;

-- Show the updated table structure
DESCRIBE admissions;
