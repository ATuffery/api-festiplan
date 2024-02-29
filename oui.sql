CREATE TABLE Favori (
    idUtilisateur INT,
    idFestival    INT(11),
    PRIMARY KEY (idUtilisateur,idFestival)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE Favori
    ADD FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur (idUtilisateur);

ALTER TABLE Favori
    ADD FOREIGN KEY (idFestival) REFERENCES Festival (idFestival);