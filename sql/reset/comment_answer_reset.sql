--
-- Reset CommentAnswer
--

DELETE FROM `CommentAnswer`;

INSERT INTO `CommentAnswer` (`questionid`, `answerid`, `user`, `text`) VALUES
    (1, 1, "doe", "Is it not B"),
    (6, 6, "pamo18", "Good answer!")
;
