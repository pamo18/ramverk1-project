--
-- Reset Comment
--

DELETE FROM `CommentQuestion`;

INSERT INTO `CommentQuestion` (`questionid`, `user`, `text`) VALUES
    (1, "pamo18", "Have you never played a game before?"),
    (3, "pamo18", "That VR crap isn't for me either!")
;

SELECT * FROM `CommentQuestion`;
