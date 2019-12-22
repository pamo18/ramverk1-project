--
-- Reset Tag
--

DELETE FROM `Tag2Question`;

INSERT INTO `Tag2Question` (`questionid`, `tagname`) VALUES
    (1, "Nintendo"),
    (1, "Mario"),
    (2, "Nintendo"),
    (2, "Mario"),
    (2, "Mushrooms"),
    (3, "Half Life 3"),
    (3, "Alyx"),
    (4, "GOT"),
    (4, "Resdient Evil 2"),
    (5, "Zelda"),
    (5, "Mario"),
    (6, "The last of us"),
    (6, "The last of us part 2")
;

SELECT * FROM `Tag2Question`;
