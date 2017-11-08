<?php

	class MySQL{
    	var $query;
    	var $numrows;
  		var $ln;
  		var $db_name;

    	function db_connect($db_host, $db_user, $db_pass,$db_name){
      		//global $result;

      		$db_host1=$db_host;
      		$db_user1=$db_user;
      		$db_pass1=$db_pass;

      		$this->db_name=$db_name;
      		//$this->ln = mysql_pconnect($db_host1, $db_user1, $db_pass1) or die("cannot connect");
      		$this->ln = mysql_pconnect($db_host1, $db_user1, $db_pass1);
      		if ($this->ln==false){
      			?>
      			<script>location.href="oops.html";</script>
      			<?php

      		}
      		mysql_select_db($this->db_name,$this->ln);
  		}

  		function db_selectData($query){
        	//global $result;
			mysql_select_db($this->db_name,$this->ln);
      		$this->query=$query;
      		$result= mysql_query($this->query,$this->ln);
            $data=array();
      		while ($row = mysql_fetch_array($result)) {
   				$data[]=$row;

      		}
      		//return $result;
      		return $data;
		}

  		function db_deleteData($query){
      		//global $result;
			mysql_select_db($this->db_name,$this->ln);
     		$this->query=$query;
     		$result= mysql_query($this->query,$this->ln);
     		return $result;
  		}

  		function db_updateData($query){
      		//global $result;
         	mysql_select_db($this->db_name,$this->ln);
         	$this->query=$query;
         	$result= mysql_query($this->query,$this->ln);
         	return $result;
  		}

  		function db_insertData($query){
  			mysql_select_db($this->db_name,$this->ln);
      		$this->query=$query;
         	$result= mysql_query($this->query,$this->ln);
         	return ($result)? mysql_insert_id($this->ln): $result;
  		}

  		function db_countRows($res){
      		//global $result;
      		$this->numrows = mysql_num_rows($result);
      		return $this->numrows;
  		}

  		function close(){
      		mysql_close($this->ln);
  		}

  		function db_creteTable($query){
      		//global $result;
         	mysql_select_db($this->db_name,$this->ln);
         	$this->query=$query;
         	$result= mysql_query($this->query,$this->ln);
         	return $result;
  		}

  		function db_dropTable($query){
      		//global $result;
         	mysql_select_db($this->db_name,$this->ln);
         	$this->query=$query;
         	$result= mysql_query($this->query,$this->ln);
         	return $result;
  		}
	}

?>