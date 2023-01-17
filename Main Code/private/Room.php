<?php
require "private/Autoload.php";
require "private/RoomObject.php";

class Room
{
    private $connection;
    private $roominfo;
    
    //Constructor
	function __construct($connection){
			$this->connection = $connection; 
	}
	
    public function getRooms()
    {
        
        $stm = $this->connection->prepare("select ROOM_ID from ROOM");
        try
        {
            $test = $this->connection->query("select ROOM_ID from ROOM");
			while($result = $test->fetch_assoc())
			{
			    echo "<option value='$result[ROOM_ID]'>$result[ROOM_ID]</option>";
			}
        }
        catch(Exception $e)
        {
            echo "Error - Room retrieval failed.";
            return 0;
        }
        return 1;
    }
    
    public function isSpecial($room_number)
    {
        try
        {
            $stmt = $this->connection->query("select IS_SPECIAL from ROOM where ROOM_ID = '$room_number'");
            $result = $stmt->fetch_assoc();
            return $result['IS_SPECIAL'];
        }
        catch(Exception $e)
        {
            echo "Failed to fetch room special status.";
            return -1;
        }
    }

    /*Function to view room*/
    function view_room(){
        $query = "SELECT ROOM_ID, IS_SPECIAL, CAPACITY, LOCATION from ROOM";
        $stmt= $this->connection->prepare($query);
        $stmt->execute();
        $stmt->store_result();
            
        if($stmt->num_rows >0){
            $stmt->bind_result($room_id, $is_special, $capacity, $location); 
            while ($stmt->fetch()){
                echo "<tr><td>".$room_id."</td><td>".$is_special."</td>
                <td>".$capacity."</td><td>".$location."</td><tr>";
            }
        }
    }

    /*Function to view room*/
    function add_room($room_object){
        $query = "INSERT INTO ROOM (ROOM_ID, IS_SPECIAL, CAPACITY, LOCATION) values (?,?,?,?)";
        $stmt= $this->connection->prepare($query);
        $stmt->bind_param('siis',$room_object->room_id, $room_object->is_special, $room_object->capacity, $room_object->location);
        $stmt->execute();
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete_room($room_id){
        $query = "DELETE FROM ROOM WHERE ROOM_ID = ?";
        $stmt= $this->connection->prepare($query);
        $stmt->bind_param("i", $room_id);
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
}

