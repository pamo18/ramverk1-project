--
-- Creating a sample table.
--



--
-- Table Answer
--
DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questionid" INTEGER NOT NULL,
    "user" TEXT NOT NULL,
    "text" TEXT NOT NULL,
    "vote" INTEGER DEFAULT 0,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
