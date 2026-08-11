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
slug varchar(255) NOT NULL,
CONSTRAINT unique_slug_medias UNIQUE (slug) 
);

CREATE TABLE pages (
id int PRIMARY KEY AUTO_INCREMENT,
titre varchar(255) NOT NULL,
slug varchar(255) NOT NULL,
CONSTRAINT unique_slug_pages UNIQUE (slug)
);

CREATE TABLE blocs(
id int PRIMARY KEY AUTO_INCREMENT,
page_id int,
media_id int,
type varchar(255) NOT NULL,
contenu text NOT NULL,
legende varchar(255),
ordre int NOT NULL,
CONSTRAINT fk_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,  -- Clé étrangère vers la table pages
CONSTRAINT fk_media FOREIGN KEY (media_id) REFERENCES medias(id) ON DELETE SET NULL  -- Clé étrangère vers la table medias
);

CREATE TABLE menu_items(
id int PRIMARY KEY AUTO_INCREMENT,
titre varchar(255) NOT NULL,
slug varchar(255) NOT NULL,
ordre int NOT NULL,
page_id int,
CONSTRAINT fk_menu_item FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE, -- Clé étrangère vers la table pages
CONSTRAINT unique_slug_menu_items UNIQUE (slug) 
);
