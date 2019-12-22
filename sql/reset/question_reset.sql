--
-- Reset Question
--

DELETE FROM `Question`;

INSERT INTO `Question` (`title`, `user`, `text`) VALUES
    ("Mario World Level 1", "doe", "How do you jump?"),
    ("Mario World Level 2", "doe", "Are mushrooms dangerous?"),
    ("Half Life 3?", "doe", "Will there ever be a proper Half Life 3?!?"),
    ("Game of the year", "pamo18", "What is the best game of 2019?"),
    ("Best ever Nintendo Game?", "doe", "What is the best ever Nintendo game, whatever platform?"),
    ("The Last Of Us Part 2", "pamo18", "When will The Last Of Us Part 2 be released?")
;

SELECT * FROM `Question`;
