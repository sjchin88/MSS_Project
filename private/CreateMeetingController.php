<?php
require_once "private/Autoload.php";
require_once "private/Room.php";
require_once "private/Meeting.php";

class CreateMeetingController
{
    private $connection;

    function __construct($connection){
			$this->connection = $connection; 
	}
	
	public function addAttendee()
	{
	    $user = $_POST['usernameBox'];
	    $id = $_POST['meeting_id'];
	    
	    try 
	    {
    	    $query = "select * from MEETING where MEETING_ID = '$id'";
    	    $stmt = $this->connection->query($query);
    	    $result = $stmt->fetch_assoc();
	    }
	    catch(SQLException $e)
	    {
	        echo "Failed to check meeting ID for validity.";
	    }
	    
	    if($result == null)
	    {
	        echo "Meeting does not exist.\n Please see details on the View Meeting page to find the unique ID for the meeting.";
	        return;
	    }
	    else
	    {
	        try 
	        {
	            $query = "insert into ATTENDANCE (ATTENDEE, MEETING_ID) values ('$user', $id)";
	            $stmt = $this->connection->query($query);
	            echo "$user has been added to the meeting!";
	        }
	        catch (SQLException $e)
	        {
	            echo "Failed to enter $user into the meeting.";
	        }
	    }

	}
	
	public function removeAttendee()
	{
	    echo $_POST['usernameBox'];
	    echo " removed.";
	}
	
	public function getMeeting($date, $time)
    {
        $user = $_SESSION['username'];
        $meet_time = date('Y-m-d H:i:s', strtotime("$date $time"));
        
        try
        {
        $query = "select IS_ADMIN from ACCOUNT where USERNAME = '$user'";
        $result = $this->connection->query($query);
        $stmt = $result->fetch_assoc();
        $status = $stmt['IS_ADMIN'];
        }
        catch(SQLException $e)
        {
            echo "Admin status could not be retrieved.";
        }
        
        if($status == 1)
        {
            $query = "select * from MEETING where START_TIME = '$meet_time'";
        }
        else
        {
            $query = "select * from MEETING where START_TIME = '$meet_time' and MEETING_ID in (select MEETING_ID from ATTENDANCE where ATTENDEE = '$user')";
        }
        
        try 
        {
            $result = $this->connection->query($query);
            $val = $result->fetch_assoc() or $this->connection->error;

            if($val != null && $val['MEETING_ID'] != null)
            {
                echo "<input name='submit' type='submit' id='$meet_time' value='$meet_time'>";
            }
        }
        catch (SQLException $e)
        {
            echo "Meetings could not be retrieved.";
        }
    }
    
    public function getMeetingDetails($meet_time)
    {
        $user = $_SESSION['username'];

        try
        {
        $query = "select IS_ADMIN from ACCOUNT where USERNAME = '$user'";
        $result = $this->connection->query($query);
        $stmt = $result->fetch_assoc();
        $status = $stmt['IS_ADMIN'];
        }
        catch(SQLException $e)
        {
            echo "Admin status could not be retrieved.";
        }
        
        if($status == 1)
        {
            $query = "select * from MEETING where START_TIME = '$meet_time'";
        }
        else
        {
            $query = "select * from MEETING where START_TIME = '$meet_time' and MEETING_ID in (select MEETING_ID from ATTENDANCE where ATTENDEE = '$user')";
        }
        
        try 
        {
            $result = $this->connection->query($query);
            //$val = $result->fetch_assoc() or $this->connection->error;

                while($val = $result->fetch_assoc())
                {
                    echo "  <div class='meeting_info'>";
                    echo "      <h1>$val[MEETING_NAME]</h1></br>";
                    echo "          <p>";
                    echo "          <strong>Meeting ID: </strong>$val[MEETING_ID]</br>";
                    echo "          <strong>Starting time: </strong>$val[START_TIME]</br>";
                    echo "          <strong>Ending time: </strong>$val[END_TIME]</br>";
                    echo "          <strong>Organizer: </strong>$val[ORGANIZER]</br>";
                    echo "          <strong>Room ID: </strong>$val[ROOM_ID]</br>";
                    echo "          </p>";
                    echo "  </div>";
                }
            
        }
        catch (SQLException $e)
        {
            echo "Meetings could not be retrieved.";
        }
    }
    
    public function getMeetingWithRoom($date, $time, $room)
    {
        $meet_time = date('Y-m-d H:i:s', strtotime("$date $time"));
        $query = "select * from MEETING where START_TIME = '$meet_time' and ROOM_ID = '$room'";
        $stmt = $this->connection->query($query) or die($this->connection->error);
        $result = $stmt->fetch_assoc();
        
        return $result;
    }
	
    public function createMeeting()
    {
        $title = $_POST['meetingTitle'];
        $start = $_POST['meetingTime'];
        $date = $_POST['meetingDate'];
        $room = $_POST['meetingRoom'];
        $user = $_SESSION['username'];
        
        echo $room;
        $room_obj = new Room($this->connection);
        $is_special = $room_obj->isSpecial($room);

        if ($is_special)
        {
            // Confirm payment
        }
        
        if ($this->getMeetingWithRoom($date, $start, $room) > 0)
        {
            echo "Room has already been booked for this timeslot. Please try again.";
        }
        
        // Format start time to fit datetime type
        $start_time = date('Y-m-d H:i:s', strtotime("$date $start"));

        // Quick code to calculate end time by adding start time 
        $end = strtotime($start_time) + 60*60;
        $end = date('H:i:s', $end);
        
        // Format end time to fit datetiime type
        $end_time = date('Y-m-d H:i:s', strtotime("$date $end"));


        //take out Date - CSJ - 2021-08-21
        if(!is_null($title) AND !is_null($start_time))
        {
            if($start_time > date('Y-m-d H:i:s', time()))
            {
                $meeting = new Meeting();

                $query = "insert into MEETING (Meeting_name, Start_time, End_time, Organizer, Room_ID) values (?,?,?,?,?)";
				$stmt = $this->connection->prepare($query);
				
				$stmt->bind_param("sssss", $title, $start_time, $end_time, $user, $room);
				
				if($stmt->execute())
				{
                    //echo "Safe!";
                    try
                    {
                        $query = "select MEETING_ID from MEETING where START_TIME = '$start_time' and ROOM_ID = '$room'";
                        $stmt = $this->connection->query($query);
                        $rows = $stmt->fetch_assoc();
                        $meeting_id = $rows['MEETING_ID'];
                    }
                    catch(SQLException $e)
                    {
                        echo "Failed to retrieve Meeting ID for the newly created meeting.";
                    }
                    
                    try
                    {
                        $query = "insert into ATTENDANCE (ATTENDEE, MEETING_ID) values (?, ?)";
                        $stmt = $this->connection->prepare($query);
                        $stmt->bind_param("ss", $user, $meeting_id);
                        $stmt->execute();
                    }
                    catch(SQLException $e)
                    {
                        echo "Failed to add current user as an attendee to the newly created meeting.";
                    }
                }
                else
                {
                    echo "Failed to execute";
                }
            }
            else
            {
                echo "Invalid date/time value"; // Show date and time was invalid
                echo $start_time;
                echo date("Y:m:d H:i:s", time());
            }
        }
    }

    public function retrieveRooms()
    {
        $room = new Room($this->connection);
        return $room->getRooms();
    }
    
    
}