<?php
class USER
{
	private $db;

	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
		public function testinput($data)
	{
		$data = htmlspecialchars($data);
		$data=stripslashes($data);
		$data=trim($data);
		return $data;

	}

	
	public function add_user($email,$password)
	{
		try
		{
			$new_password = MD5($password);
			$stmt2=$this->db->prepare("SELECT * FROM users_table WHERE email=:em");
			$stmt2->bindParam(":em",$email);
			$stmt2->execute();
			if($stmt2->rowCount()>0)
			{
				?>
				<div class="animated swing alert alert-danger alert-dismissal w3-win-phone-red w3-padding-16">
				You are Already Registered  <?php echo $email  ?> With This Email.
				</div>
				<?php
			}
			else
			{
			$stmt = $this->db->prepare("INSERT INTO users_table(email,password)
		                                               VALUES(:email,:pass)");

			$stmt->bindParam(":email", $email);
			
			$stmt->bindParam(":pass",$new_password);
			$stmt->execute();

			return $stmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function add_hostel($hostel_name,$no_rums,$booked_rums,$gender)
	{
		try
		{
			
			//$new_password = MD5($reg_no);
			$stmt2=$this->db->prepare("SELECT * FROM hostels WHERE hostel_name=:nam");
			$stmt2->bindParam(":nam",$hostel_name);
			$stmt2->execute();
			if($stmt2->rowCount()>0)
			{
				?>
				<div class="animated swing alert alert-danger alert-dismissal w3-win-phone-red w3-padding-16">
				 Hostel "  <?php echo $hostel_name  ?>  " is already added
				</div>
				<?php
			}
			else
			{
			$stmt = $this->db->prepare("INSERT INTO hostels(hostel_name,no_rums,booked_rums,gender)
		                                               VALUES(:hostel_name,:no_rums,:booked_rums,:gender)");

			$stmt->bindParam(":hostel_name", $hostel_name);
			$stmt->bindParam(":no_rums", $no_rums);
			$stmt->bindParam(":booked_rums",$booked_rums);
			$stmt->bindParam(":gender",$gender);
			$stmt->execute();
			
            for($i=1;$i<=$no_rums;$i++)
			{
				$status="vacant";
				$rum_name=substr($hostel_name,0,3) . $i;
				$stmt4=$this->db->prepare("INSERT INTO rooms(room_name,hostel,status)
										VALUES(:rum_name,:hostel,:status)");
				$stmt4->bindParam(":rum_name",$rum_name);
				$stmt4->bindParam(":hostel",$hostel_name);
				$stmt4->bindParam(":status",$status);
				$stmt4->execute();
				
			}
			
			return $stmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	

	public function student_register($email,$f_name,$sur_name,$l_name,$phone)
	{
		try
		{
			//$new_password = MD5($pass);
			$stmt2=$this->db->prepare("SELECT * FROM student_details WHERE email=:em");
			$stmt2->bindParam(":em",$email);
			$stmt2->execute();
			if($stmt2->rowCount()>0)
			{
				?>
				<div class="animated swing alert alert-danger alert-dismissal w3-win-phone-red w3-padding-16">
				You Are Already Registered With "<?php echo $email  ?>"  Email.
				</div>
				<?php
			}
			else
			{
			$stmt = $this->db->prepare("INSERT INTO student_details(email,f_name,sur_name,l_name,phone)
		                                               VALUES(:email,:f_name,:sur_name,:l_name,:phone)");

			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":f_name", $f_name);
			$stmt->bindparam(":sur_name", $sur_name);
			$stmt->bindparam(":l_name", $l_name);
			$stmt->bindParam(":phone",$phone);
			
            


			$stmt->execute();

			return $stmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function add__newCourse($course_name,$course_code)
	{
		try
		{
			//$new_password = MD5($pass);
			$stmt2=$this->db->prepare("SELECT * FROM courses WHERE course_name=:name AND course_code=:code");
			$stmt2->bindParam(":name",$course_name);
			$stmt2->bindParam(":code",$course_code);
			$stmt2->execute();
			if($stmt2->rowCount()>0)
			{
				?>
				    <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong><?php echo $course_name  ?> Course Code Or Name Already Exists</strong>
  </div>
				
				<?php
			}
			else
			{
			$stmt = $this->db->prepare("INSERT INTO courses(course_name,course_code)
		                                               VALUES(:course_name,:course_code)");

			$stmt->bindparam(":course_name", $course_name);
			$stmt->bindparam(":course_code", $course_code);
			
			$stmt->execute();

			return $stmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function login($umail,$upass)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users_table WHERE email=:ema LIMIT 1");
		    $stmt->bindParam(":ema",$umail);
			$stmt->execute();
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				if($userRow['password']==MD5($upass))
				{
					$_SESSION['user_session'] = $userRow['email'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>
