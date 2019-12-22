--
-- Reset User
--

DELETE FROM `User`;

INSERT INTO `User` (`username`, `password`, `email`) VALUES
    ("doe", "doe", "doe@mail.com")
;
