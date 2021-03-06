<?php

    class   Course
    {
        private $title;
        private $id;

        function __construct($title, $id = Null )
        {
            $this->title = $title;
            $this->id = $id;
        }

        // getters and setters
        function setTitle($new_title)
        {
            $this->title = $new_title;
        }

        function getTitle()
        {
            return $this->title;
        }

        function getId()
        {
            return $this->id;
        }

        // CRUD
        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO course (title) VALUES ('{$this->getTitle()}');");

            $this->id = $GLOBALS['DB']->LastInsertId();
        }

        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query( "SELECT * FROM course;");
            $courses = array();
            foreach( $returned_courses as $course)
            {
                $new_title = $course['title'];
                $new_id = $course['id'];
                $new_course = new Course($new_title, $new_id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM course");
        }

        function update($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE course SET title = '{$new_title}';");

            $this->setTitle($new_title);

        }

        static function find($search_id)
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM course WHERE id = {$search_id};");
            $courses = array();
            $returned_courses = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($returned_courses as $course){
                $id = $course['id'];
                $title = $course['title'];
                $found_course = new Course($title, $id);
            }
            return $found_course;
        }

        function deleteCourse()
        {
            $GLOBALS['DB']->exec("DELETE FROM course WHERE id = '{$this->getId()}';");
        }

        function getStudents()
        {
            $students = $GLOBALS['DB']->query("SELECT student.* FROM
            course  JOIN student_course ON (course.id = student_course.course_id)
                    JOIN student ON ( student_course.student_id = student.id)
            WHERE course.id = {$this->getId()};");

            $return_students = array();
            foreach($students as $student){
                $id = $student['id'];
                $student_name = $student['student_name'];
                $instrument = $student['instrument'];
                $teacher_id = $student['teacher_id'];
                $notes = $student['notes'];
                $new_student = new Student($student_name, $instrument, $teacher_id, $id);
                $new_student->setNotes($notes);
                array_push($return_students, $new_student);
            }
            return $return_students;
        }







            // $returned_courses = $GLOBALS['DB']->query("SELECT course.* FROM
            // student JOIN student_course ON (student.id = student_course.student_id)
            //         JOIN course ON (student_course.course_id = course.id)
            // WHERE student.id = {$this->getId()};");
    }
 ?>
