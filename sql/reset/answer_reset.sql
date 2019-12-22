--
-- Reset Answer
--

DELETE FROM `Answer`;

INSERT INTO `Answer` (`questionid`, `user`, `text`) VALUES
    (1, "pamo18", "Press A."),
    (2, "pamo18", "Yes"),
    (3, "doe", "No"),
    (4, "doe", "Resident Evil 2 remake"),
    (5, "pamo18", "The Legend of Zelda: Breath of the Wild"),
    (6, "doe", "Dont know, try game-tagging it!")
;

SELECT * FROM `Answer`;
