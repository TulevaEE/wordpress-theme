-- Named so that we can easily import the live database
CREATE DATABASE IF NOT EXISTS `d56838_tuleva20` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
GRANT ALL PRIVILEGES ON `d56838_tuleva20`.* TO 'wordpress'@'%';
FLUSH PRIVILEGES;
