<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class projects extends db
{
	function projects()
	{
		$this->db();
	}

	function add_project($user_id, $name, $comments, $acl_read, $acl_write)
	{
		$project_id = $this->nextid("pmProjects");
		if ($project_id > 0)
		{
			$sql = "INSERT INTO pmProjects (id, user_id, name, acl_read, acl_write, comments, creation_time) ";
			$sql .= "VALUES ('$project_id', '$user_id', '$name', '$acl_read', '$acl_write', '$comments', '".time()."')";
			return $this->query($sql);
		}
		return false;
	}

	function update_project($project_id, $name, $comments)
	{
		$sql = "UPDATE pmProjects SET name='$name', comments='$comments' WHERE id='$project_id'";
		return $this->query($sql);
	}

	function get_project($project_id)
	{
		$sql = "SELECT * FROM pmProjects WHERE id='$project_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function delete_project($project_id)
	{
		$sql = "DELETE FROM pmHours WHERE project_id='$project_id'";
		if ($this->query($sql))
		{
			$sql = "DELETE FROM pmProjects WHERE id='$project_id'";
			return $this->query($sql);
		}else
		{
			return false;
		}
	}

	function get_projects($sort='name', $direction='ASC')
	{
		$sql = "SELECT * from pmProjects ORDER BY $sort $direction";
		$this->query($sql);
		return $this->num_rows();
	}

	function add_hours($project_id, $user_id, $start_time, $end_time, $break_time, $comments)
	{
		$id = $this->nextid("pmHours");
		if ($id > 0)
		{
			$sql  = "INSERT INTO pmHours (id, user_id, project_id, start_time, end_time, break_time, comments) ";
			$sql .= "VALUES ('$id', '$user_id', '$project_id', '$start_time', '$end_time', '$break_time', '$comments')";
			return $this->query($sql);
		}
		return false;
	}

	function check_hours($user_id, $start_time, $end_time)
	{
		$sql = "SELECT * FROM pmHours WHERE user_id='$user_id' AND start_time <= '$start_time' AND end_time > '$start_time'";
		$this->query($sql);
		if ($this->next_record())
		{
			return false;
		}

		$sql = "SELECT * FROM pmHours WHERE user_id='$user_id' AND start_time <= '$end_time' AND end_time > '$end_time'";
		$this->query($sql);
		if($this->next_record())
		{
			return false;
		}
		return true;
	}

	function delete_hours($hours_id)
	{
		if ($hours_id > 0)
		{
			$sql = "DELETE FROM pmHours WHERE id='$hours_id'";
			return $this->query($sql);
		}else
		{
			return false;
		}
	}

	function get_project_hours($project_id, $start_time=0, $end_time=0)
	{
		if ($start_time == 0 && $end_time == 0)
		{
			$sql = "SELECT * FROM pmHours WHERE project_id='$project_id' ORDER BY start_time ASC";
		}else
		{
			$sql = "SELECT * FROM pmHours WHERE project_id='$project_id' AND start_time >= '$start_time' AND start_time < '$end_time' ORDER BY start_time ASC";
		}
		$this->query($sql);
		return $this->num_rows();
	}

	function get_user_hours($user_id, $start_time=0, $end_time=0)
	{
		if ($start_time == 0 && $end_time == 0)
		{
			$sql = "SELECT pmHours.*,pmProjects.name AS project_name FROM pmHours LEFT JOIN pmProjects ON (pmHours.project_id = pmProjects.id) WHERE pmHours.user_id='$user_id' ORDER BY start_time ASC";
		}else
		{
			$sql = "SELECT pmHours.*,pmProjects.name AS project_name FROM pmHours LEFT JOIN pmProjects ON (pmHours.project_id = pmProjects.id) WHERE pmHours.user_id='$user_id' AND pmHours.start_time >= '$start_time' AND pmHours.start_time < '$end_time' ORDER BY pmHours.start_time ASC";
		}
		$this->query($sql);
		return $this->num_rows();
	}

	function get_fees()
	{
		$sql = "SELECT * FROM pmFees";
		$this->query($sql);
		return $this->num_rows();
	}

	function get_fee($fee_id)
	{
		$sql = "SELECT * FROM pmFees WHERE id='$fee_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function add_fee($name, $value)
	{
		$id = $this->nextid("pmFees");
		if ($id > 0)
		{
			$sql = "INSERT INTO pmFees (id, name, value) VALUES ('$id', '$name', '$value')";
			return $this->query($sql);
		}
		return false;
	}

	function delete_fee($fee_id)
	{
		$sql = "DELETE FROM pmFees WHERE id='$fee_id'";
		return $this->query($sql);
	}

	function update_fee($fee_id, $name, $value)
	{
		$sql = "UPDATE pmFees SET name='$name', value='$value' WHERE id='$fee_id'";
		return $this->query($sql);
	}

	function get_materials()
	{
		$sql = "SELECT * FROM pmMaterials";
		$this->query($sql);
		return $this->num_rows();
	}

	function get_material($material_id)
	{
		$sql = "SELECT * FROM pmMaterials WHERE id='$material_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function add_material($name, $value, $description)
	{
		$id = $this->nextid("pmMaterials");
		if ($id > 0)
		{
			$sql = "INSERT INTO pmMaterials (id, name, value, description) VALUES ('$id', '$name', '$value', '$description')";
			return $this->query($sql);
		}
		return false;
	}

	function delete_material($material_id)
	{
		$sql = "DELETE FROM pmMaterials WHERE id='$material_id'";
		return $this->query($sql);
	}

	function update_material($material_id, $name, $value, $description)
	{
		$sql = "UPDATE pmMaterials SET name='$name', value='$value', description='$description' WHERE id='$material_id'";
		return $this->query($sql);
	}
}