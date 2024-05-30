drop database if exists gakuDB;
create database if not exists gakuDB;
use gakuDB;
create user if not exists 'gaku_admin' IDENTIFIED BY 'stopl0okingatp4sswd!';

-- ne pas donner toutes ces perms, enfin pas à ce compte
grant select,update,insert,delete on gakuDB.* to 'gaku_admin';

-- Côté User
create table if not exists `Role` (
        `id` int not null auto_increment,
        `nom` varchar(20) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `User` (
        `id` int not null auto_increment,
        `prenom` varchar(15) null,
        `nom` varchar(15) null,
        `mail` varchar(50) not null,
        `passwdHash` varchar(255) not null,
        `idRole` int not null default 1,
        `phone` varchar(10) null,
        `dateNaissance` DateTime null,
        PRIMARY KEY (`id`),
        UNIQUE KEY `mail` (`mail`),
        FOREIGN KEY (`idRole`) REFERENCES `Role` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Token` (
        `tokenId` varchar(255) not null,
	`tokenHash` varchar(255) not null,
	`idUser` int not null,
	`dateExpiration` DateTime not null,
	primary key (`tokenId`),
	foreign key (`idUser`) references `User` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Côté musique et contenue
create table if not exists `Label` (
        `id` int not null auto_increment,
        `nom` varchar(70) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Artiste` (
        `id` int not null auto_increment,
        `nom` varchar(70) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Album` (
        `id` int  not null auto_increment,
        `nom` varchar(70) not null,
        `description` varchar(500) not null DEFAULT 'à définir',
        `lienXFD` varchar(100) not null default '???',
        `idLabel` int,
        `idArtiste` int,
        `prix` float not null default 999,
        `qte` int not null default 0,
        `uriImage` varchar(70) not null default 'no_image.jpg',
        `alerteSeuil` int not null default 5,
        `dateSortie` date not null default NOW(),
        PRIMARY KEY (`id`),
        FOREIGN KEY (`idLabel`) REFERENCES `Label` (`id`),
        FOREIGN KEY (`idArtiste`) REFERENCES `Artiste` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Noter` (
        `idAlbum` int not null,
        `idUser` int not null,
        `note` int not null,
        primary key (`idAlbum`,`idUser`),
        FOREIGN KEY(`idAlbum`) REFERENCES `Album`(`id`),
        FOREIGN KEY(`idUser`) REFERENCES `User`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Aimer` (
        `idAlbum` int not null,
        `idUser` int not null,
        primary key (`idAlbum`,`idUser`),
        FOREIGN KEY(`idAlbum`) REFERENCES `Album`(`id`),
        FOREIGN KEY(`idUser`) REFERENCES `User`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Event` (
        `id` varchar(5) not null,
        `nom` varchar(40) not null,
        `description` varchar(500) not null default 'C''est vide...',
        `lienLogo` varchar(100) not null default '??',
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Edition_Event`(
        `idEvent` varchar(5) not null,
        `numEdition` int not null,
        `annee` int not null,
        primary key(`idEvent`,`numEdition`),
        FOREIGN KEY (`idEvent`) REFERENCES `Event`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Provenir`(
        `idAlbum` int not null,
        `idEvent` varchar(5) not null,
        `numEdition` int not null,
        primary key (`idAlbum`,`idEvent`,`numEdition`),
        FOREIGN KEY (`idAlbum`) REFERENCES `Album`(`id`),
        FOREIGN KEY (`idEvent`,`numEdition`) REFERENCES `Edition_Event`(`idEvent`,`numEdition`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Song` (
        `id` int not null auto_increment,
        `nom` varchar(100) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Composer` (
        `idSong` int not null,
        `idArtiste` int not null,
        primary key (`idSong`,`idArtiste`),
        FOREIGN KEY (`idSong`) REFERENCES `Song`(`id`),
        FOREIGN KEY (`idArtiste`) REFERENCES `Artiste`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Contenir`(
        `idAlbum` int not null,
        `idSong` int not null,
        `positionOrdreAlbum` int,
        primary key (`idAlbum`,`idSong`),
        FOREIGN KEY (`idAlbum`) REFERENCES `Album` (`id`),
        FOREIGN KEY (`idSong`) REFERENCES `Song` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

-- Côté gestion e-commerce (Commande, produit...)

create table if not exists `Cart` (
        `idUser` int not null,
        `idAlbum` int not null,
        `qte` int not null,
        primary key (`idUser`,`idAlbum`),
        FOREIGN KEY (`idUser`) REFERENCES `User`(`id`),
        FOREIGN KEY(`idAlbum`) REFERENCES `Album`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Commande` (
        `id` int not null auto_increment,
        `prenomDestinataire` varchar(50) not null default '???',
        `nomDestinataire`varchar(50) not null default '???',
        `dateHeure` datetime not null default NOW(),
        `adresseLivraison` varchar(50) not null,
        `complementAdresse` varchar(50) null,
        `cpLivraison` varchar(6) not null,
        `villeLivraison` varchar(50) not null,
        `numeroTel` varchar(15) not null,
        `mailContact` varchar(50) not null default '???',
        `idUser` int not null,
        `note` varchar(300) not null default 'Aucune',
        primary key(`id`),
        foreign key(`idUser`) REFERENCES `User`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Statut` (
        `id` int not null auto_increment,
        `libelle` varchar(30) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Commander` (
        `idCommande` int not null,
        `idAlbum` int not null,
        `qte` int not null,
        primary key (`idCommande`,`idAlbum`),
        foreign key (`idCommande`) REFERENCES `Commande`(`id`),
        foreign key (`idAlbum`) REFERENCES `Album`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Avancer` (
        `idCommande` int not null,
        `idStatut` int not null,
        `dateStatut` DateTime not null,
        primary key (`idCommande`,`idStatut`),
        foreign key (`idCommande`) REFERENCES `Commande`(`id`),
        foreign key (`idStatut`) REFERENCES `Statut`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO Event(id,nom) values ('M3','Music Media-Mix Market'),('ZZISC','Divers');
INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3',44,2019),('ZZISC',0,0);



-- TRIGGER d'ajout de commandes
DELIMITER //

drop trigger if exists before_insert_cart//
CREATE TRIGGER before_insert_cart BEFORE INSERT
ON Cart FOR EACH ROW
BEGIN
        DECLARE qteActuelProduit int;
        SELECT qte INTO qteActuelProduit FROM Album WHERE id = NEW.idAlbum;
        IF (qteActuelProduit < NEW.qte) THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La quantité ajouté au panier est supérieur au stock disponible de l''album';
        END IF;

END//

drop trigger if exists before_update_cart//
CREATE TRIGGER before_update_cart BEFORE UPDATE
ON Cart FOR EACH ROW
BEGIN
        DECLARE qteActuelProduit int;
        SELECT qte INTO qteActuelProduit FROM Album WHERE id = NEW.idAlbum;
        IF (qteActuelProduit < NEW.qte) THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La quantité ajouté au panier est supérieur au stock disponible de l''album';
        END IF;

END//


CREATE TRIGGER before_insert_commander BEFORE INSERT
ON Commander FOR EACH ROW
BEGIN
        DECLARE qteActuelProduit int;
        SELECT qte INTO qteActuelProduit FROM Album WHERE id = NEW.idAlbum;
        IF (qteActuelProduit > NEW.qte) THEN
                UPDATE Album
                SET qte = qte - new.qte
                WHERE id = new.idAlbum;
        ELSE 
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La quantité commandé est supérieur au stock disponible de l''album';
        END IF;
END//

CREATE TRIGGER after_insert_commande AFTER INSERT
ON COMMANDE FOR EACH ROW
BEGIN
        INSERT into Avancer(idCommande,idStatut,dateStatut) values (NEW.id,0,NOW());
END//
CREATE TRIGGER before_insert_avancer BEFORE INSERT
ON Avancer FOR EACH ROW
BEGIN
        DECLARE DernierStatut int;
        SELECT IFNULL((SELECT MAX(idStatut) FROM Avancer WHERE idCommande = NEW.idCommande),0) + 1 INTO DernierStatut;
        IF (DernierStatut < 6) THEN
                SET NEW.idStatut = (Select id from statut where id = DernierStatut); 
        ELSE
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le dernier statut a déjà été attribué, il n''est pas possible d''en ajouter.';
                END IF;
END//
-- Fonctions d'ajout d'albums

drop function if exists estDejaPresent//
CREATE FUNCTION if not exists estDejaPresent(nomArtisteOuLabel varchar(70),estArtiste bit)
RETURNS INT
BEGIN
        DECLARE nbPresent INT;
        IF (estArtiste = 0) THEN
                SELECT Count(id) INTO nbPresent from Label where nom = nomArtisteOuLabel;
        ELSE
                SELECT Count(id) INTO nbPresent from Artiste where nom = nomArtisteOuLabel;
        END IF;
        RETURN nbPresent;
END //

drop function if exists CartPrice//
Create function if not exists CartPrice(idUser INT)
RETURNS FLOAT
BEGIN
        DECLARE prixTotal FLOAT;
        SELECT SUM(Cart.qte*album.prix) INTO prixTotal 
        FROM Cart
                JOIN Album ON idAlbum = Album.id
        WHERE idUser = idUser;
        RETURN prixTotal;
END //
grant execute on function gakudb.CartPrice to 'gaku_admin'@'%'//

drop procedure if exists addItemToCart//
CREATE PROCEDURE IF NOT EXISTS addItemToCart(idUserCart INT, idItem INT, qteItem INT)
BEGIN
        IF(EXISTS (SELECT * FROM Cart WHERE idUser = idUserCart AND idAlbum = idItem)) THEN
                UPDATE Cart SET qte = qteItem WHERE idUser = idUserCart AND idAlbum = idItem;
        ELSE
                INSERT INTO Cart(idUser,idAlbum,qte) VALUES(idUserCart,idItem,qteItem);
        END IF;
END //
grant execute on procedure gakudb.addItemToCart to 'gaku_admin'@'%'//

-- UNIQUEMENT POUR LE DEVELOPPEMENT, NE PAS GARDER EN PRODUCTION
drop procedure if exists grantAdminRole//
CREATE PROCEDURE IF NOT EXISTS grantAdminRole(idUserAdmin INT)
BEGIN
        UPDATE User SET idRole = 999 WHERE id = idUserAdmin;
END //
grant execute on procedure gakudb.grantAdminRole to 'gaku_admin'@'%'//

delimiter //
use gakudb //
drop procedure if exists CartToCommande//
CREATE PROCEDURE CartToCommande(idUserCommande INT, prenomDest VARCHAR(50), nomDest VARCHAR(50), adresseLivr VARCHAR(50), complementAdresseLivr VARCHAR(50), cpLivr VARCHAR(6), villeLivr VARCHAR(50), numTel VARCHAR(15), mailContactDest VARCHAR(50))
BEGIN
        DECLARE idAlbumCommandee INT;
        DECLARE qteCommandee INT;
        DECLARE fini INT DEFAULT FALSE;
        DECLARE cursCart CURSOR
                FOR Select idAlbum, qte FROM Cart WHERE idUser = idUserCommande;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET fini = TRUE;
        OPEN cursCart;
        -- la doc qui documente https://dev.mysql.com/doc/refman/8.0/en/cursors.html parce que le while ça fait un petit tour en plus !!!
        check_loop: LOOP
                FETCH cursCart INTO idAlbumCommandee, qteCommandee;
                IF fini THEN
                        LEAVE check_loop;
                END IF;
                IF(qteCommandee > (SELECT GetAlbumStock(idAlbumCommandee))) THEN
                        SIGNAL SQLSTATE '45003' SET MESSAGE_TEXT = 'La quantité commandée est supérieure au stock disponible';
                END IF;
        END LOOP check_loop;
        CLOSE cursCart;
        INSERT INTO Commande(prenomDestinataire,nomDestinataire,adresseLivraison,complementAdresse,cpLivraison,villeLivraison,numeroTel,mailContact,idUser) VALUES (prenomDest,nomDest,adresseLivr,complementAdresseLivr,cpLivr,villeLivr,numTel,mailContactDest,idUserCommande);
        INSERT INTO Commander(idCommande,idAlbum,qte) SELECT (select LAST_INSERT_ID()),idAlbum,qte FROM Cart WHERE idUser = idUserCommande;
        DELETE FROM Cart WHERE idUser = idUserCommande;
        SELECT LAST_INSERT_ID() as 'idCommande';
END //
grant execute on procedure gakudb.CartToCommande to 'gaku_admin'@'%'//

CREATE FUNCTION GetAlbumStock(idAlbumStock INT)
RETURNS INT
BEGIN
        DECLARE Stock INT;
        SELECT qte - ifnull((SELECT sum(qte) from Commander where idAlbum = idAlbumStock),0) INTO Stock FROM Album WHERE id = idAlbumStock;
        RETURN Stock;
END //
grant execute on function gakudb.GetAlbumStock to 'gaku_admin'@'%'//

drop function if exists prixCommande//
create function prixCommande(idCommande INT)
RETURNS FLOAT
BEGIN
        DECLARE prix FLOAT;
        SELECT SUM(Album.prix*Commander.qte) INTO prix
        FROM Commander
                JOIN Album ON Commander.idAlbum = Album.id
        WHERE Commander.idCommande = idCommande;
        RETURN prix;
END //
grant execute on function gakudb.prixCommande to 'gaku_admin'@'%'//

drop procedure if exists addAlbum//
CREATE PROCEDURE if not exists addAlbum(nomAlbum varchar(70), nomArtisteOuLabel varchar(70), estArtiste bit, event varchar(5),edition int,qteAlbum int,prixAlbum float, uriImageAlbum varchar(70), descriptionAlbum varchar(500), lienXFDAlbum varchar(100), dateSortieAlbum date) 
BEGIN
        DECLARE estPresent int;
        SELECT estDejaPresent(nomArtisteOuLabel,estArtiste) INTO estPresent;
        IF (estArtiste = 0) THEN
                IF (estPresent = 0) THEN
                        INSERT INTO Label(nom) values (nomArtisteOuLabel);
                END IF;
                INSERT INTO Album(nom,idLabel,qte,prix,uriImage,description,lienXFD,dateSortie) values (nomAlbum,(select id from Label where nom = nomArtisteOuLabel),qteAlbum,prixAlbum,uriImageAlbum,descriptionAlbum,lienXFDAlbum,dateSortieAlbum);
        ELSE
                IF (estPresent = 0) THEN
                        INSERT INTO Artiste(nom) values (nomArtisteOuLabel);
                END IF;
                INSERT INTO Album(nom,idArtiste,qte,prix,uriImage,description,lienXFD,dateSortie) values (nomAlbum,(select id from Artiste where nom = nomArtisteOuLabel),qteAlbum,prixAlbum,uriImageAlbum,descriptionAlbum,lienXFDAlbum,dateSortieAlbum);
        END IF;
        INSERT INTO Provenir(idAlbum,idEvent,numEdition) values ((select LAST_INSERT_ID()),event,edition);
END //

drop procedure if exists addSongInAlbum//
CREATE PROCEDURE if not exists addSongInAlbum(nomSong varchar(100), nomArtiste varchar(70), nomAlbumAjout varchar(70))
BEGIN
DECLARE idAlbumAjout, idSongAjout int;
SELECT id INTO idAlbumAjout from Album where nom = nomAlbumAjout;
IF (not exists(select * from Artiste where nom = nomArtiste)) THEN
        INSERT INTO ARTISTE(nom) VALUES(nomArtiste);
END IF;
INSERT INTO Song(nom) Values (nomSong);
SELECT max(id) INTO idSongAjout FROM Song;
INSERT INTO Composer(idSong,idArtiste) values(idSongAjout,(select id from Artiste where nom = nomArtiste));
INSERT INTO Contenir(idAlbum,idSong,positionOrdreAlbum) values (idAlbumAjout,idSongAjout,(select ifnull((select max(positionOrdreAlbum) from (select  positionOrdreAlbum,idAlbum from Contenir where idAlbum = idAlbumAjout)as maxPositionAlbum),0))+1);
END //

DELIMITER ;

call addAlbum('Dreams','Gabor Szabo',1,'ZZISC',0,50,15.99,'DREAMS.jpg','Dreams is an album by Hungarian guitarist Gábor Szabó featuring performances recorded in 1968 and released on the Skye label.','https://www.youtube.com/watch?v=gr0XWmEbiMQ','1968-01-01');
call addSongInAlbum('Galatea''s Guitar', 'Gabor Szabo','Dreams');
call addSongInAlbum('Half The Dayt Is Night','Gabor Szabo','Dreams');
call addSongInAlbum('Song Of The Injured Love','Gabor Szabo','Dreams');
call addSongInAlbum('The Fortune Teller', 'Gabor Szabo','Dreams');
call addSongInAlbum('Fire Dance', 'Gabor Szabo','Dreams');
call addSongInAlbum('The Lady In The Moon (From Kodaly)', 'Gabor Szabo','Dreams');
call addSongInAlbum('Ferris Wheel', 'Gabor Szabo','Dreams');
INSERT INTO Event(id,nom) values ('C','Comiket');
INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('C',103,2023);
call addAlbum('AD:PIANO VIVACE 2','Diverse System',0,'C',103,20,10,'ADPIANOVIVACE2.jpg','AD:PIANO VIVACE 2 is a piano album by Diverse System.','https://www.youtube.com/watch?v=oMQtmaBImBE','2023-12-30');
call addSongInAlbum('Reverie','Gardens feat. xia','AD:PIANO VIVACE 2');
call addSongInAlbum('ViViD Delusion','KARUT','AD:PIANO VIVACE 2');
call addSongInAlbum('Flying Emotion', 'Blacky','AD:PIANO VIVACE 2');
call addSongInAlbum('Untheory','Avans','AD:PIANO VIVACE 2');
call addSongInAlbum('PANORAMA','muyu','AD:PIANO VIVACE 2');
call addSongInAlbum('Amore Appassionato','seatrus','AD:PIANO VIVACE 2');
call addSongInAlbum('Reincarnation','Essbee','AD:PIANO VIVACE 2');
call addSongInAlbum('Pleiadescent','VeetaCrush','AD:PIANO VIVACE 2');
call addSongInAlbum('キャロルの瓦解','＊-Teris.','AD:PIANO VIVACE 2');
call addSongInAlbum('Pastel Express','Cynax','AD:PIANO VIVACE 2');
call addSongInAlbum('secretspeel','tn-shi','AD:PIANO VIVACE 2');
call addSongInAlbum('Flows With Errors','ARForest','AD:PIANO VIVACE 2');
call addSongInAlbum('Phantoflux','xi','AD:PIANO VIVACE 2');
call addSongInAlbum('Sempre.Vivacissimo','Polymath9','AD:PIANO VIVACE 2');
call addSongInAlbum('Ce vios','Sobrem','AD:PIANO VIVACE 2');
call addSongInAlbum('傀儡のためのコンチェルト','のとを','AD:PIANO VIVACE 2');
call addSongInAlbum('Ethereal Lullaby','yuichi NAGAO','AD:PIANO VIVACE 2');
call addSongInAlbum('躍動','Error Signal','AD:PIANO VIVACE 2');
call addSongInAlbum('Beyond Your Words','Sad Keyboard Guy feat. vally.exe','AD:PIANO VIVACE 2');
call addSongInAlbum('Meow Meow','Sazukyo','AD:PIANO VIVACE 2');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3',52,2023);

call addAlbum('20','HARDCORE TANO*C',0,'M3',52,30,15.99,'20.jpg','20 is a hardcore album by HARDCORE TANO*C.','https://www.youtube.com/watch?v=AsBGoWaWG5s','2023-10-11');
call addSongInAlbum('Our Memories (feat. 小岩井ことり)','REDALiCE & kors k','20');
call addSongInAlbum('YOLO','P*Light & YUC''e','20');
call addSongInAlbum('Dream Away (feat. Yukacco)','DJ Noriken & DJ Genki','20');
call addSongInAlbum('Beatboxer VS Trackmaker (feat. KAJI & Kohey)','t+pazolite','20');
call addSongInAlbum('ブルーモーメント (feat. 松永依織)','Getty','20');
call addSongInAlbum('FACE (feat. 山手響子, CV: 愛美)','Laur','20');
call addSongInAlbum('カラフルビート (feat. ユメ, CV: 小田果林 & ユウ, CV: 高槻みゆう)','Srav3R & DJ Myosuke','20');
call addSongInAlbum('Trust (feat. 光吉猛修)','Massive New Krew','20');
call addSongInAlbum('WACCA ULTRA DREAM MEGAMIX','USAO & Kobaryo','20');
call addSongInAlbum('SATELLITE','siqlo','20');
call addSongInAlbum('B.O.S.S','RiraN','20');
call addSongInAlbum('Shall we dance hardcore? (feat. 棗いつき)','RoughSketch','20');
call addSongInAlbum('Garden of Eden (feat. Kanae Asaba)','aran','20');

call addAlbum('XII - The Devourer of Gods -','Mensis IV Aria Reliquiae',1,'C',103,30,10.99,'THEDEVOUREROFGODS.jpg','XII - The Devourer of Gods - is an album by Mensis IV Aria Reliquiae.','https://www.youtube.com/watch?v=jEGt_zhil4s','2023-12-30');
call addSongInAlbum('Nightingale','Vocals:Eili','XII - The Devourer of Gods -');
call addSongInAlbum('Vanitas','Vocals:Eili','XII - The Devourer of Gods -');
call addSongInAlbum('Black Swan','Vocals:Eili','XII - The Devourer of Gods -');
call addSongInAlbum('Oblivion','Vocals:Eili','XII - The Devourer of Gods -');
call addSongInAlbum('Schadenfreude','Vocals:はらもりよしな','XII - The Devourer of Gods -');
call addSongInAlbum('Afterglow','Vocals:AKA','XII - The Devourer of Gods -');


INSERT INTO Event(id,nom) values ('REI','Reitaisai');
INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('REI',8,2021);
call addAlbum('e^(x+i)<3u',".new label",0,'REI',8,50,8.99,'eLUVu.jpg','e^(x+i)<3u is an album by .new label.','https://www.youtube.com/watch?v=5-kHtw764OE','2021-10-16');
call addSongInAlbum('into the EXTRA / 魔法少女達の百年祭','as key_','e^(x+i)<3u');
call addSongInAlbum('bouchonne','as key_','e^(x+i)<3u');
call addSongInAlbum('Eat up my HEART???','as key_','e^(x+i)<3u');
call addSongInAlbum('Good-bye Suicide','as key_','e^(x+i)<3u');
call addSongInAlbum('Implicature','as key_','e^(x+i)<3u');
call addAlbum('パラフォビア','lapix',1,'C','103',50,5.99,'PARAFOBIA.jpg','Paraphobia feat. 藍月なくる est un morceau de High-Tech Trance caractérisé par un rythme effréné, un chant puissant et des sons de synthétiseur énergiques.','https://www.youtube.com/watch?v=vzowc7DhDu8','2024-01-03');
call addSongInAlbum('パラフォビア (feat. 藍月なくる)','lapix','パラフォビア');
call addSongInAlbum('ルナティッククレイジ (feat. PANXI)','lapix ','パラフォビア');
call addSongInAlbum('サイレン (feat. 奈良ひより)','lapix','パラフォビア');
call addSongInAlbum('コンティニュー! (feat. 藍月なくる)','lapix','パラフォビア');
call addSongInAlbum('八月の風が吹く頃 (feat. 棗いつき)','lapix','パラフォビア');
call addSongInAlbum('宇宙遊泳 (feat. 中村さんそ)','lapix','パラフォビア');
call addSongInAlbum('Free Myself (feat. mami)','lapix','パラフォビア');
call addSongInAlbum('メリーバッド乙女 (feat. PANXI)','lapix','パラフォビア');
call addSongInAlbum('ドラスティックジェネレイト (feat. mami)','lapix','パラフォビア');

call addAlbum('SPD GAR 003','MEGAREX',0,'ZZISC',0,8839,10.99,'SPDGAR03.jpg','SPD GAR 003 is a compilation album by MEGAREX.','https://www.youtube.com/watch?v=iayaAxMdX40','2020-04-28');

call addSongInAlbum('Sunday Night (feat. Kanata.N)','Mameyudoufu','SPD GAR 003');
call addSongInAlbum('Broken Light (feat. mami)','poplavor','SPD GAR 003');
call addSongInAlbum('Continue (feat. Megumi Takahashi)','tokiwa','SPD GAR 003');
call addSongInAlbum('On The West Coastline (feat. Punipuni Denki)','Dirty Androids','SPD GAR 003');
call addSongInAlbum('Gone Days (feat. Ranasol)','Nhato','SPD GAR 003');
call addSongInAlbum('Melting (feat. Nicole Curry)','TEMPLINE','SPD GAR 003');
call addSongInAlbum('Feedback','Applekid','SPD GAR 003');
call addSongInAlbum('Signal (feat. shully)','Tsubusare BOZZ','SPD GAR 003');
call addSongInAlbum('Point of No Return (feat. Shizuki)','lapix','SPD GAR 003');
call addSongInAlbum('ideal you (feat. Risa Yuzuki)','sky_delta','SPD GAR 003');
call addSongInAlbum('Galaxy Vacation (feat. Punipuni Denki)','KOTONOHOUSE','SPD GAR 003');
call addSongInAlbum('Square Connection (feat. Such)','LADY''S ONLY','SPD GAR 003');
call addSongInAlbum('Sprout (feat. shully)','colate','SPD GAR 003');
call addSongInAlbum('Open Your Heart (feat. Renko)','rejection','SPD GAR 003');
call addSongInAlbum('illumination (feat. Yukaco)','hyleo','SPD GAR 003');

call addAlbum('Jive Round 3','zensen',1,'M3',52,533,9.99,'JIVEROUND3.jpg','Jive Round 3 is an album by zensen.','https://www.youtube.com/watch?v=6mB1Y1-z6Os','2023-10-11');
call addSongInAlbum('Double-Sided-Party(Club Edit)','zensen','Jive Round 3');
call addSongInAlbum('Jackpot Overdose','zensen','Jive Round 3');
call addSongInAlbum('Swinging All Thieves','zensen','Jive Round 3');
call addSongInAlbum('Feel My Secret','zensen','Jive Round 3');
call addSongInAlbum('Billiards Jockey (Club Edit)','zensen','Jive Round 3');
call addSongInAlbum('Jack Daniel','zensen','Jive Round 3');
call addSongInAlbum('Jeez Selecta','zensen','Jive Round 3');
call addSongInAlbum('Glitch Cocktail','zensen','Jive Round 3');
call addSongInAlbum('Dancing with Congress','zensen','Jive Round 3');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3',50,2020);
call addAlbum('PARADØXY','BlackY feat. Risa Yuzuki',1,'M3',50,13,8.39,'PARADOXY.jpg','PARADØXY is an album by BlackY feat. Risa Yuzuki.','https://www.youtube.com/watch?v=B_B3DktCH-s','2022-10-30');
-- Ajout des chansons de l'album "PARADØXY" avec l'artiste "BlackY feat. Risa Yuzuki"
CALL addSongInAlbum('PARADØXY', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('UNLEASHED', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('Starry Colors (Long ver.)', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('melty light', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('正しさに道連れ', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('標本', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('PARADØXY - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('UNLEASHED - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('Starry Colors - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('melty light - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('正しさに道連れ - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addSongInAlbum('標本 - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('C',102,2023);
call addAlbum('Flying Method','lapix',1,'C',102,10,10.10,'FLYINGMETHOD.jpg','Flying Method is an album by lapix.','https://www.youtube.com/watch?v=F-310GWdIYs','2023-08-13');
-- Ajout des chansons de l'album "Flying Method" avec l'artiste "lapix"
CALL addSongInAlbum('Our Love (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Primitive Vibes (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Flying Castle (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('ハイテックトキオ (Extended Mix) 【lapix ∞ BEMANI Sound Team "Sota Fujimori"】', 'lapix', 'Flying Method');
CALL addSongInAlbum('& Intelligence (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Double Dribble (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('glacia [STARDOM Remix] (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Rosa azuL (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('7 to Smoke (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Glitch Angel (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Ocean Blue feat. Luschel (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Volcanos (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Crumble Soul (Extended Mix)', 'lapix', 'Flying Method');
CALL addSongInAlbum('Foolish Again (Extended Mix)', 'lapix', 'Flying Method');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3','51',2023);
call addAlbum('Beyond CORE EVANGELIX 03','MEGAREX',0,'M3',51,30,10,'BEYONDCOREEVANGELIX03.jpg','Beyond CORE EVANGELIX 03 is a compilation album by MEGAREX.','https://www.youtube.com/watch?v=1aeiJNOkh-Y','2023-04-30');
-- Ajout des chansons de l'album "Beyond CORE EVANGELIX 03"
CALL addSongInAlbum('Rapid', 'Mylta', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Laser Tag', 'Mameyudoufu', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Answer', 'VOLTA', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('良いお菓子と悪いお菓子 (ft. L4hee)', 'Asatsumei', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('LUNARSCAPE', 'RYOQUCHA', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Follow_Me', 'Assertive', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Tonight', 'ZoeEngine', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Random Encounter', 'KARUT', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Take a Shot', 'Riku', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Vain', 'Aethral', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Ripple Effects', 'litmus*', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('flo-lo', 'Titancube', 'Beyond CORE EVANGELIX 03');
CALL addSongInAlbum('Hypernova', 'rejection', 'Beyond CORE EVANGELIX 03');

call addAlbum('Moment.','MOTTO MUSIC',0,'M3',52,30,2.99,'MOMENT.jpg','Moment. is an album by MOTTO MUSIC.','https://www.youtube.com/watch?v=gMmNOH09oi0','2023-10-12');
-- Ajout des chansons de l'album "Moment."
CALL addSongInAlbum('Glittering Sky (feat.Marpril)', 'picco', 'Moment.');
CALL addSongInAlbum('empty (feat.KMNZ LITA)', 'tokiwa', 'Moment.');
CALL addSongInAlbum('Ray of Lie (feat.Such)', 'DÉ DÉ MOUSE & Kakeru', 'Moment.');
CALL addSongInAlbum('キュー (feat.メトロミュー)', 'KAIRUI', 'Moment.');
CALL addSongInAlbum('Galactic Gourmet (feat.くいしんぼあかちゃん)', 'KOTONOHOUSE', 'Moment.');
CALL addSongInAlbum('クソデカマジカルマジックマッシュルーム (feat.ちょこ)', 'なみぐる', 'Moment.');
CALL addSongInAlbum('プリズムバード (feat.Risa Yuzuki)', 'CHOUX', 'Moment.');
CALL addSongInAlbum('オテンキグラビティ (feat.雨宮みやび)', 'Ray_Oh', 'Moment.');
CALL addSongInAlbum('Floating Summer (feat.夢乃ゆき)', 'yoswu', 'Moment.');

call addAlbum('NX ENCHANT 02','NEXTLIGHT',0,'ZZISC',0,39,39.39,'NXENCHANT02.jpg','NX ENCHANT 02 is an album by NEXTLIGHT.','https://www.youtube.com/watch?v=9OAuMw38IHY','2023-10-29');
-- Ajout des chansons de l'album "NX ENCHANT 02"
CALL addSongInAlbum('メジルシ', 'Nor', 'NX ENCHANT 02');
CALL addSongInAlbum('Storia', 'Reno', 'NX ENCHANT 02');
CALL addSongInAlbum('タイニーキャット', 'picco', 'NX ENCHANT 02');
CALL addSongInAlbum('Take Me', 'Lunabitt', 'NX ENCHANT 02');
CALL addSongInAlbum('Da/ys*', 'purini', 'NX ENCHANT 02');
CALL addSongInAlbum('Scramberry!!', 'Capchii', 'NX ENCHANT 02');
CALL addSongInAlbum('Miraizu', '瀬名', 'NX ENCHANT 02');
CALL addSongInAlbum('月歩き', 'DoubleLift', 'NX ENCHANT 02');
CALL addSongInAlbum('Black Swan Theory', 'Reno, Hylen', 'NX ENCHANT 02');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3','49',2022);
call addAlbum('NX ENCHANT','NEXTLIGHT',0,'M3',49,110,3.99,'NXENCHANT.jpg','NX ENCHANT is an album by NEXTLIGHT.','https://www.youtube.com/watch?v=yUcUZUdsbpE','2022-04-17');
-- Ajout des chansons de l'album "NX ENCHANT"
CALL addSongInAlbum('Sweet Trick', 'picco', 'NX ENCHANT');
CALL addSongInAlbum('Overrun', 'Twinfield', 'NX ENCHANT');
CALL addSongInAlbum('Lullaby', 'ckwa', 'NX ENCHANT');
CALL addSongInAlbum('Magic Theory', 'Reno', 'NX ENCHANT');
CALL addSongInAlbum('inner', 'Mysteka', 'NX ENCHANT');
CALL addSongInAlbum('いいわけクレセント', 'picco', 'NX ENCHANT');
CALL addSongInAlbum('Enough', 'Mwk', 'NX ENCHANT');
CALL addSongInAlbum('New Normal', 'tekalu', 'NX ENCHANT');
CALL addSongInAlbum('It''s getting warmer day', 'DenDora, picco', 'NX ENCHANT');
CALL addSongInAlbum('Secret Crush', 'HALA1004', 'NX ENCHANT');

call addAlbum('The Umbra','ARForest',1,'M3',51,40,99.99,'THEUMBRA.jpg','The Umbra is an album by ARForest.','https://www.youtube.com/watch?v=OnJuGTW9wkY','2023-04-21');
-- Ajout des chansons de l'album "The Umbra", il y a normalement deux disques dans l'album mais pas nécessaire à gérer pour ce contexte.
CALL addSongInAlbum('The Umbra (feat.Sennzai)', 'ARForest', 'The Umbra');
CALL addSongInAlbum('Paradox', 'Maozon', 'The Umbra');
CALL addSongInAlbum('EZ-COM3-EZ-G0', 'KARUT', 'The Umbra');
CALL addSongInAlbum('Combat', 'EmoCosine', 'The Umbra');
CALL addSongInAlbum('Jotunheim', 'Nhato', 'The Umbra');
CALL addSongInAlbum('RIOT', 'KO3', 'The Umbra');
CALL addSongInAlbum('Flashout', 'Junk', 'The Umbra');
CALL addSongInAlbum('stupor', 'awfuless', 'The Umbra');
CALL addSongInAlbum('Highly Spiral', 'BlackY', 'The Umbra');
CALL addSongInAlbum('Mandragora', 'Zekk', 'The Umbra');
CALL addSongInAlbum('Emerald Green', 'Blacklolita', 'The Umbra');
CALL addSongInAlbum('Moon', 'Juggernaut.', 'The Umbra');
CALL addSongInAlbum('Đ', 'Hylen', 'The Umbra');
CALL addSongInAlbum('Connect to Relic', '黒魔', 'The Umbra');
CALL addSongInAlbum('Motherboard', 'Paul Bazooka', 'The Umbra');
CALL addSongInAlbum('⁄⁄ alumina_', 'Nor', 'The Umbra');
CALL addSongInAlbum('Fabricated Personality', 'Mameyudoufu', 'The Umbra');
CALL addSongInAlbum('Natur[Z]eit', 'Street', 'The Umbra');
CALL addSongInAlbum('One More Night', 'RiraN', 'The Umbra');
CALL addSongInAlbum('solarblade', 'BilliumMoto', 'The Umbra');
CALL addSongInAlbum('R.T.S', 'Soochan', 'The Umbra');
CALL addSongInAlbum('New Horizon', 'Tatsunoshin', 'The Umbra');
CALL addSongInAlbum('Think About You', 'Hommarju', 'The Umbra');
CALL addSongInAlbum('Dot Karma', 'Tanchiky', 'The Umbra');
CALL addSongInAlbum('Church_Emulator', 'Team Grimoire', 'The Umbra');
CALL addSongInAlbum('Reborn Again', 'YUKIYANAGI', 'The Umbra');
CALL addSongInAlbum('Escape a Cyber City', 'Laur', 'The Umbra');
CALL addSongInAlbum('Amnesia', 'DJ Noriken', 'The Umbra');

call addAlbum('ARTIFACTS：ZERØ','Connexio',1,'M3',50,107,0.99,'ARTIFACTSZERO.jpg','ARTIFACTS：ZERØ is an album by Connexio.','https://www.youtube.com/watch?v=pJnzoz1cn-g','2022-10-20');
-- Ajout des chansons de l'album "ARTIFACTS：ZERØ"
CALL addSongInAlbum('Arte Factum', 'Connexio', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Cellnix', 'MoAE:. feat.可不', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Swarm of Outdoor Units', 'RYOQUCHA', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Artemizija Transfiguration', 'CS4W', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Electra', 'KONPEKi', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Disobedience', '現役JK', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('&#0;', 'ADA', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('eclipsization', 'ぴれんどらー', 'ARTIFACTS：ZERØ');
CALL addSongInAlbum('Missing Planet', '黒魔', 'ARTIFACTS：ZERØ');

call addAlbum('PSYcoLogy','Osanzi',1,'ZZISC',0,150,5.99,'PSYCOLOGY.jpg','PSYcoLogy is an album by Osanzi.','https://www.youtube.com/watch?v=GVfPXzxVi_U','2021-12-27');
-- Ajout des chansons de l'album "PSYcoLogy" par Osanzi
CALL addSongInAlbum('白日夢', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('マニピュレート', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('イマジン', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('Summer Love (2021 mix)', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('In Your Eyes (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('True Color (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('Dance With Me (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addSongInAlbum('With U', 'Osanzi', 'PSYcoLogy');

INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('C',97,2019);
call addAlbum('リファクタリング・トラベル -Refactoring Travel-','t+pazolite',1,'C',97,3514,12.99,'REFACTORINGTRAVEL.jpg','リファクタリング・トラベル -Refactoring Travel- is an album by t+pazolite.','https://www.youtube.com/watch?v=NJG6XB_FVsU','2020-03-26');
-- Ajout des chansons de l'album "リファクタリング・トラベル -Refactoring Travel-" par t+pazolite
CALL addSongInAlbum('Intro - I''ll be waiting for you', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Dive High', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Refactoring Travel (feat. ななひら)', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('TOKONOMA Spacewalk (Uncut Edition) (c)Rayark / from Cytus II', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Fusion Cruise', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Duality Drive', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Waku ga Dokidoki', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('星屑ストラック (Uncut Edition) from 太鼓の達人', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('ブズーブズービチービチーブベベベベベピーゴゴゴゴゴ', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('What a Hyped Beautiful World', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('Good Night, Bad Luck (Uncut Edition) (c)TAITO / from Groove Coaster', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addSongInAlbum('星屑ストラック (かねこちはる Remix)', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');

call addAlbum('Meadowsweet','Sigillum Azoetia',0,'ZZISC',0,0,99,'MEADOWSWEET.jpg','Meadowsweet is an album by Sigillum Azoetia.','https://www.youtube.com/watch?v=F0uFQR036-0','2022-10-30');
-- Ajout des chansons de l'album "Meadowsweet" par GOETIΛ
CALL addSongInAlbum('Transient Epileptic Amnesia', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Blodeuwedd', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Scroll of Adepha', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Sacrificial Doll', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Meadowsweet', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('The Destroyer', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('SERA 2405', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Rusticate', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Maidwell', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Beltshera''s Record', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Carmine', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('A3XECR', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Queen Carrier', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('The Mechanical Eye', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Inverse Electron Demand Diels-Alder', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Pennyroyal', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Hamartia', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Pillars of Enoch', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Kolbrin', 'GOETIΛ', 'Meadowsweet');
CALL addSongInAlbum('Nefatari''s Lament', 'GOETIΛ', 'Meadowsweet');

call addAlbum('Vintage Emotion 2','Login Records',0,'C',103,1980,19.98,'VINTAGEEMOTION2.jpg','Vintage Emotion 2 is an album by Login Records.','https://www.youtube.com/watch?v=mDOrmEtPZ9E','2023-10-30');
-- Ajout des chansons de l'album "Vintage Emotion 2"
CALL addSongInAlbum('Rainy Tarte', 'you', 'Vintage Emotion 2');
CALL addSongInAlbum('Crossing point', 'akatora', 'Vintage Emotion 2');
CALL addSongInAlbum('One Day', 'hu-zin', 'Vintage Emotion 2');
CALL addSongInAlbum('Midnight Poolside', 'Risen', 'Vintage Emotion 2');
CALL addSongInAlbum('Cybernetic Rock City', 'pocotan', 'Vintage Emotion 2');
CALL addSongInAlbum('NIGHT CITY', 'DJ AIR-G', 'Vintage Emotion 2');
CALL addSongInAlbum('Chicago Rain', 'DXST', 'Vintage Emotion 2');
CALL addSongInAlbum('Awake Reverie', 'YUKIYANAGI', 'Vintage Emotion 2');

call addAlbum('MEGATON KICK 5','HARDCORE TANO*C',0,'C','103',2024,9.99,'MEGATONKICK5.jpg','MEGATON KICK 5 is an album by HARDCORE TANO*C.','https://www.youtube.com/watch?v=LBM6B89d3SU','2023-12-15');
-- Ajout des chansons de l'album "MEGATON KICK 5"
CALL addSongInAlbum('Megaton Keeper', 'DJ Myosuke', 'MEGATON KICK 5');
CALL addSongInAlbum('Never Fall Apart', 'RiraN', 'MEGATON KICK 5');
CALL addSongInAlbum('Movin''', 'Massive New Krew', 'MEGATON KICK 5');
CALL addSongInAlbum('Flames of Fate', 'DJ Noriken', 'MEGATON KICK 5');
CALL addSongInAlbum('56 Seconds Later', 't+pazolite', 'MEGATON KICK 5');
CALL addSongInAlbum('Tokyo Midnight (feat. mami)', 'Yuta Imai', 'MEGATON KICK 5');
CALL addSongInAlbum('Hera', 'Gram', 'MEGATON KICK 5');
CALL addSongInAlbum('Announcement', 'RoughSketch', 'MEGATON KICK 5');
CALL addSongInAlbum('Louder', 'Laur', 'MEGATON KICK 5');
CALL addSongInAlbum('Cosmic Diffusion', 'Kobaryo', 'MEGATON KICK 5');

call addAlbum('SHIFT','KO3',1,'M3',50,4,9.30,'SHIFT.jpg','SHIFT is an album by KO3.','https://soundcloud.com/dj-ko3/ko3-3rd-album-shiftxfd','2022-10-30');
-- Ajout des chansons de l'album "SHIFT" par KO3
CALL addSongInAlbum('MAK3SHIFT', 'KO3', 'SHIFT');
CALL addSongInAlbum('Eeny,Meeny... (feat. 藤村鼓乃美）', 'KO3', 'SHIFT');
CALL addSongInAlbum('Not Alone (feat. MYLK)', 'KO3', 'SHIFT');
CALL addSongInAlbum('JVST RYDING', 'KO3', 'SHIFT');
CALL addSongInAlbum('GAME OVER', 'KO3', 'SHIFT');
CALL addSongInAlbum('FEEL', 'KO3', 'SHIFT');
CALL addSongInAlbum('Magnet (feat. Renko)', 'KO3', 'SHIFT');
CALL addSongInAlbum('I''ll be for you (feat. Kanae Asaba)', 'KO3', 'SHIFT');
CALL addSongInAlbum('I''ll be for you VIP', 'KO3', 'SHIFT');
CALL addSongInAlbum('Summery (Club Extended Mix)', 'KO3', 'SHIFT');
INSERT INTO Edition_Event(idEvent,numEdition,annee) values ('M3',53,2024);
call addAlbum('DUMMY DISC','t+pazolite',1,'M3',53,50,19.99,'DUMMY_DISC.jpg','DUMMY DISC est un album par t+pazolite pour C.H.S','https://www.youtube.com/watch?v=Hz13QgklIMk','2024-09-30');
-- Ajout des chansons de l'album "DUMMY DISC" par t+pazolite
CALL addSongInAlbum('It\'s a DUMMY', 't+pazolite', 'DUMMY DISC');
CALL addSongInAlbum('Makina 2022', 't+pazolite', 'DUMMY DISC');
CALL addSongInAlbum('Never Dr0p', 't+pazolite', 'DUMMY DISC');

call addAlbum('cottage','歩く人',1,'M3',53,50,10.99,'COTTAGE.jpg','cottage est un album par 歩く人, qui nous change de TOUT !','https://www.youtube.com/watch?v=2RP-GBq3H5I','2024-05-30');
CALL addSongInAlbum('部屋の窓から', '歩く人', 'cottage');
CALL addSongInAlbum('わたしの組成式', '歩く人', 'cottage');
CALL addSongInAlbum('in the gray', '歩く人', 'cottage');
CALL addSongInAlbum('CREATION', '歩く人', 'cottage');
CALL addSongInAlbum('メゾン', '歩く人', 'cottage');
CALL addSongInAlbum('SEPIA NOTES', '歩く人', 'cottage');
CALL addSongInAlbum('メイデイ', '歩く人', 'cottage');
CALL addSongInAlbum('ゴーストレストラン', '歩く人', 'cottage');
CALL addSongInAlbum('あれ？', '歩く人', 'cottage');
CALL addSongInAlbum('リフェクトリ', '歩く人', 'cottage');
CALL addSongInAlbum('トレンチ', '歩く人', 'cottage');
CALL addSongInAlbum('メトロタクシー', '歩く人', 'cottage');
CALL addSongInAlbum('天気雨の原理', '歩く人', 'cottage');

call addAlbum('いのぷれりゅうど','yuru',1,'M3',53,50,13.99,'INOPURERYUDO.jpg','Première album de YURU, いのぷれりゅうど est une aude au bonheur !','https://www.youtube.com/watch?v=2RP-GBq3H5I','2024-12-30');
-- Ajout des chansons de l'album "いのぷれりゅうど" par yuru
CALL addSongInAlbum('ファンファーレの鳴る空に', 'yuru', 'いのぷれりゅうど');
CALL addSongInAlbum('Delicious✩Journey(Solo ver.)', 'yuru', 'いのぷれりゅうど');
CALL addSongInAlbum('Magic Of Colors', 'yuru', 'いのぷれりゅうど');
CALL addSongInAlbum('プレリュード', 'yuru', 'いのぷれりゅうど');
call addAlbum('NONEXISTENT VITRUVIUS','E0ri4',1,'M3',53,50,44.44,'NONEXISTENT_VITRUVIUS.jpg','4ème album de E0ri4, NONEXISTENT VITRUVIUS fait preuve d\'une grande originalité et nous fait bondir de nos chaises !','https://www.youtube.com/watch?v=rBg0Rng2D54','2024-12-30');
-- Ajout des chansons de l'album "NONEXISTENT VITRUVIUS" par E0ri4
CALL addSongInAlbum('Unusualeisure', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addSongInAlbum('Bloodthirsty', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addSongInAlbum('Plot Type', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addSongInAlbum('Signature Analysis', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addSongInAlbum('My Origin', 'E0ri4', 'NONEXISTENT VITRUVIUS');

call addAlbum('Steppin\' for Summer','xenigata',1,'M3',53,50,99.99,'STEPPIN_FOR_SUMMER.jpg','Inconnu du bataillon, j\'ai vraiment rien à écrire là','https://www.youtube.com/watch?v=m4O4RBr_JNg','2024-12-30');
-- Ajout des chansons de l'album "Steppin' for Summer" par xenigata
CALL addSongInAlbum('夜と空 ft.雨汰。', 'xenigata', 'Steppin\' for Summer');
CALL addSongInAlbum('Summer Diary ft.記憶に残る', 'xenigata', 'Steppin\' for Summer');
CALL addSongInAlbum('君宛て ft.ことの', 'xenigata', 'Steppin\' for Summer');

call addAlbum('IRREGULAR NATION 10','HARDCORE TANO*C',0,'M3',53,50,10.01,'IRREGULAR_NATION_10.jpg','Les rois du monde ces types l\'album est fou mais pas nécessaire la couverture','https://www.youtube.com/watch?v=ojthAKDF_R4','2024-12-30');
-- Ajout des chansons de l'album "IRREGULAR NATION 10"
CALL addSongInAlbum('Need You', 'REDALiCE', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Recollection', 'Getty', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Calm & Passionate', 't+pazolite', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Groovy Bunny', 'Laur', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Whisper of Despair feat. 蛇塚透花', 'Kobaryo', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Moving On', 'USAO & Shandy Kubota', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Shadow Bang', 'DJ Myosuke', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Bar Do Thos Grol', 'Massive New Krew', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Casual Encounter', 'aran', 'IRREGULAR NATION 10');
CALL addSongInAlbum('Fake illness', 'Srav3R', 'IRREGULAR NATION 10');

call addAlbum('Trinity Force','Zekk',1,'M3',53,50,9.99,'TRINITY_FORCE.jpg','Trinity Force est un album par Zekk, l\'album du siècle selon le titre du XFD','https://www.youtube.com/watch?v=7GjohEBsfqQ','2025-01-01');
-- Ajout des chansons de l'album "Trinity Force" par Zekk
CALL addSongInAlbum('Trinity Force', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Swampgator [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Let Me Hear [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Haetae [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Falling Down feat. Renko × TRI△NGLE [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Count [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('D4NCE [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('SUMMER [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Foresight [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Duality Rave (Zekk\'s \'FULL SPEC\' Remix) [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Your voice so... feat. Such (Zekk\'s \'FULL SPEC\' Remix) [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addSongInAlbum('Astronomical Optical Interferometry [2019 Remaster]', 'Zekk', 'Trinity Force');

call addAlbum('#B​.​E​.​R​.​Radio','Blatantly Emotional Records',0,'M3',52,50,11.11,'BERRADIO.jpg','#B​.​E​.​R​.​Radio is an album by Blatantly Emotional Records.','https://www.youtube.com/watch?v=rcv3hmXt_Bc','2099-01-30');
-- Ajout des chansons de l'album "#B​.​E​.​R​.​Radio"
CALL addSongInAlbum('Always beside you', 'DenDora', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('PARTY TIME', 'joinT', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('Tape Stop', '書店太郎', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('Riverside on rainy weekend', 'joinT', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('EVER_BLUE（Over Dub）', '書店太郎', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('Sunset Serenade', 'ぼぉの', '#B​.​E​.​R​.​Radio');
CALL addSongInAlbum('Hello_Desktop', '書店太郎 & DenDora', '#B​.​E​.​R​.​Radio');

INSERT INTO Role(id,nom) values (999,'admin'),(1,'user');

INSERT INTO User(prenom,nom,mail,passwdHash,idRole) values ('Damien','R.','damien@damdam.fr','e',999),('Berre','Etang','Etang@berre.fr','',1),('Eva','Cuhassion','cuhassion.eva@genmarre.com','',1);

-- Insertion de l'album RUNABOUT (https://diverse.jp/dvsp-0229/), vieille méthode avant écriture des fonctions
INSERT INTO Artiste(nom) values('tanigon'),('void (Mournfinale)'),
                        ('Nago'),('Kenichi Chiba'),
                        ('filmiiz'),('Masayoshi Minoshima'),('Lawy'),
                        ('くるぶっこちゃん');

INSERT INTO Song(nom) values ('Storm Spirit'),('Wander Heaven'),('Petrolhead'),
                        ('Drive Out'),('#THE_DVRL_286'),('AM 4:53'),
                        ('Signals'),('Future Traffic'),('Wild Chaser'),
                        ('Orange Night'),('TECHNODRIVE'),('Far from here'),
                        ('Neon Breeze');

INSERT INTO Composer(idSong,idArtiste) values 
((select id from Song where nom = 'Storm Spirit'),(select id from Artiste where nom = 'tanigon')),
((select id from Song where nom = 'Wander Heaven'),(select id from Artiste where nom = 'BlackY')),
((select id from Song where nom = 'Petrolhead'),(select id from Artiste where nom = 'void (Mournfinale)')),
((select id from Song where nom = 'Drive Out'), (select id from Artiste where nom = 'ARForest')),
((select id from Song where nom = '#THE_DVRL_286'),(select id from Artiste where nom = 'DJ Noriken')),
((select id from Song where nom = 'AM 4:53'),(select id from Artiste where nom = 'lapix')),
((select id from Song where nom = 'Signals'),(select id from Artiste where nom = 'Maozon')),
((select id from Song where nom = 'Future Traffic'),(select id from Artiste where nom = 'Nago')),
((select id from Song where nom = 'Wild Chaser'),(select id from Artiste where nom = 'Kenichi Chiba')),
((select id from Song where nom = 'Orange Night'),(select id from Artiste where nom = 'filmiiz')),
((select id from Song where nom = 'TECHNODRIVE'),(select id from Artiste where nom = 'Masayoshi Minoshima')),
((select id from Song where nom = 'Far from Here'),(select id from Artiste where nom = 'Lawy')),
((select id from Song where nom = 'Neon Breeze'),(select id from Artiste where nom = 'くるぶっこちゃん'));

INSERT INTO Album(nom,idLabel,qte,prix,uriImage,lienXFD) values ('RUNABOUT',(select id from Label where nom = 'Diverse System'),100,9.99,'RUNABOUT.jpg','https://soundcloud.com/diversesystem/dvsp-0229-xfd');

INSERT INTO Contenir (idAlbum,idSong,positionOrdreAlbum) values
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Storm Spirit'),1),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Wander Heaven'),2),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Petrolhead'),3),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Drive Out'),4),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = '#THE_DVRL_286'),5),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'AM 4:53'),6),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Signals'),7),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Future Traffic'),8),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Wild Chaser'),9),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Orange Night'),10),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'TECHNODRIVE'),11),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Far from Here'),12),
((select id from Album where nom = 'RUNABOUT'),(select id from Song where nom = 'Neon Breeze'),13);

INSERT INTO PROVENIR(idAlbum,idEvent,numEdition) values ((select id from Album where nom = 'RUNABOUT'),'M3',44);

-- Insertion des commandes + test trigger

INSERT INTO STATUT(id,libelle) values (1,'Non validée'),(2,'Préparation'),(3,'Pris en charge'),(4,'en cours d''acheminement'),(5,'Livré');

INSERT INTO Commande(prenomDestinataire,nomDestinataire,dateHeure,adresseLivraison,cpLivraison,villeLivraison,numeroTel,idUser) values
('Damien','Rocher',NOW(),'30 Rue du Quelque','30000','Part','+06 51 23 45 67',(select id from User where nom = 'R.')),
('Damien','Rocher',ADDTIME(NOW(), "1000000"),'30 Rue du Quelque','30001','Part','+99 99 99 99 99',(select id from User where nom = 'R.')),
('Etang','Berre',ADDTIME(NOW(),"-1000000"),'10 Avenue de la loose','101010','Pas un winner','+01 10 20 32 12',(select id from User where nom='Etang')),
('Eva','Cuhassion',ADDTIME(NOW(),"200000000"),'999 UNKNOWN PLACE','99999','???','+33 06 33 40 33 23',(select id from User where nom='Cuhassion'));

INSERT INTO Commander(idCommande,idAlbum,qte) values ((select id from Commande where cpLivraison="30000"),1,3),((select id from Commande where cpLivraison="30001"),1,3),((select id from Commande where cpLivraison="30001"),2,1),((select id from Commande where cpLivraison="30001"),3,1)
                                                      ,((select id from Commande where cpLivraison="101010"),10,1),((select id from Commande where cpLivraison="99999"),13,1),((select id from Commande where cpLivraison="99999"),14,1);

update commande set note = 'Salut c''est gillou' where idUser = 3;

ALTER TABLE Statut ADD COLUMN `idSuivant` int;
ALTER TABLE STATUT ADD COLUMN `idPrecedent` int;

Alter TABLE STATUT ADD FOREIGN KEY (`idPrecedent`) REFERENCES STATUT(`id`);
ALTER TABLE STATUT ADD FOREIGN KEY (`idSuivant`) REFERENCES Statut(`id`);

create table if not exists `Employe` (
        `id` int not null auto_increment,
        `nom` varchar(30) not null,
        `prenom` varchar(30) not null,
        PRIMARY KEY (`id`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

alter table Commande add column `idResponsable` int;
alter table Commande add FOREIGN KEY (`idResponsable`) REFERENCES `Employe`(`id`);

update Statut set idSuivant = 2 where id = 1;
update Statut set idSuivant = 3, idPrecedent = 1 where id = 2;
update Statut set idSuivant = 4, idPrecedent = 2 where id = 3;
update Statut set idSuivant = 5, idPrecedent = 3 where id = 4;
update Statut set idPrecedent = 4 where id = 5;



update Commande set idResponsable = 1 where exists(select * from Avancer where idStatut = 3);

insert into employe(nom,prenom) values ('Gerard','Menvussat'),('Eva','Cuhassion'),('John','Doe');

