<?php
// 'lesson' object
class EGNSubstitution{

    // database connection and table name
    private $conn;
    private $table_name = "substitute";
    private $table_time = "lesson_time";
    private $table_subjects = "subjects";
    private $table_students = "students";
    private $table_teachers = "teachers";
    private $table_course_member = "course_member";

    // object properties
    public $id;
    public $error;
    public $date;
    public $year;
    public $class;
    public $course;
    public $time;
    public $subject_old;
    public $teacher_old;
    public $room_old;
    public $subject;
    public $teacher;
    public $room;
    public $info;

    public $substitutions;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Create

    public function create(){
        
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    error = :error,
                    date = :date,
                    year = :year,
                    class = :class,
                    course = :course,
                    time = :time,
                    subject_old = :sunject_old,
                    teacher_old = :teacher_old,
                    room_old = :room_old,
                    subject = :subject,
                    teacher = :teacher,
                    room = :room,
                    info = :info";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->error=htmlspecialchars(strip_tags($this->error));
        $this->date=htmlspecialchars(strip_tags($this->date));
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->class=htmlspecialchars(strip_tags($this->class));
        $this->course=htmlspecialchars(strip_tags($this->course));
        $this->time=htmlspecialchars(strip_tags($this->time));
        $this->subject_old=htmlspecialchars(strip_tags($this->subject_old));
        $this->teacher_old=htmlspecialchars(strip_tags($this->teacher_old));
        $this->room_old=htmlspecialchars(strip_tags($this->room_old));
        $this->subject=htmlspecialchars(strip_tags($this->subject));
        $this->teacher=htmlspecialchars(strip_tags($this->teacher));
        $this->room=htmlspecialchars(strip_tags($this->room));
        $this->info=htmlspecialchars(strip_tags($this->info));

        // bind the values
        $stmt->bindParam(':error', $this->error);
        $temptime = strtotime($this->date);
        $stmt->bindParam(':date', date('Y-m-d', $temptime));
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':class', $this->class);
        $stmt->bindParam(':course', $this->course);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':subject_old', $this->subject_old);
        $stmt->bindParam(':teacher_old', $this->teacher_old);
        $stmt->bindParam(':room_old', $this->room_old);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':teacher', $this->teacher);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':info', $this->info);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // CRUD -> Read

    // get complete substitution
    public function getAllSubstitution(){

        // Create Query
        $query = '  SELECT
                        id, error, date, year, class, course, time, subject_old, teacher_old, room_old, subject, teacher, room, info
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        date >= :starttime AND date <= :endtime
                    ORDER BY
						date ASC, year ASC, time ASC';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // bind the values
        $starttime = strtotime('now');
        $endtime = strtotime('+2 days');
        $stmt->bindParam(':starttime', date('Y-m-d', $starttime));
        $stmt->bindParam(':endtime', date('Y-m-d', $endtime));

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // get record details / values
        $this->substitutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return true;
    }

}