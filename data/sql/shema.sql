CREATE TABLE admins (
id int PRIMARY KEY AUTO_INCREMENT,
username varchar(255) NOT NULL,
password varchar(255) NOT NULL,
CONSTRAINT unique_username UNIQUE (username)
);

CREATE TABLE medias (
id int PRIMARY KEY AUTO_INCREMENT,
titre varchar(255) NOT NULL,
type varchar(255) NOT NULL,
url varchar(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items(
id int PRIMARY KEY AUTO_INCREMENT,
titre varchar(255) NOT NULL,
ordre int NOT NULL
);

CREATE TABLE pages (
id int PRIMARY KEY AUTO_INCREMENT,
titre varchar(255) NOT NULL,
slug varchar(255) NOT NULL,
menu_id int NULL,
ordre int NOT NULL DEFAULT 0,
visible TINYINT(1) NOT NULL DEFAULT 1,
CONSTRAINT unique_slug_pages UNIQUE (slug),
CONSTRAINT fk_menu FOREIGN KEY (menu_id) REFERENCES menu_items(id) ON DELETE SET NULL -- Clé étrangère vers la table menu_items
);

CREATE TABLE blocs (
id int PRIMARY KEY AUTO_INCREMENT,
page_id int,
type varchar(50) NOT NULL,
donnees JSON NOT NULL,
ordre int NOT NULL,
CONSTRAINT fk_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE  -- Clé étrangère vers la table pages
);

