# create test database
CREATE DATABASE IF NOT EXISTS `smp-imeja`;

# grant rights
GRANT ALL PRIVILEGES ON `smp-imeja`.* TO 'smp-imeja'@'%';
