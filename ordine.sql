USE fruttidafavola;

DROP TABLE IF EXISTS `Cassetta`;
CREATE TABLE `Cassetta` (
	`ID` INT NOT NULL auto_increment PRIMARY KEY,
	`Tipologia_Cassetta` VARCHAR(200) NOT NULL,
	`Num_Prodotti` VARCHAR(100),
	`Peso` VARCHAR(100),
	`Cliente` VARCHAR(100),
	`Prezzo` VARCHAR(50)
);

DROP TABLE IF EXISTS `Frutta_Verdura`; 
CREATE TABLE `Frutta_Verdura` (
	`ID` INT NOT NULL auto_increment PRIMARY KEY,
	`Tipologia_Prodotto` VARCHAR(100) NOT NULL,
	`Nome_Prodotto` VARCHAR(200) NOT NULL,
	`Prezzo` VARCHAR(50),
	`Unita` VARCHAR(10)
);

DROP TABLE IF EXISTS `Aggiunta_Cassetta`;
CREATE TABLE `Aggiunta_Cassetta` (
	`ID` INT NOT NULL auto_increment PRIMARY KEY,
	`Tipologia_Prodotto` VARCHAR(100) NOT NULL,
	`Nome_Prodotto` VARCHAR(200) NOT NULL,
	`Prezzo` VARCHAR(50),
	`Unita` VARCHAR(10),
	`Peso` VARCHAR(10),
	`Note` TEXT
);