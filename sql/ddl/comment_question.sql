--
-- Creating a sample table.
--



--
-- Table Comment
--
DROP TABLE IF EXISTS CommentQuestion;
CREATE TABLE CommentQuestion (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questionid" INTEGER NOT NULL,
    "user" TEXT NOT NULL,
    "text" TEXT NOT NULL,
    "vote" TEXT DEFAULT 0,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
