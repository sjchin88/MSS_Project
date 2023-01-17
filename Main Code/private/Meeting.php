<?php
require "private/Autoload.php";

class Meeting
{
    private $connection;

    public function Create($id, $title, $start_time, $end_time, $date, $user, $room, $connection)
    {
        // Quick code to calculate end time by adding start time (maybe move this to outside this function)
        $end = strtotime($time) + 60*60;
        $end_time = date('H:i', $end);
        $this->connection = $connection;

        // Set up the query, bind all the values
        $this->connection->query("insert into MEETING (Meeting_id, Meeting_name, Start_time, End_time, Organizer, Room_ID) values ($id, $title, $start_time, $end_time, $user, $room)");
        //$stm->bindValue(1, $id);
        //$stm->bindValue(2, $title);
        //$stm->bindValue(3, $time);
        //$stm->bindValue(4, $end_time);
        //$stm->bindValue(5, $date); 
        //$stm->bindValue(5, $user);
        //$stm->bindValue(6, $room);
        
        

        // Check to see if we successfully added it
        $check = $this->connection->query("select ALL from MEETING where Meeting_id = $id");
        $result = $check->fetch_assoc();
        $result_count = count($result);
        echo $result;

        if($result_count > 0)
        {
            return true; // If our meeting with the unique ID is now in the database, return true to confirm success
        }
        return false; // insertion failed!
    }
}