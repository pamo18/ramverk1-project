#!/usr/bin/env bash
# shellcheck disable=SC2181

#
# Load a SQL file into db
#
sqlite3 data/db_test.sqlite < sql/ddl/question.sql
sqlite3 data/db_test.sqlite < sql/ddl/answer.sql
sqlite3 data/db_test.sqlite < sql/ddl/comment_question.sql
sqlite3 data/db_test.sqlite < sql/ddl/comment_answer.sql
sqlite3 data/db_test.sqlite < sql/ddl/tag.sql
sqlite3 data/db_test.sqlite < sql/ddl/tag2question.sql
sqlite3 data/db_test.sqlite < sql/ddl/user.sql

sqlite3 data/db_test.sqlite < sql/reset/question_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/answer_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/comment_question_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/comment_answer_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/tag_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/tag2question_reset.sql
sqlite3 data/db_test.sqlite < sql/reset/user_reset.sql
