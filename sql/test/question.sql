--
-- Creating a sample table.
--



--
-- Table Question
--
DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "title" TEXT NOT NULL,
    "user" TEXT NOT NULL,
    "text" TEXT NOT NULL,
    "vote" INTEGER DEFAULT 0,
    "accepted" INTEGER,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
