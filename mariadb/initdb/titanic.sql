CREATE TABLE IF NOT EXISTS `titanic` (
    `index` INT NOT NULL PRIMARY KEY,
    `PassengerId` INT NOT NULL,
    `Survived` INT NOT NULL,
    `Pclass` INT NOT NULL,
    `Name` VARCHAR(255) NOT NULL,
    `Sex` VARCHAR(10) NOT NULL,
    `Age` FLOAT NULL,
    `SibSp` INT NOT NULL,
    `Parch` INT NOT NULL,
    `Ticket` VARCHAR(50) NOT NULL,
    `Fare` DECIMAL(10, 4) NOT NULL,
    `Cabin` VARCHAR(50) NULL,
    `Embarked` VARCHAR(5) NULL
);

TRUNCATE TABLE `titanic`;

INSERT INTO `titanic` (`index`, `PassengerId`, `Survived`, `Pclass`, `Name`, `Sex`, `Age`, `SibSp`, `Parch`, `Ticket`, `Fare`, `Cabin`, `Embarked`) VALUES
(0, 1, 0, 3, 'Braund, Mr. Owen Harris', 'male', 22, 1, 0, 'A/5 21171', 7.2500, '', 'S'),
(1, 2, 1, 1, 'Cumings, Mrs. John Bradley (Florence Briggs Thayer)', 'female', 38, 1, 0, 'PC 17599', 71.2833, 'C85', 'C'),
(2, 3, 1, 3, 'Heikkinen, Miss. Laina', 'female', 26, 0, 0, 'STON/O2. 3101282', 7.9250, '', 'S'),
(3, 4, 1, 1, 'Futrelle, Mrs. Jacques Heath (Lily May Peel)', 'female', 35, 1, 0, '113803', 53.1000, 'C123', 'S'),
(4, 5, 0, 3, 'Allen, Mr. William Henry', 'male', 35, 0, 0, '373450', 8.0500, '', 'S'),
(5, 6, 0, 3, 'Moran, Mr. James', 'male', NULL, 0, 0, '330877', 8.4583, '', 'Q'),
(6, 7, 0, 1, 'McCarthy, Mr. Timothy J', 'male', 54, 0, 0, '17463', 51.8625, 'E46', 'S'),
(7, 8, 0, 3, 'Palsson, Master. Gosta Leonard', 'male', 2, 3, 1, '349909', 21.0750, '', 'S'),
(8, 9, 1, 3, 'Johnson, Mrs. Oscar W (Elisabeth Vilhelmina Berg)', 'female', 27, 0, 2, '347742', 11.1333, '', 'S'),
(9, 10, 1, 2, 'Nasser, Mrs. Nicholas (Adele Achem)', 'female', 14, 1, 0, '237736', 30.0708, '', 'C'),
(10, 11, 1, 3, 'Sandstrom, Miss. Marguerite Rut', 'female', 4, 1, 1, 'PP 9549', 16.7000, 'G6', 'S'),
(11, 12, 1, 1, 'Bonnell, Miss. Elizabeth', 'female', 58, 0, 0, '113783', 26.5500, 'C103', 'S'),
(12, 13, 0, 3, 'Saundercock, Mr. William Henry', 'male', 20, 0, 0, 'A/5. 2151', 8.0500, '', 'S'),
(13, 14, 0, 3, 'Andersson, Mr. Anders Johan', 'male', 39, 1, 5, '347082', 31.2750, '', 'S'),
(14, 15, 0, 3, 'Vestrom, Miss. Hulda Amanda Adolfina', 'female', 14, 0, 0, '350406', 7.8542, '', 'S'),
(15, 16, 1, 2, 'Hewlett, Mrs. (Mary D Kingcome)', 'female', 55, 0, 0, '248706', 16.0000, '', 'S'),
(16, 17, 0, 3, 'Rice, Master. Eugene', 'male', 2, 4, 1, '382652', 29.1250, '', 'Q'),
(17, 18, 1, 2, 'Williams, Mr. Charles Eugene', 'male', NULL, 0, 0, '244373', 13.0000, '', 'S'),
(18, 19, 0, 3, 'Vander Planke, Mrs. Julius (Emelia Maria Vandemoortele)', 'female', 31, 1, 0, '345763', 18.0000, '', 'S'),
(19, 20, 1, 3, 'Masselmani, Mrs. Fatima', 'female', NULL, 0, 0, '2649', 7.2250, '', 'C');
