drop database if exists gakuDB;
create database if not exists gakuDB;
use gakuDB;
create user if not exists 'gaku_view' IDENTIFIED BY 'stopl0okingatp4sswd!';

-- ne pas donner toutes ces perms, enfin pas à ce compte
grant select,update,insert,delete on gakuDB.* to 'gaku_view';

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
        `note` tinyint not null,
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

create table if not exists `Evenement` (
        `id` varchar(5) not null,
        `nom` varchar(40) not null,
        `description` varchar(500) not null default 'C''est vide...',
        `lienLogo` varchar(100) not null default '??',
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Edition_Evenement`(
        `idEvent` varchar(5) not null,
        `numEdition` int not null,
        `annee` int not null,
        primary key(`idEvent`,`numEdition`),
        FOREIGN KEY (`idEvent`) REFERENCES `Evenement`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Provenir`(
        `idAlbum` int not null,
        `idEvent` varchar(5) not null,
        `numEdition` int not null,
        primary key (`idAlbum`,`idEvent`,`numEdition`),
        FOREIGN KEY (`idAlbum`) REFERENCES `Album`(`id`),
        FOREIGN KEY (`idEvent`,`numEdition`) REFERENCES `Edition_Evenement`(`idEvent`,`numEdition`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Musique` (
        `id` int not null auto_increment,
        `nom` varchar(60) not null,
        PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Composer` (
        `idMusique` int not null,
        `idArtiste` int not null,
        primary key (`idMusique`,`idArtiste`),
        FOREIGN KEY (`idMusique`) REFERENCES `Musique`(`id`),
        FOREIGN KEY (`idArtiste`) REFERENCES `Artiste`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


create table if not exists `Contenir`(
        `idAlbum` int not null,
        `idMusique` int not null,
        `positionOrdreAlbum` int,
        primary key (`idAlbum`,`idMusique`),
        FOREIGN KEY (`idAlbum`) REFERENCES `Album` (`id`),
        FOREIGN KEY (`idMusique`) REFERENCES `Musique` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

-- Côté gestion e-commerce (Commande, produit...)

create table if not exists `Panier` (
        `idAlbum` int not null,
        `idFormat` int not null,
        `idUser` int not null,
        `qte` tinyint not null,
        primary key (`idAlbum`,`idFormat`,`idUser`),
        FOREIGN KEY(`idAlbum`) REFERENCES `Album`(`id`),
        FOREIGN KEY(`idUser`) REFERENCES `User`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

create table if not exists `Commande` (
        `prenomDestinataire` varchar(50) not null default '???',
        `nomDestinataire`varchar(50) not null default '???',
        `id` int not null auto_increment,
        `dateHeure` datetime not null default NOW(),
        `adresseLivraison` varchar(50) not null,
        `cpLivraison` varchar(6) not null,
        `villeLivraison` varchar(50) not null,
        `numeroTel` varchar(13) not null,
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
        `qte` tinyint not null,
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


INSERT INTO Evenement(id,nom) values ('M3','Music Media-Mix Market'),('MISC','Divers');
INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3',44,2019),('MISC',0,0);



-- TRIGGER d'ajout de commandes
DELIMITER //
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

-- Problème ici
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

drop procedure if exists addMusiqueInAlbum//
CREATE PROCEDURE if not exists addMusiqueInAlbum(nomMusique varchar(70), nomArtiste varchar(70), nomAlbumAjout varchar(70))
BEGIN
DECLARE idAlbumAjout, idMusiqueAjout int;
SELECT id INTO idAlbumAjout from Album where nom = nomAlbumAjout;
IF (not exists(select * from Artiste where nom = nomArtiste)) THEN
        INSERT INTO ARTISTE(nom) VALUES(nomArtiste);
END IF;
INSERT INTO Musique(nom) Values (nomMusique);
SELECT max(id) INTO idMusiqueAjout FROM Musique;
INSERT INTO Composer(idMusique,idArtiste) values(idMusiqueAjout,(select id from Artiste where nom = nomArtiste));
INSERT INTO Contenir(idAlbum,idMusique,positionOrdreAlbum) values (idAlbumAjout,idMusiqueAjout,(select ifnull((select max(positionOrdreAlbum) from (select  positionOrdreAlbum,idAlbum from Contenir where idAlbum = idAlbumAjout)as maxPositionAlbum),0))+1);
END //

DELIMITER ;

call addAlbum('Dreams','Gabor Szabo',1,'MISC',0,50,15.99,'DREAMS.jpg','Dreams is an album by Hungarian guitarist Gábor Szabó featuring performances recorded in 1968 and released on the Skye label.','https://www.youtube.com/watch?v=gr0XWmEbiMQ','1968-01-01');
call addMusiqueInAlbum('Galatea''s Guitar', 'Gabor Szabo','Dreams');
call addMusiqueInAlbum('Half The Dayt Is Night','Gabor Szabo','Dreams');
call addMusiqueInAlbum('Song Of The Injured Love','Gabor Szabo','Dreams');
call addMusiqueInAlbum('The Fortune Teller', 'Gabor Szabo','Dreams');
call addMusiqueInAlbum('Fire Dance', 'Gabor Szabo','Dreams');
call addMusiqueInAlbum('The Lady In The Moon (From Kodaly)', 'Gabor Szabo','Dreams');
call addMusiqueInAlbum('Ferris Wheel', 'Gabor Szabo','Dreams');
INSERT INTO Evenement(id,nom) values ('C','Comic Market');
INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('C',103,2023);
call addAlbum('AD:PIANO VIVACE 2','Diverse System',0,'C',103,20,10,'ADPIANOVIVACE2.jpg','AD:PIANO VIVACE 2 is a piano album by Diverse System.','https://www.youtube.com/watch?v=oMQtmaBImBE','2023-12-30');
call addMusiqueInAlbum('Reverie','Gardens feat. xia','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('ViViD Delusion','KARUT','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Flying Emotion', 'Blacky','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Untheory','Avans','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('PANORAMA','muyu','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Amore Appassionato','seatrus','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Reincarnation','Essbee','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Pleiadescent','VeetaCrush','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('キャロルの瓦解','＊-Teris.','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Pastel Express','Cynax','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('secretspeel','tn-shi','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Flows With Errors','ARForest','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Phantoflux','xi','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Sempre.Vivacissimo','Polymath9','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Ce vios','Sobrem','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('傀儡のためのコンチェルト','のとを','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Ethereal Lullaby','yuichi NAGAO','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('躍動','Error Signal','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Beyond Your Words','Sad Keyboard Guy feat. vally.exe','AD:PIANO VIVACE 2');
call addMusiqueInAlbum('Meow Meow','Sazukyo','AD:PIANO VIVACE 2');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3',52,2023);

call addAlbum('20','HARDCORE TANO*C',0,'M3',52,30,15.99,'20.jpg','20 is a hardcore album by HARDCORE TANO*C.','https://www.youtube.com/watch?v=AsBGoWaWG5s','2023-10-11');
call addMusiqueInAlbum('Our Memories (feat. 小岩井ことり)','REDALiCE & kors k','20');
call addMusiqueInAlbum('YOLO','P*Light & YUC''e','20');
call addMusiqueInAlbum('Dream Away (feat. Yukacco)','DJ Noriken & DJ Genki','20');
call addMusiqueInAlbum('Beatboxer VS Trackmaker (feat. KAJI & Kohey)','t+pazolite','20');
call addMusiqueInAlbum('ブルーモーメント (feat. 松永依織)','Getty','20');
call addMusiqueInAlbum('FACE (feat. 山手響子, CV: 愛美)','Laur','20');
call addMusiqueInAlbum('カラフルビート (feat. ユメ, CV: 小田果林 & ユウ, CV: 高槻みゆう)','Srav3R & DJ Myosuke','20');
call addMusiqueInAlbum('Trust (feat. 光吉猛修)','Massive New Krew','20');
call addMusiqueInAlbum('WACCA ULTRA DREAM MEGAMIX','USAO & Kobaryo','20');
call addMusiqueInAlbum('SATELLITE','siqlo','20');
call addMusiqueInAlbum('B.O.S.S','RiraN','20');
call addMusiqueInAlbum('Shall we dance hardcore? (feat. 棗いつき)','RoughSketch','20');
call addMusiqueInAlbum('Garden of Eden (feat. Kanae Asaba)','aran','20');

call addAlbum('XII - The Devourer of Gods -','Mensis IV Aria Reliquiae',1,'C',103,30,10.99,'THEDEVOUREROFGODS.jpg','XII - The Devourer of Gods - is an album by Mensis IV Aria Reliquiae.','https://www.youtube.com/watch?v=jEGt_zhil4s','2023-12-30');
call addMusiqueInAlbum('Nightingale','Vocals:Eili','XII - The Devourer of Gods -');
call addMusiqueInAlbum('Vanitas','Vocals:Eili','XII - The Devourer of Gods -');
call addMusiqueInAlbum('Black Swan','Vocals:Eili','XII - The Devourer of Gods -');
call addMusiqueInAlbum('Oblivion','Vocals:Eili','XII - The Devourer of Gods -');
call addMusiqueInAlbum('Schadenfreude','Vocals:はらもりよしな','XII - The Devourer of Gods -');
call addMusiqueInAlbum('Afterglow','Vocals:AKA','XII - The Devourer of Gods -');


INSERT INTO Evenement(id,nom) values ('REI','Reitaisai');
INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('REI',8,2021);
call addAlbum('e^(x+i)<3u',".new label",0,'REI',8,50,8.99,'eLUVu.jpg','e^(x+i)<3u is an album by .new label.','https://www.youtube.com/watch?v=5-kHtw764OE','2021-10-16');
call addMusiqueInAlbum('into the EXTRA / 魔法少女達の百年祭','as key_','e^(x+i)<3u');
call addMusiqueInAlbum('bouchonne','as key_','e^(x+i)<3u');
call addMusiqueInAlbum('Eat up my HEART???','as key_','e^(x+i)<3u');
call addMusiqueInAlbum('Good-bye Suicide','as key_','e^(x+i)<3u');
call addMusiqueInAlbum('Implicature','as key_','e^(x+i)<3u');
call addAlbum('パラフォビア','lapix',1,'C','103',50,5.99,'PARAFOBIA.jpg','Paraphobia feat. 藍月なくる est un morceau de High-Tech Trance caractérisé par un rythme effréné, un chant puissant et des sons de synthétiseur énergiques.','https://www.youtube.com/watch?v=vzowc7DhDu8','2024-01-03');
call addMusiqueInAlbum('パラフォビア (feat. 藍月なくる)','lapix','パラフォビア');
call addMusiqueInAlbum('ルナティッククレイジ (feat. PANXI)','lapix ','パラフォビア');
call addMusiqueInAlbum('サイレン (feat. 奈良ひより)','lapix','パラフォビア');
call addMusiqueInAlbum('コンティニュー! (feat. 藍月なくる)','lapix','パラフォビア');
call addMusiqueInAlbum('八月の風が吹く頃 (feat. 棗いつき)','lapix','パラフォビア');
call addMusiqueInAlbum('宇宙遊泳 (feat. 中村さんそ)','lapix','パラフォビア');
call addMusiqueInAlbum('Free Myself (feat. mami)','lapix','パラフォビア');
call addMusiqueInAlbum('メリーバッド乙女 (feat. PANXI)','lapix','パラフォビア');
call addMusiqueInAlbum('ドラスティックジェネレイト (feat. mami)','lapix','パラフォビア');

call addAlbum('SPD GAR 003','MEGAREX',0,'MISC',0,8839,10.99,'SPDGAR03.jpg','SPD GAR 003 is a compilation album by MEGAREX.','https://www.youtube.com/watch?v=iayaAxMdX40','2020-04-28');

call addMusiqueInAlbum('Sunday Night (feat. Kanata.N)','Mameyudoufu','SPD GAR 003');
call addMusiqueInAlbum('Broken Light (feat. mami)','poplavor','SPD GAR 003');
call addMusiqueInAlbum('Continue (feat. Megumi Takahashi)','tokiwa','SPD GAR 003');
call addMusiqueInAlbum('On The West Coastline (feat. Punipuni Denki)','Dirty Androids','SPD GAR 003');
call addMusiqueInAlbum('Gone Days (feat. Ranasol)','Nhato','SPD GAR 003');
call addMusiqueInAlbum('Melting (feat. Nicole Curry)','TEMPLINE','SPD GAR 003');
call addMusiqueInAlbum('Feedback','Applekid','SPD GAR 003');
call addMusiqueInAlbum('Signal (feat. shully)','Tsubusare BOZZ','SPD GAR 003');
call addMusiqueInAlbum('Point of No Return (feat. Shizuki)','lapix','SPD GAR 003');
call addMusiqueInAlbum('ideal you (feat. Risa Yuzuki)','sky_delta','SPD GAR 003');
call addMusiqueInAlbum('Galaxy Vacation (feat. Punipuni Denki)','KOTONOHOUSE','SPD GAR 003');
call addMusiqueInAlbum('Square Connection (feat. Such)','LADY''S ONLY','SPD GAR 003');
call addMusiqueInAlbum('Sprout (feat. shully)','colate','SPD GAR 003');
call addMusiqueInAlbum('Open Your Heart (feat. Renko)','rejection','SPD GAR 003');
call addMusiqueInAlbum('illumination (feat. Yukaco)','hyleo','SPD GAR 003');

call addAlbum('Jive Round 3','zensen',1,'M3',52,533,9.99,'JIVEROUND3.jpg','Jive Round 3 is an album by zensen.','https://www.youtube.com/watch?v=6mB1Y1-z6Os','2023-10-11');
call addMusiqueInAlbum('Double-Sided-Party(Club Edit)','zensen','Jive Round 3');
call addMusiqueInAlbum('Jackpot Overdose','zensen','Jive Round 3');
call addMusiqueInAlbum('Swinging All Thieves','zensen','Jive Round 3');
call addMusiqueInAlbum('Feel My Secret','zensen','Jive Round 3');
call addMusiqueInAlbum('Billiards Jockey (Club Edit)','zensen','Jive Round 3');
call addMusiqueInAlbum('Jack Daniel','zensen','Jive Round 3');
call addMusiqueInAlbum('Jeez Selecta','zensen','Jive Round 3');
call addMusiqueInAlbum('Glitch Cocktail','zensen','Jive Round 3');
call addMusiqueInAlbum('Dancing with Congress','zensen','Jive Round 3');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3',50,2020);
call addAlbum('PARADØXY','BlackY feat. Risa Yuzuki',1,'M3',50,13,8.39,'PARADOXY.jpg','PARADØXY is an album by BlackY feat. Risa Yuzuki.','https://www.youtube.com/watch?v=B_B3DktCH-s','2022-10-30');
-- Ajout des chansons de l'album "PARADØXY" avec l'artiste "BlackY feat. Risa Yuzuki"
CALL addMusiqueInAlbum('PARADØXY', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('UNLEASHED', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('Starry Colors (Long ver.)', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('melty light', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('正しさに道連れ', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('標本', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('PARADØXY - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('UNLEASHED - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('Starry Colors - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('melty light - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('正しさに道連れ - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');
CALL addMusiqueInAlbum('標本 - Instrumental', 'BlackY feat. Risa Yuzuki', 'PARADØXY');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('C',102,2023);
call addAlbum('Flying Method','lapix',1,'C',102,10,10.10,'FLYINGMETHOD.jpg','Flying Method is an album by lapix.','https://www.youtube.com/watch?v=F-310GWdIYs','2023-08-13');
-- Ajout des chansons de l'album "Flying Method" avec l'artiste "lapix"
CALL addMusiqueInAlbum('Our Love (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Primitive Vibes (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Flying Castle (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('ハイテックトキオ (Extended Mix) 【lapix ∞ BEMANI Sound Team "Sota Fujimori"】', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('& Intelligence (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Double Dribble (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('glacia [STARDOM Remix] (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Rosa azuL (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('7 to Smoke (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Glitch Angel (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Ocean Blue feat. Luschel (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Volcanos (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Crumble Soul (Extended Mix)', 'lapix', 'Flying Method');
CALL addMusiqueInAlbum('Foolish Again (Extended Mix)', 'lapix', 'Flying Method');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3','51',2023);
call addAlbum('Beyond CORE EVANGELIX 03','MEGAREX',0,'M3',51,30,10,'BEYONDCOREEVANGELIX03.jpg','Beyond CORE EVANGELIX 03 is a compilation album by MEGAREX.','https://www.youtube.com/watch?v=1aeiJNOkh-Y','2023-04-30');
-- Ajout des chansons de l'album "Beyond CORE EVANGELIX 03"
CALL addMusiqueInAlbum('Rapid', 'Mylta', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Laser Tag', 'Mameyudoufu', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Answer', 'VOLTA', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('良いお菓子と悪いお菓子 (ft. L4hee)', 'Asatsumei', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('LUNARSCAPE', 'RYOQUCHA', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Follow_Me', 'Assertive', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Tonight', 'ZoeEngine', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Random Encounter', 'KARUT', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Take a Shot', 'Riku', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Vain', 'Aethral', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Ripple Effects', 'litmus*', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('flo-lo', 'Titancube', 'Beyond CORE EVANGELIX 03');
CALL addMusiqueInAlbum('Hypernova', 'rejection', 'Beyond CORE EVANGELIX 03');

call addAlbum('Moment.','MOTTO MUSIC',0,'M3',52,30,2.99,'MOMENT.jpg','Moment. is an album by MOTTO MUSIC.','https://www.youtube.com/watch?v=gMmNOH09oi0','2023-10-12');
-- Ajout des chansons de l'album "Moment."
CALL addMusiqueInAlbum('Glittering Sky (feat.Marpril)', 'picco', 'Moment.');
CALL addMusiqueInAlbum('empty (feat.KMNZ LITA)', 'tokiwa', 'Moment.');
CALL addMusiqueInAlbum('Ray of Lie (feat.Such)', 'DÉ DÉ MOUSE & Kakeru', 'Moment.');
CALL addMusiqueInAlbum('キュー (feat.メトロミュー)', 'KAIRUI', 'Moment.');
CALL addMusiqueInAlbum('Galactic Gourmet (feat.くいしんぼあかちゃん)', 'KOTONOHOUSE', 'Moment.');
CALL addMusiqueInAlbum('クソデカマジカルマジックマッシュルーム (feat.ちょこ)', 'なみぐる', 'Moment.');
CALL addMusiqueInAlbum('プリズムバード (feat.Risa Yuzuki)', 'CHOUX', 'Moment.');
CALL addMusiqueInAlbum('オテンキグラビティ (feat.雨宮みやび)', 'Ray_Oh', 'Moment.');
CALL addMusiqueInAlbum('Floating Summer (feat.夢乃ゆき)', 'yoswu', 'Moment.');

call addAlbum('NX ENCHANT 02','NEXTLIGHT',0,'MISC',0,39,39.39,'NXENCHANT02.jpg','NX ENCHANT 02 is an album by NEXTLIGHT.','https://www.youtube.com/watch?v=9OAuMw38IHY','2023-10-29');
-- Ajout des chansons de l'album "NX ENCHANT 02"
CALL addMusiqueInAlbum('メジルシ', 'Nor', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Storia', 'Reno', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('タイニーキャット', 'picco', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Take Me', 'Lunabitt', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Da/ys*', 'purini', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Scramberry!!', 'Capchii', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Miraizu', '瀬名', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('月歩き', 'DoubleLift', 'NX ENCHANT 02');
CALL addMusiqueInAlbum('Black Swan Theory', 'Reno, Hylen', 'NX ENCHANT 02');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3','49',2022);
call addAlbum('NX ENCHANT','NEXTLIGHT',0,'M3',49,110,3.99,'NXENCHANT.jpg','NX ENCHANT is an album by NEXTLIGHT.','https://www.youtube.com/watch?v=yUcUZUdsbpE','2022-04-17');
-- Ajout des chansons de l'album "NX ENCHANT"
CALL addMusiqueInAlbum('Sweet Trick', 'picco', 'NX ENCHANT');
CALL addMusiqueInAlbum('Overrun', 'Twinfield', 'NX ENCHANT');
CALL addMusiqueInAlbum('Lullaby', 'ckwa', 'NX ENCHANT');
CALL addMusiqueInAlbum('Magic Theory', 'Reno', 'NX ENCHANT');
CALL addMusiqueInAlbum('inner', 'Mysteka', 'NX ENCHANT');
CALL addMusiqueInAlbum('いいわけクレセント', 'picco', 'NX ENCHANT');
CALL addMusiqueInAlbum('Enough', 'Mwk', 'NX ENCHANT');
CALL addMusiqueInAlbum('New Normal', 'tekalu', 'NX ENCHANT');
CALL addMusiqueInAlbum('It''s getting warmer day', 'DenDora, picco', 'NX ENCHANT');
CALL addMusiqueInAlbum('Secret Crush', 'HALA1004', 'NX ENCHANT');

call addAlbum('The Umbra','ARForest',1,'M3',51,40,99.99,'THEUMBRA.jpg','The Umbra is an album by ARForest.','https://www.youtube.com/watch?v=OnJuGTW9wkY','2023-04-21');
-- Ajout des chansons de l'album "The Umbra", il y a normalement deux disques dans l'album mais pas nécessaire à gérer pour ce contexte.
CALL addMusiqueInAlbum('The Umbra (feat.Sennzai)', 'ARForest', 'The Umbra');
CALL addMusiqueInAlbum('Paradox', 'Maozon', 'The Umbra');
CALL addMusiqueInAlbum('EZ-COM3-EZ-G0', 'KARUT', 'The Umbra');
CALL addMusiqueInAlbum('Combat', 'EmoCosine', 'The Umbra');
CALL addMusiqueInAlbum('Jotunheim', 'Nhato', 'The Umbra');
CALL addMusiqueInAlbum('RIOT', 'KO3', 'The Umbra');
CALL addMusiqueInAlbum('Flashout', 'Junk', 'The Umbra');
CALL addMusiqueInAlbum('stupor', 'awfuless', 'The Umbra');
CALL addMusiqueInAlbum('Highly Spiral', 'BlackY', 'The Umbra');
CALL addMusiqueInAlbum('Mandragora', 'Zekk', 'The Umbra');
CALL addMusiqueInAlbum('Emerald Green', 'Blacklolita', 'The Umbra');
CALL addMusiqueInAlbum('Moon', 'Juggernaut.', 'The Umbra');
CALL addMusiqueInAlbum('Đ', 'Hylen', 'The Umbra');
CALL addMusiqueInAlbum('Connect to Relic', '黒魔', 'The Umbra');
CALL addMusiqueInAlbum('Motherboard', 'Paul Bazooka', 'The Umbra');
CALL addMusiqueInAlbum('⁄⁄ alumina_', 'Nor', 'The Umbra');
CALL addMusiqueInAlbum('Fabricated Personality', 'Mameyudoufu', 'The Umbra');
CALL addMusiqueInAlbum('Natur[Z]eit', 'Street', 'The Umbra');
CALL addMusiqueInAlbum('One More Night', 'RiraN', 'The Umbra');
CALL addMusiqueInAlbum('solarblade', 'BilliumMoto', 'The Umbra');
CALL addMusiqueInAlbum('R.T.S', 'Soochan', 'The Umbra');
CALL addMusiqueInAlbum('New Horizon', 'Tatsunoshin', 'The Umbra');
CALL addMusiqueInAlbum('Think About You', 'Hommarju', 'The Umbra');
CALL addMusiqueInAlbum('Dot Karma', 'Tanchiky', 'The Umbra');
CALL addMusiqueInAlbum('Church_Emulator', 'Team Grimoire', 'The Umbra');
CALL addMusiqueInAlbum('Reborn Again', 'YUKIYANAGI', 'The Umbra');
CALL addMusiqueInAlbum('Escape a Cyber City', 'Laur', 'The Umbra');
CALL addMusiqueInAlbum('Amnesia', 'DJ Noriken', 'The Umbra');

call addAlbum('ARTIFACTS：ZERØ','Connexio',1,'M3',50,107,0.99,'ARTIFACTSZERO.jpg','ARTIFACTS：ZERØ is an album by Connexio.','https://www.youtube.com/watch?v=pJnzoz1cn-g','2022-10-20');
-- Ajout des chansons de l'album "ARTIFACTS：ZERØ"
CALL addMusiqueInAlbum('Arte Factum', 'Connexio', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Cellnix', 'MoAE:. feat.可不', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Swarm of Outdoor Units', 'RYOQUCHA', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Artemizija Transfiguration', 'CS4W', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Electra', 'KONPEKi', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Disobedience', '現役JK', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('&#0;', 'ADA', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('eclipsization', 'ぴれんどらー', 'ARTIFACTS：ZERØ');
CALL addMusiqueInAlbum('Missing Planet', '黒魔', 'ARTIFACTS：ZERØ');

call addAlbum('PSYcoLogy','Osanzi',1,'MISC',0,150,5.99,'PSYCOLOGY.jpg','PSYcoLogy is an album by Osanzi.','https://www.youtube.com/watch?v=GVfPXzxVi_U','2021-12-27');
-- Ajout des chansons de l'album "PSYcoLogy" par Osanzi
CALL addMusiqueInAlbum('白日夢', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('マニピュレート', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('イマジン', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('Summer Love (2021 mix)', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('In Your Eyes (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('True Color (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('Dance With Me (re-edit)', 'Osanzi', 'PSYcoLogy');
CALL addMusiqueInAlbum('With U', 'Osanzi', 'PSYcoLogy');

INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('C',97,2019);
call addAlbum('リファクタリング・トラベル -Refactoring Travel-','t+pazolite',1,'C',97,3514,12.99,'REFACTORINGTRAVEL.jpg','リファクタリング・トラベル -Refactoring Travel- is an album by t+pazolite.','https://www.youtube.com/watch?v=NJG6XB_FVsU','2020-03-26');
-- Ajout des chansons de l'album "リファクタリング・トラベル -Refactoring Travel-" par t+pazolite
CALL addMusiqueInAlbum('Intro - I''ll be waiting for you', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Dive High', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Refactoring Travel (feat. ななひら)', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('TOKONOMA Spacewalk (Uncut Edition) (c)Rayark / from Cytus II', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Fusion Cruise', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Duality Drive', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Waku ga Dokidoki', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('星屑ストラック (Uncut Edition) from 太鼓の達人', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('ブズーブズービチービチーブベベベベベピーゴゴゴゴゴ', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('What a Hyped Beautiful World', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('Good Night, Bad Luck (Uncut Edition) (c)TAITO / from Groove Coaster', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');
CALL addMusiqueInAlbum('星屑ストラック (かねこちはる Remix)', 't+pazolite', 'リファクタリング・トラベル -Refactoring Travel-');

call addAlbum('Meadowsweet','Sigillum Azoetia',0,'MISC',0,0,99,'MEADOWSWEET.jpg','Meadowsweet is an album by Sigillum Azoetia.','https://www.youtube.com/watch?v=F0uFQR036-0','2022-10-30');
-- Ajout des chansons de l'album "Meadowsweet" par GOETIΛ
CALL addMusiqueInAlbum('Transient Epileptic Amnesia', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Blodeuwedd', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Scroll of Adepha', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Sacrificial Doll', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Meadowsweet', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('The Destroyer', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('SERA 2405', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Rusticate', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Maidwell', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Beltshera''s Record', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Carmine', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('A3XECR', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Queen Carrier', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('The Mechanical Eye', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Inverse Electron Demand Diels-Alder', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Pennyroyal', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Hamartia', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Pillars of Enoch', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Kolbrin', 'GOETIΛ', 'Meadowsweet');
CALL addMusiqueInAlbum('Nefatari''s Lament', 'GOETIΛ', 'Meadowsweet');

call addAlbum('Vintage Emotion 2','Login Records',0,'C',103,1980,19.98,'VINTAGEEMOTION2.jpg','Vintage Emotion 2 is an album by Login Records.','https://www.youtube.com/watch?v=mDOrmEtPZ9E','2023-10-30');
-- Ajout des chansons de l'album "Vintage Emotion 2"
CALL addMusiqueInAlbum('Rainy Tarte', 'you', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('Crossing point', 'akatora', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('One Day', 'hu-zin', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('Midnight Poolside', 'Risen', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('Cybernetic Rock City', 'pocotan', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('NIGHT CITY', 'DJ AIR-G', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('Chicago Rain', 'DXST', 'Vintage Emotion 2');
CALL addMusiqueInAlbum('Awake Reverie', 'YUKIYANAGI', 'Vintage Emotion 2');

call addAlbum('MEGATON KICK 5','HARDCORE TANO*C',0,'C','103',2024,9.99,'MEGATONKICK5.jpg','MEGATON KICK 5 is an album by HARDCORE TANO*C.','https://www.youtube.com/watch?v=LBM6B89d3SU','2023-12-15');
-- Ajout des chansons de l'album "MEGATON KICK 5"
CALL addMusiqueInAlbum('Megaton Keeper', 'DJ Myosuke', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Never Fall Apart', 'RiraN', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Movin''', 'Massive New Krew', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Flames of Fate', 'DJ Noriken', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('56 Seconds Later', 't+pazolite', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Tokyo Midnight (feat. mami)', 'Yuta Imai', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Hera', 'Gram', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Announcement', 'RoughSketch', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Louder', 'Laur', 'MEGATON KICK 5');
CALL addMusiqueInAlbum('Cosmic Diffusion', 'Kobaryo', 'MEGATON KICK 5');

call addAlbum('SHIFT','KO3',1,'M3',50,4,9.3,'SHIFT.jpg','SHIFT is an album by KO3.','https://soundcloud.com/dj-ko3/ko3-3rd-album-shiftxfd','2022-10-30');
-- Ajout des chansons de l'album "SHIFT" par KO3
CALL addMusiqueInAlbum('MAK3SHIFT', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('Eeny,Meeny... (feat. 藤村鼓乃美）', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('Not Alone (feat. MYLK)', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('JVST RYDING', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('GAME OVER', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('FEEL', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('Magnet (feat. Renko)', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('I''ll be for you (feat. Kanae Asaba)', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('I''ll be for you VIP', 'KO3', 'SHIFT');
CALL addMusiqueInAlbum('Summery (Club Extended Mix)', 'KO3', 'SHIFT');
INSERT INTO Edition_Evenement(idEvent,numEdition,annee) values ('M3',53,2024);
call addAlbum('DUMMY DISC','t+pazolite',1,'M3',53,50,19.99,'DUMMY_DISC.jpg','DUMMY DISC est un album par t+pazolite pour C.H.S','https://www.youtube.com/watch?v=Hz13QgklIMk','2024-09-30');
-- Ajout des chansons de l'album "DUMMY DISC" par t+pazolite
CALL addMusiqueInAlbum('It\'s a DUMMY', 't+pazolite', 'DUMMY DISC');
CALL addMusiqueInAlbum('Makina 2022', 't+pazolite', 'DUMMY DISC');
CALL addMusiqueInAlbum('Never Dr0p', 't+pazolite', 'DUMMY DISC');

call addAlbum('cottage','歩く人',1,'M3',53,50,10.99,'COTTAGE.jpg','cottage est un album par 歩く人, qui nous change de TOUT !','https://www.youtube.com/watch?v=2RP-GBq3H5I','2024-05-30');
CALL addMusiqueInAlbum('部屋の窓から', '歩く人', 'cottage');
CALL addMusiqueInAlbum('わたしの組成式', '歩く人', 'cottage');
CALL addMusiqueInAlbum('in the gray', '歩く人', 'cottage');
CALL addMusiqueInAlbum('CREATION', '歩く人', 'cottage');
CALL addMusiqueInAlbum('メゾン', '歩く人', 'cottage');
CALL addMusiqueInAlbum('SEPIA NOTES', '歩く人', 'cottage');
CALL addMusiqueInAlbum('メイデイ', '歩く人', 'cottage');
CALL addMusiqueInAlbum('ゴーストレストラン', '歩く人', 'cottage');
CALL addMusiqueInAlbum('あれ？', '歩く人', 'cottage');
CALL addMusiqueInAlbum('リフェクトリ', '歩く人', 'cottage');
CALL addMusiqueInAlbum('トレンチ', '歩く人', 'cottage');
CALL addMusiqueInAlbum('メトロタクシー', '歩く人', 'cottage');
CALL addMusiqueInAlbum('天気雨の原理', '歩く人', 'cottage');

call addAlbum('いのぷれりゅうど','yuru',1,'M3',53,50,13.99,'INOPURERYUDO.jpg','Première album de YURU, いのぷれりゅうど est une aude au bonheur !','https://www.youtube.com/watch?v=2RP-GBq3H5I','2024-12-30');
-- Ajout des chansons de l'album "いのぷれりゅうど" par yuru
CALL addMusiqueInAlbum('ファンファーレの鳴る空に', 'yuru', 'いのぷれりゅうど');
CALL addMusiqueInAlbum('Delicious✩Journey(Solo ver.)', 'yuru', 'いのぷれりゅうど');
CALL addMusiqueInAlbum('Magic Of Colors', 'yuru', 'いのぷれりゅうど');
CALL addMusiqueInAlbum('プレリュード', 'yuru', 'いのぷれりゅうど');
call addAlbum('NONEXISTENT VITRUVIUS','E0ri4',1,'M3',53,50,44.44,'NONEXISTENT_VITRUVIUS.jpg','4ème album de E0ri4, NONEXISTENT VITRUVIUS fait preuve d\'une grande originalité et nous fait bondir de nos chaises !','https://www.youtube.com/watch?v=rBg0Rng2D54','2024-12-30');
-- Ajout des chansons de l'album "NONEXISTENT VITRUVIUS" par E0ri4
CALL addMusiqueInAlbum('Unusualeisure', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addMusiqueInAlbum('Bloodthirsty', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addMusiqueInAlbum('Plot Type', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addMusiqueInAlbum('Signature Analysis', 'E0ri4', 'NONEXISTENT VITRUVIUS');
CALL addMusiqueInAlbum('My Origin', 'E0ri4', 'NONEXISTENT VITRUVIUS');

call addAlbum('Steppin\' for Summer','xenigata',1,'M3',53,50,99.99,'STEPPIN_FOR_SUMMER.jpg','Inconnu du bataillon, j\'ai vraiment rien à écrire là','https://www.youtube.com/watch?v=m4O4RBr_JNg','2024-12-30');
-- Ajout des chansons de l'album "Steppin' for Summer" par xenigata
CALL addMusiqueInAlbum('夜と空 ft.雨汰。', 'xenigata', 'Steppin\' for Summer');
CALL addMusiqueInAlbum('Summer Diary ft.記憶に残る', 'xenigata', 'Steppin\' for Summer');
CALL addMusiqueInAlbum('君宛て ft.ことの', 'xenigata', 'Steppin\' for Summer');

call addAlbum('IRREGULAR NATION 10','HARDCORE TANO*C',0,'M3',53,50,10.01,'IRREGULAR_NATION_10.jpg','Les rois du monde ces types l\'album est fou mais pas nécessaire la couverture','https://www.youtube.com/watch?v=ojthAKDF_R4','2024-12-30');
-- Ajout des chansons de l'album "IRREGULAR NATION 10"
CALL addMusiqueInAlbum('Need You', 'REDALiCE', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Recollection', 'Getty', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Calm & Passionate', 't+pazolite', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Groovy Bunny', 'Laur', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Whisper of Despair feat. 蛇塚透花', 'Kobaryo', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Moving On', 'USAO & Shandy Kubota', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Shadow Bang', 'DJ Myosuke', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Bar Do Thos Grol', 'Massive New Krew', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Casual Encounter', 'aran', 'IRREGULAR NATION 10');
CALL addMusiqueInAlbum('Fake illness', 'Srav3R', 'IRREGULAR NATION 10');

call addAlbum('Trinity Force','Zekk',1,'M3',53,50,9.99,'TRINITY_FORCE.jpg','Trinity Force est un album par Zekk, l\'album du siècle selon le titre du XFD','https://www.youtube.com/watch?v=7GjohEBsfqQ','2025-01-01');
-- Ajout des chansons de l'album "Trinity Force" par Zekk
CALL addMusiqueInAlbum('Trinity Force', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Swampgator [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Let Me Hear [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Haetae [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Falling Down feat. Renko × TRI△NGLE [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Count [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('D4NCE [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('SUMMER [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Foresight [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Duality Rave (Zekk\'s \'FULL SPEC\' Remix) [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Your voice so... feat. Such (Zekk\'s \'FULL SPEC\' Remix) [2019 Remaster]', 'Zekk', 'Trinity Force');
CALL addMusiqueInAlbum('Astronomical Optical Interferometry [2019 Remaster]', 'Zekk', 'Trinity Force');

call addAlbum('#B​.​E​.​R​.​Radio','Blatantly Emotional Records',0,'M3',52,50,11.11,'BERRADIO.jpg','#B​.​E​.​R​.​Radio is an album by Blatantly Emotional Records.','https://www.youtube.com/watch?v=rcv3hmXt_Bc','2099-01-30');
-- Ajout des chansons de l'album "#B​.​E​.​R​.​Radio"
CALL addMusiqueInAlbum('Always beside you', 'DenDora', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('PARTY TIME', 'joinT', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('Tape Stop', '書店太郎', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('Riverside on rainy weekend', 'joinT', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('EVER_BLUE（Over Dub）', '書店太郎', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('Sunset Serenade', 'ぼぉの', '#B​.​E​.​R​.​Radio');
CALL addMusiqueInAlbum('Hello_Desktop', '書店太郎 & DenDora', '#B​.​E​.​R​.​Radio');

INSERT INTO Role(id,nom) values (999,'admin'),(1,'user');

INSERT INTO User(prenom,nom,mail,passwdHash,idRole) values ('Damien','R.','damien@damdam.fr','e',999),('Berre','Etang','Etang@berre.fr','',1),('Eva','Cuhassion','cuhassion.eva@genmarre.com','',1);

-- Insertion de l'album RUNABOUT (https://diverse.jp/dvsp-0229/), vieille méthode avant écriture des fonctions
INSERT INTO Artiste(nom) values('tanigon'),('void (Mournfinale)'),
                        ('Nago'),('Kenichi Chiba'),
                        ('filmiiz'),('Masayoshi Minoshima'),('Lawy'),
                        ('くるぶっこちゃん');

INSERT INTO Musique(nom) values ('Storm Spirit'),('Wander Heaven'),('Petrolhead'),
                        ('Drive Out'),('#THE_DVRL_286'),('AM 4:53'),
                        ('Signals'),('Future Traffic'),('Wild Chaser'),
                        ('Orange Night'),('TECHNODRIVE'),('Far from here'),
                        ('Neon Breeze');

INSERT INTO Composer(idMusique,idArtiste) values 
((select id from Musique where nom = 'Storm Spirit'),(select id from Artiste where nom = 'tanigon')),
((select id from Musique where nom = 'Wander Heaven'),(select id from Artiste where nom = 'BlackY')),
((select id from Musique where nom = 'Petrolhead'),(select id from Artiste where nom = 'void (Mournfinale)')),
((select id from Musique where nom = 'Drive Out'), (select id from Artiste where nom = 'ARForest')),
((select id from Musique where nom = '#THE_DVRL_286'),(select id from Artiste where nom = 'DJ Noriken')),
((select id from Musique where nom = 'AM 4:53'),(select id from Artiste where nom = 'lapix')),
((select id from Musique where nom = 'Signals'),(select id from Artiste where nom = 'Maozon')),
((select id from Musique where nom = 'Future Traffic'),(select id from Artiste where nom = 'Nago')),
((select id from Musique where nom = 'Wild Chaser'),(select id from Artiste where nom = 'Kenichi Chiba')),
((select id from Musique where nom = 'Orange Night'),(select id from Artiste where nom = 'filmiiz')),
((select id from Musique where nom = 'TECHNODRIVE'),(select id from Artiste where nom = 'Masayoshi Minoshima')),
((select id from Musique where nom = 'Far from Here'),(select id from Artiste where nom = 'Lawy')),
((select id from Musique where nom = 'Neon Breeze'),(select id from Artiste where nom = 'くるぶっこちゃん'));

INSERT INTO Album(nom,idLabel,qte,prix,uriImage) values ('RUNABOUT',(select id from Label where nom = 'Diverse System'),100,9.99,'RUNABOUT.jpg');

INSERT INTO Contenir (idAlbum,idMusique,positionOrdreAlbum) values
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Storm Spirit'),1),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Wander Heaven'),2),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Petrolhead'),3),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Drive Out'),4),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = '#THE_DVRL_286'),5),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'AM 4:53'),6),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Signals'),7),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Future Traffic'),8),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Wild Chaser'),9),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Orange Night'),10),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'TECHNODRIVE'),11),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Far from Here'),12),
((select id from Album where nom = 'RUNABOUT'),(select id from Musique where nom = 'Neon Breeze'),13);

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