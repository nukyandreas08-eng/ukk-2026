USE db_ukk_2026;

-- Password demo: admin123 / guru123
UPDATE t_user SET password='$2y$12$Y0L8g4BsFC6Z3UoGjloVyuU2iaAOBbv4OhQBIKmkfALmjD4tOYvi2' WHERE email='admin@gmail.com';
UPDATE t_user SET password='$2y$12$87U7mLLDPTWjRwxFFUjTvuH8pcQOcKfh/9OS7IBOMgR5jkragnMIa' WHERE email='guru@gmail.com';

-- Optional account untuk aktor Wali Kelas pada diagram.
INSERT INTO t_user (name,email,password,remember_token,role)
SELECT 'Wali Kelas','wali@ukk2026.sch.id','$2y$12$87U7mLLDPTWjRwxFFUjTvuH8pcQOcKfh/9OS7IBOMgR5jkragnMIa','','wali_kelas'
WHERE NOT EXISTS (SELECT 1 FROM t_user WHERE email='wali@ukk2026.sch.id');
