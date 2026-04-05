-- Add submitted_by column to admissions table to link with users
USE muteesaiidb;

-- Add the column to track who submitted the application
ALTER TABLE admissions 
ADD COLUMN submitted_by INT NULL AFTER email;

-- Add foreign key constraint
ALTER TABLE admissions 
ADD CONSTRAINT fk_admissions_user 
FOREIGN KEY (submitted_by) REFERENCES users(user_id) ON DELETE SET NULL;
