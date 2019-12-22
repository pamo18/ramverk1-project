--
-- Creating a sample table.
--



--
-- Table Tag
--
DROP TABLE IF EXISTS Tag2Question;
CREATE TABLE Tag2Question (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questionid" INTEGER NOT NULL,
    "tagname" INTEGER NOT NULL,
    FOREIGN KEY(questionid) REFERENCES Question(id),
    FOREIGN KEY(tagname) REFERENCES Tag(name)
);
