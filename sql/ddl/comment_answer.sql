--
-- Creating a sample table.
--



--
-- Table Comment
--
DROP TABLE IF EXISTS CommentAnswer;
CREATE TABLE CommentAnswer (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questionid" INTEGER NOT NULL,
    "answerid" INTEGER NOT NULL,
    "user" TEXT NOT NULL,
    "text" TEXT NOT NULL,
    "vote" TEXT DEFAULT 0,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
