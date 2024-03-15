CREATE TABLE Favori (
    idUtilisateur INT,
    idFestival    INT(11),
    PRIMARY KEY (idUtilisateur,idFestival)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE Favori
    ADD FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur);

ALTER TABLE Favori
    ADD FOREIGN KEY (idFestival) REFERENCES Festival (idFestival);


CREATE TRIGGER festival_d
BEFORE DELETE ON festival
FOR EACH ROW
BEGIN
    DELETE FROM equipeorganisatrice WHERE equipeorganisatrice.idFestival = OLD.idFestival;
    DELETE FROM favori WHERE favori.idFestival = OLD.idFestival;
    DELETE FROM jour WHERE jour.idGrij = (SELECT idGrij from grij where grij.idGrij = old.idFestival);
    DELETE FROM grij WHERE grij.idGrij = OLD.idFestival;
    DELETE FROM spectacledefestival WHERE spectacledefestival.idFestival = OLD.idFestival;
    DELETE FROM spectaclescenes WHERE spectaclescenes.idFestival = OLD.idFestival;
    DELETE FROM spectaclesjour WHERE spectaclesjour.idFestival = OLD.idFestival;
end;