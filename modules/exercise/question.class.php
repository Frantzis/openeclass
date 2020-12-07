<?php

/* ========================================================================
 * Open eClass 3.7
 * E-learning and Course Management System
 * ========================================================================
 * Copyright 2003-2019  Greek Universities Network - GUnet
 * A full copyright notice can be read in "/info/copyright.txt".
 * For a full list of contributors, see "credits.txt".
 *
 * Open eClass is an open platform distributed in the hope that it will
 * be useful (without any warranty), under the terms of the GNU (General
 * Public License) as published by the Free Software Foundation.
 * The full license can be read in "/info/license/license_gpl.txt".
 *
 * Contact address: GUnet Asynchronous eLearning Group,
 *                  Network Operations Center, University of Athens,
 *                  Panepistimiopolis Ilissia, 15784, Athens, Greece
 *                  e-mail: info@openeclass.org
 * ======================================================================== */

if (!class_exists('Question')) {

    /**
     * @brief This class allows to instantiate an object of type Question
     */
    class Question {

        private $id;
        private $question;
        private $description;
        private $weighting;
        private $position;
        private $type;
        private $difficulty;
        private $category;
        private $exerciseList;  // array with the list of exercises which this question is in

        /**
         * constructor of the class
         *
         * @author - Olivier Brouckaert
         */
        public function __construct() {
            $this->id = 0;
            $this->question = '';
            $this->description = '';
            $this->weighting = 0;
            $this->position = 1;
            $this->type = 1;
            $this->difficulty = 0;
            $this->category = 0;
            $this->exerciseList = array();
        }

        /**
         * reads question information from the data base
         *
         * @author - Olivier Brouckaert
         * @param - integer $id - question ID
         * @return - boolean - true if question exists, otherwise false
         */
        function read($id) {
            global $course_id;

            $object = Database::get()->querySingle("SELECT question, description, weight, `type`, difficulty, category
                        FROM `exercise_question` WHERE course_id = ?d AND id = ?d", $course_id, $id);
            // if the question has been found
            if ($object) {
                $this->id = $id;
                $this->question = $object->question;
                $this->description = $object->description;
                $this->weighting = $object->weight;
                $this->type = $object->type;
                $this->difficulty = $object->difficulty;
                $this->category = $object->category;

                $result = Database::get()->queryArray("SELECT exercise_id FROM `exercise_with_questions` WHERE question_id = ?d", $id);
                // fills the array with the exercises which this question is in
                foreach ($result as $row) {
                    $this->exerciseList[] = $row->exercise_id;
                }

                return true;
            }

            // question not found
            return false;
        }

        /**
         * returns the question ID
         *
         * @author - Olivier Brouckaert
         * @return - integer - question ID
         */
        function selectId() {
            return $this->id;
        }

        /**
         * returns the question title
         *
         * @author - Olivier Brouckaert
         * @return - string - question title
         */
        function selectTitle() {
            return $this->question;
        }

        /**
         * returns the question description
         *
         * @author - Olivier Brouckaert
         * @return - string - question description
         */
        function selectDescription() {
            return $this->description;
        }

        /**
         * returns the question weighting
         *
         * @author - Olivier Brouckaert
         * @return - float - question weighting
         */
        function selectWeighting() {
            return $this->weighting;
        }

        /**
         * returns the question position
         *
         * @author - Olivier Brouckaert
         * @return - integer - question position
         */
        function selectPosition() {
            return $this->position;
        }

        /**
         * returns the answer type
         */
        function selectType() {
            return $this->type;
        }
        /**
         * returns the question difficulty
         */
        function selectDifficulty() {
            return $this->difficulty;
        }


        /**
         * @brief get difficulty icon + text
         * @param $difficulty
         * @return string
         */
        function selectDifficultyIcon($difficulty) {
            global $langQuestionVeryEasy, $langQuestionEasy, $langQuestionModerate,
                   $langQuestionDifficult, $langQuestionVeryDifficult;

            switch($difficulty) {
                case 1: return icon('fa-smile-o', $langQuestionVeryEasy);
                case 2: return icon('fa-smile-o', $langQuestionEasy);
                case 3: return icon('fa-meh-o', $langQuestionModerate);
                case 4: return icon('fa-exclamation', $langQuestionDifficult);
                case 5: return icon('fa-fire', $langQuestionVeryDifficult);
            }
        }

        /**
         * @brief get
         * @param $difficulty text
         * @return string
         */
        function selectDifficultyLegend($difficulty) {
            global $langQuestionVeryEasy, $langQuestionEasy, $langQuestionModerate,
                   $langQuestionDifficult, $langQuestionVeryDifficult;


            switch($difficulty) {
                case 1: return $langQuestionVeryEasy;
                case 2: return $langQuestionEasy;
                case 3: return $langQuestionModerate;
                case 4: return $langQuestionDifficult;
                case 5: return $langQuestionVeryDifficult;
            }
        }


        /**
         * returns the question category
         */
        function selectCategory() {
            return $this->category;
        }


        /*
         * @brief get category name
         */
        function selectCategoryName($cat_id) {

            global $course_id;

            $q = Database::get()->querySingle("SELECT question_cat_name FROM exercise_question_cats 
                                  WHERE question_cat_id = ?d 
                                  AND course_id = ?d", $cat_id, $course_id);
            if ($q) {
                return $q->question_cat_name;
            } else {
                return null;
            }
        }

        /**
         * returns the relative verbal answer type
         */
        function selectTypeLegend($answerTypeId) {
            global $langUniqueSelect, $langMultipleSelect, $langFillBlanks,
                   $langMatching, $langTrueFalse, $langFreeText,
                   $langFillBlanksStrict, $langFillBlanksTolerant;

            switch ($answerTypeId) {
                case UNIQUE_ANSWER:
                    return $langUniqueSelect;
                case MULTIPLE_ANSWER:
                    return $langMultipleSelect;
                case FILL_IN_BLANKS:
                    return "$langFillBlanks ($langFillBlanksStrict)";
                case MATCHING:
                    return $langMatching;
                case TRUE_FALSE:
                    return $langTrueFalse;
                case FREE_TEXT:
                    return $langFreeText;
                case FILL_IN_BLANKS_TOLERANT:
                    return "$langFillBlanks ($langFillBlanksTolerant)";
            }
        }
        /**
         * returns the array with the exercise ID list
         *
         * @author - Olivier Brouckaert
         * @return - array - list of exercise ID which the question is in
         */
        function selectExerciseList() {
            return $this->exerciseList;
        }

        /**
         * returns the number of exercises which this question is in
         *
         * @author - Olivier Brouckaert
         * @return - integer - number of exercises
         */
        function selectNbrExercises() {
            if (is_array($this->exerciseList)) {
                return sizeof($this->exerciseList);
            }
        }

        /**
         * changes the question title
         *
         * @author - Olivier Brouckaert
         * @param - string $title - question title
         */
        function updateTitle($title) {
            $this->question = $title;
        }
        /**
         * changes the question description
         *
         * @author - Olivier Brouckaert
         * @param - string $description - question description
         */
        function updateDescription($description) {
            $this->description = $description;
        }
        /**
         * changes the question weighting
         *
         * @author - Olivier Brouckaert
         * @param - float $weighting - question weighting
         */
        function updateWeighting($weighting) {
            $this->weighting = $weighting;
        }

        /**
         * changes the question position
         *
         * @author - Olivier Brouckaert
         * @param - integer $position - question position
         */
        function updatePosition($position) {
            $this->position = $position;
        }

        /**
         * changes the answer type. If the user changes the type from "unique answer" to "multiple answers"
         * (or conversely) answers are not deleted, otherwise yes
         *
         * @author - Olivier Brouckaert
         * @param - integer $type - answer type
         */
        function updateType($type) {
            // if we really change the type
            if ($type != $this->type) {
                // if we don't change from "unique answer" to "multiple answers" (or conversely)
                if (!in_array($this->type, array(UNIQUE_ANSWER, MULTIPLE_ANSWER)) || !in_array($type, array(UNIQUE_ANSWER, MULTIPLE_ANSWER))) {
                    // removes old answers
                    Database::get()->query("DELETE FROM `exercise_answer` WHERE question_id = ?d", $this->id);
                }
                $this->type = $type;
            }
        }

        /**
         * changes the question difficulty
         */
        function updateDifficulty($difficulty) {
            $this->difficulty = $difficulty;
        }
        /**
         * changes the question category
         */
        function updateCategory($category_id) {
            $this->category = $category_id;
        }
        /**
         * adds a picture to the question
         *
         * @author - Olivier Brouckaert
         * @param - string $Picture - temporary path of the picture to upload
         * @return - boolean - true if uploaded, otherwise false
         */
        function uploadPicture($picture, $type) {
            global $picturePath;

            if ($this->id) {
                $filename_final = $picturePath . '/quiz-' . $this->id;
                if (!copy_resized_image($picture, $type, 760, 512, $filename_final)) {
                    return false;
                } else {
                    return true;
                }
            }
            return false;
        }

        /**
         * deletes the picture
         *
         * @author - Olivier Brouckaert
         * @return - boolean - true if removed, otherwise false
         */
        function removePicture() {
            global $picturePath;

            // if the question has got an ID and if the picture exists
            if ($this->id && file_exists($picturePath . '/quiz-' . $this->id)) {
                return unlink($picturePath . '/quiz-' . $this->id) ? true : false;
            }

            return false;
        }

        /**
         * imports a picture from another question
         *
         * @author - Olivier Brouckaert
         * @param - integer $questionId - ID of the original question
         * @return - boolean - true if copied, otherwise false
         */
        function importPicture($questionId) {
            global $picturePath;

            // if the question has got an ID and if the picture exists
            if ($this->id && file_exists($picturePath . '/quiz-' . $questionId)) {
                return copy($picturePath . '/quiz-' . $questionId, $picturePath . '/quiz-' . $this->id) ? true : false;
            }

            return false;
        }

        /**
         * exports a picture to another question
         *
         * @author - Olivier Brouckaert
         * @param - integer $questionId - ID of the target question
         * @return - boolean - true if copied, otherwise false
         */
        function exportPicture($questionId) {
            global $picturePath;

            // if the question has got an ID and if the picture exists
            if ($this->id && file_exists($picturePath . '/quiz-' . $this->id)) {
                return copy($picturePath . '/quiz-' . $this->id, $picturePath . '/quiz-' . $questionId) ? true : false;
            }

            return false;
        }

        /**
         * updates the question in the data base
         * if an exercise ID is provided, we add that exercise ID into the exercise list
         *
         * @author - Olivier Brouckaert
         * @param - integer $exerciseId - exercise ID if saving in an exercise
         */
        function save($exerciseId = null) {
            global $course_id;

            $id = $this->id;
            $question = $this->question;
            $description = $this->description;
            $weighting = $this->weighting;
            $type = $this->type;
            $difficulty = $this->difficulty;
            $category = $this->category;

            // question already exists
            if ($id) {
                Database::get()->query("UPDATE `exercise_question` SET question = ?s, description = ?s,
                    weight = ?f, type = ?d, difficulty = ?d, category = ?d
                    WHERE course_id = $course_id AND id='$id'", $question, $description, $weighting, $type, $difficulty, $category);
            }
            // creates a new question
            else {
                $this->id = Database::get()->query("INSERT INTO `exercise_question` (course_id, question, description, weight, type, difficulty, category)
                VALUES (?d, ?s, ?s, ?f, ?d, ?d, ?d)", $course_id, $question, $description, $weighting, $type, $difficulty, $category)->lastInsertID;
            }

            // if the question is created in an exercise
            if ($exerciseId) {
                // adds the exercise into the exercise list of this question
                $exercise = new Exercise();
                $exercise->read($exerciseId);
                $exercise->addToList($this->id);
                $exercise->save();
            }
        }

        /**
         * removes an exercise from the exercise list
         *
         * @author - Olivier Brouckaert
         * @param - integer $exerciseId - exercise ID
         * @return - boolean - true if removed, otherwise false
         */
        function removeFromList($exerciseId) {
            $id = $this->id;

            // searches the position of the exercise ID in the list
            $pos = array_search($exerciseId, $this->exerciseList);

            // exercise not found
            if ($pos === false) {
                return false;
            } else {
                // deletes the position in the array containing the wanted exercise ID
                unset($this->exerciseList[$pos]);
                Database::get()->query("DELETE FROM `exercise_with_questions` WHERE question_id = ?d AND exercise_id = ?d", $id, $exerciseId);
                return true;
            }
        }


        /**
         * @brief delete random questions
         * @param $id
         * @param $exerciseId
         */
        function removeRandomQuestionsFromList($id, $exerciseId) {

            Database::get()->query("DELETE FROM `exercise_with_questions` WHERE id = ?d AND exercise_id = ?d", $id, $exerciseId);

        }

        /**
         * deletes a question from the database
         * the parameter tells if the question is removed from all exercises (value = 0),
         * or just from one exercise (value = exercise ID)
         *
         * @author - Olivier Brouckaert
         * @param - integer $deleteFromEx - exercise ID if the question is only removed from one exercise
         */
        function delete($deleteFromEx = 0) {
            global $course_id;

            $id = $this->id;

            // if the question must be removed from all exercises
            //if($deleteFromEx === 0)
            if (!$deleteFromEx) {
                Database::get()->query("DELETE FROM `exercise_with_questions` WHERE question_id = ?d", $id);
                Database::get()->query("DELETE FROM `exercise_question` WHERE course_id = ?d AND id = ?d", $course_id, $id);
                Database::get()->query("DELETE FROM `exercise_answer` WHERE question_id = ?d", $id);
                $this->removePicture();
                // resets the object
                $this->__construct();
            }
            // just removes the exercise from the list
            else {
                $this->removeFromList($deleteFromEx);
            }
        }
        /**
         * Getting exercise answers
         */
        function get_answers_record($eurid) {
            $type = $this->type;
            $question_id = $this->id;
            $choice = null;
            $answers = Database::get()->queryArray("SELECT * FROM exercise_answer_record WHERE eurid = ?d AND question_id = ?d", $eurid, $question_id);
            if ($answers) {
                $i = 1;
                foreach ($answers as $row) {
                    if ($type == UNIQUE_ANSWER || $type == TRUE_FALSE) {
                        $choice = $row->answer_id;
                    } elseif ($type == MULTIPLE_ANSWER) {
                        $choice[$row->answer_id] = 1;
                    } elseif ($type == FREE_TEXT) {
                        $choice = $row->answer;
                    } elseif ($type == FILL_IN_BLANKS || $type == FILL_IN_BLANKS_TOLERANT) {
                        $choice[$row->answer_id] = $row->answer;
                    } elseif ($type == MATCHING) {
                        $choice[$row->answer] = $row->answer_id;
                    }
                    $i++;
                }
                return $choice;
            }
        }
        /**
         * duplicates the question
         *
         * @author - Olivier Brouckaert
         * @return - integer - ID of the new question
         */
        function duplicate() {
            global $course_id;

            $question = $this->question;
            $description = $this->description;
            $weighting = $this->weighting;
            $type = $this->type;
            $difficulty = $this->difficulty;
            $category = $this->category;

            $id = Database::get()->query("INSERT INTO `exercise_question` (course_id, question, description, weight, `type`, difficulty, category)
                        VALUES (?d, ?s, ?s, ?f, ?d, ?d, ?d)", $course_id, $question, $description, $weighting, $type, $difficulty, $category)->lastInsertID;

            // duplicates the picture
            $this->exportPicture($id);

            return $id;
        }
        /**
         *
         * Calculate Question success rate
         */
        function successRate($exerciseId = NULL) {
            $id = $this->id;
            $type = $this->type;
            $objAnswerTmp = new Answer($id);
            $nbrAnswers = $objAnswerTmp->selectNbrAnswers();
            $q_correct_answers_sql = '';
            $q_incorrect_answers_sql = '';
            $extra_sql = '';
            $query_vars = array($id, ATTEMPT_COMPLETED);
            if(isset($exerciseId)) {
                $extra_sql = " AND b.eid = ?d";
                $query_vars[] = $exerciseId;
            }
            $total_answer_attempts = Database::get()->querySingle("SELECT COUNT(DISTINCT a.eurid) AS count
                    FROM exercise_answer_record a, exercise_user_record b
                    WHERE a.eurid = b.eurid AND a.question_id = ?d AND b.attempt_status=?d$extra_sql", $query_vars)->count;

            //BUILDING CORRECT ANSWER QUERY BASED ON QUESTION TYPE
            if($type == UNIQUE_ANSWER || $type == MULTIPLE_ANSWER || $type == TRUE_FALSE){ //works wrong for MULTIPLE_ANSWER
                $i=1;
                for ($answerId = 1; $answerId <= $nbrAnswers; $answerId++) {
                    if ($objAnswerTmp->isCorrect($answerId)) {
                        $q_correct_answers_sql .= ($i!=1) ? ' OR ' : '';
                        $q_correct_answers_sql .= 'a.answer_id = '.$objAnswerTmp->selectPosition($answerId);
                        $q_incorrect_answers_sql .= ($i!=1) ? ' AND ' : '';
                        $q_incorrect_answers_sql .= 'a.answer_id != '.$objAnswerTmp->selectPosition($answerId);
                        $i++;
                    }
                }
                $q_correct_answers_cnt = $i-1;
            } elseif ($type == MATCHING) { // to be done
                    $i = 1;
                    for ($answerId = 1; $answerId <= $nbrAnswers; $answerId++) {
                        //must get answer id ONLY where correct value existS
                        $answerCorrect = $objAnswerTmp->isCorrect($answerId);
                        if ($answerCorrect) {
                            $q_correct_answers_sql .= ($i!=1) ? " OR " : "";
                            $q_correct_answers_sql .= "(a.answer = $answerId AND a.answer_id = $answerCorrect)";
                            $q_incorrect_answers_sql .= ($i!=1) ? " OR " : "";
                            $q_incorrect_answers_sql .= "(a.answer = $answerId AND a.answer_id != $answerCorrect)";
                            $i++;
                        }
                    }
                    $q_correct_answers_cnt = $i-1;
            } elseif ($type == FILL_IN_BLANKS || $type == FILL_IN_BLANKS_TOLERANT) { // Works Great
               $answer_field = $objAnswerTmp->selectAnswer($nbrAnswers);
               list($answer, $answerWeighting) = $this::blanksSplitAnswer($answer_field);
               $blanks = $this::getBlanks($answer);
               $i = 1;
               $sql_binary_comparison = $type == FILL_IN_BLANKS ? 'BINARY ' : '';
               foreach ($blanks as $answers) {
                    $correct_answers = preg_split('/\s*,\s*/', $answers);
                    $j = 1;
                    $q_correct_answers_sql .= ($i!=1) ? ' OR ' : '';
                    $q_incorrect_answers_sql .= ($i!=1) ? ' OR ' : '';
                    foreach ($correct_answers as $value) {
                        $placeholder = "?s";
                        $q_correct_answers_sql .= ($j!=1) ? ' OR ' : '';
                        $q_correct_answers_sql .= "(a.answer = $sql_binary_comparison $placeholder AND a.answer_id = $i)";
                        $query_vars[] = $value;
                        $q_incorrect_answers_sql .= ($j!=1) ? ' AND ' : '';
                        $q_incorrect_answers_sql .= "(a.answer != $sql_binary_comparison $placeholder AND a.answer_id = $i)";
                        $query_vars[] = $value;
                        $j++;
                    }
                    $i++;
               }
               $q_correct_answers_cnt = $i - 1;
            }
            //FIND CORRECT ANSWER ATTEMPTS
            if ($type == FREE_TEXT) {
                // This query gets answers which where graded with question maximum grade
                $correct_answer_attempts = Database::get()->querySingle("SELECT COUNT(DISTINCT a.eurid) AS count
                        FROM exercise_answer_record a, exercise_user_record b, exercise_question c
                        WHERE a.eurid = b.eurid AND a.question_id = c.id AND a.weight=c.weight AND a.question_id = ?d AND b.attempt_status=?d$extra_sql", $query_vars)->count;
            } else {
                // One Query to Rule Them All (except free text questions)
                // This query groups attempts and counts correct and incorrect answers
                // then counts attempts where (correct answers == total anticipated correct attempts)
                // and (incorrect answers == 0) (this control is necessary mostly in cases of MULTIPLE ANSWER type)
                    if ($q_correct_answers_cnt > 0) {
                        $correct_answer_attempts = Database::get()->querySingle("
                            SELECT COUNT(*) AS counter FROM (
                                SELECT a.eurid,
                                SUM($q_correct_answers_sql) as correct_answer_cnt,
                                SUM($q_incorrect_answers_sql) as incorrect_answer_cnt
                                FROM exercise_answer_record a, exercise_user_record b
                                WHERE a.eurid = b.eurid AND a.question_id = ?d AND b.attempt_status = ?d$extra_sql
                                GROUP BY(a.eurid) HAVING correct_answer_cnt = ?d AND incorrect_answer_cnt = 0
                            ) AS sub", $query_vars, $q_correct_answers_cnt)->counter;
                    } else {
                        $correct_answer_attempts = 0;
                    }
                }

            if ($total_answer_attempts>0) {
                $successRate = round($correct_answer_attempts/$total_answer_attempts*100, 2);
            } else {
                $successRate = NULL;
            }
            return $successRate;
        }


        /**
         * @brief Calculate success rate only in FREE TEXT type questions
         * @param type $exerciseId
         */
        function successRateInQuestion() {

            $id = $this->id;
            $question_weight = $this->weighting;

            $answers_weight = Database::get()->querySingle("SELECT AVG(weight) AS weight FROM exercise_answer_record
                                                            WHERE question_id = ?d", $id)->weight;
            $successRate = round(($answers_weight/$question_weight)*100, 2);

            return $successRate;
        }


        /**
         * Split answer string from weighting string for fill-in-blanks answers
         */
        static function blanksSplitAnswer($str) {
            $parts = explode('::', $str);
            $answerWeighting = array_pop($parts);
            $answer = implode('::', $parts);
            return array($answer, $answerWeighting);
        }

        /**
         * Get array of answers from blanks in fill-in-blanks answers
         */
        static function getBlanks($string) {
            $blanks = Array();

            // remove math tags [m]...[/m]
            $temp = preg_replace('/\[m\].*?\[\\m\]/', '', $string);

            // the loop will stop at the end of the string
            while (1) {
                if (($pos = strpos($temp, '[')) === false) {
                    break;
                }
                // remove characters till '['
                $temp = substr($temp, $pos + 1);

                // quit the loop if there are no more blanks
                if (($pos = strpos($temp, ']')) === false) {
                    break;
                }
                // store the found blank answer
                $blanks[] = substr($temp, 0, $pos);

                // remove the character ']'
                $temp = substr($temp, $pos + 1);
            }
            return $blanks;
        }
    }

}
