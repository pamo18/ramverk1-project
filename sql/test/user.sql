--
-- Creating a User table.
--



--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "username" TEXT NOT NULL,
    "password" TEXT NOT NULL,
    "email" TEXT NOT NULL,
    "rank" INT DEFAULT 0,
    "voted" INT DEFAULT 0,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
