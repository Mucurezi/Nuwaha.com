-- Complete fix for admissions table
-- Run this in phpMyAdmin to add all missing columns

USE muteesaiidb;

-- First, check if columns exist and add them if they don't
-- Add gender column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'gender';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN gender ENUM(''male'', ''female'', ''other'') NULL AFTER date_of_birth',
    'SELECT "gender column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add relationship column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'relationship';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN relationship VARCHAR(50) NULL AFTER parent_name',
    'SELECT "relationship column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add address column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'address';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN address TEXT NULL AFTER email',
    'SELECT "address column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add previous_school column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'previous_school';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN previous_school VARCHAR(200) NULL AFTER address',
    'SELECT "previous_school column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add medical_conditions column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'medical_conditions';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN medical_conditions TEXT NULL AFTER previous_school',
    'SELECT "medical_conditions column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add special_needs column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'special_needs';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN special_needs TEXT NULL AFTER medical_conditions',
    'SELECT "special_needs column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add submitted_by column
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND COLUMN_NAME = 'submitted_by';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE admissions ADD COLUMN submitted_by INT NULL AFTER special_needs',
    'SELECT "submitted_by column already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key constraint for submitted_by (if it doesn't exist)
SET @fk_exists = 0;
SELECT COUNT(*) INTO @fk_exists 
FROM information_schema.TABLE_CONSTRAINTS 
WHERE TABLE_SCHEMA = 'muteesaiidb' 
AND TABLE_NAME = 'admissions' 
AND CONSTRAINT_NAME = 'fk_admissions_user';

SET @query = IF(@fk_exists = 0, 
    'ALTER TABLE admissions ADD CONSTRAINT fk_admissions_user FOREIGN KEY (submitted_by) REFERENCES users(user_id) ON DELETE SET NULL',
    'SELECT "foreign key already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Show final table structure
DESCRIBE admissions;

SELECT 'Database update complete! All columns added successfully.' AS Status;
