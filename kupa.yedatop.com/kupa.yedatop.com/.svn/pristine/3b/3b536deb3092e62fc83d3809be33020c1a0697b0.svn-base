<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class contacts extends db
{
	function contacts()
	{
		$this->db();
	}

   	function authorise($authcode, $email, $user_id)
	{
		global $GO_SECURITY;

		$this->query("SELECT acl_id, id FROM users WHERE authcode='$authcode' AND email='$email'");
		if ($this->next_record())
		{
			$contact_id = $this->f("id");
			$acl_id = $this->f("acl_id");
			if (!$GO_SECURITY->user_in_acl($user_id, $acl_id))
			{
					if (!$GO_SECURITY->add_user_to_acl($user_id,$acl_id))
							return false;
			}
			$this->query("SELECT acl_id FROM users WHERE id='$user_id'");
			if ($this->next_record())
			{
					$acl_id = $this->f("acl_id");
					$GO_SECURITY->add_user_to_acl($contact_id,$acl_id);
			}
			return true;

		}else
		{
			return false;
		}
	}

	function get_contacts($user_id, $sort = "name", $direction = "ASC")
	{
		$this->query("SELECT * FROM contacts WHERE user_id='$user_id' ORDER BY ".$sort." ".$direction);
		return $this->num_rows();
	}

	function get_contacts_with_group($user_id)
	{
		$this->query("SELECT contacts.*, contact_groups.name AS group_name FROM contacts LEFT JOIN contact_groups ON (contacts.group_id=contact_groups.id) WHERE contacts.user_id='$user_id'");
		return $this->num_rows();
	}

    function get_contacts_group($user_id, $group_id, $sort = "name", $direction = "ASC")
    {
		$this->query("SELECT * FROM contacts WHERE group_id='$group_id' AND user_id='$user_id' ORDER BY ".$sort." ".$direction);
		return $this->num_rows();
   	}

	function get_contact($contact_id)
	{
		$this->query("SELECT * FROM contacts WHERE id='$contact_id'");
		if ($this->next_record())
		{
			return $this->Record;
		}
	}

	function add_contact($source_id, $user_id, $name, $email, $work_phone = "", $home_phone = "", $fax = "", $cellular = "", $country = "", $state = "", $city = "", $zip = "", $address = "", $company = "", $work_country = "", $work_state = "", $work_city = "", $work_zip = "", $work_address = "", $work_fax = "", $homepage = "", $department = "", $function = "", $comments="", $group_id = 0)
	{
		if ($group_id == '')
		{
			$group_id = 0;
		}

		$contact_id = $this->nextid("contacts");
		if ($contact_id > 0)
		{
			$sql = "INSERT INTO contacts ";
			$sql .= "(id, source_id, user_id, name, email, work_phone, home_phone, fax, cellular, country, state, city, zip, address, company, work_country, work_state, work_city, work_zip, work_address, work_fax, homepage, department, function, comments, group_id) VALUES ";
			$sql .= "('$contact_id', '$source_id', '$user_id', '$name', '$email', '$work_phone', '$home_phone', '$fax', '$cellular', '$country', '$state', '$city', '$zip', '$address', '$company', '$work_country', '$work_state', '$work_city', '$work_zip', '$work_address', '$work_fax', '$homepage', '$department', '$function','$comments', '$group_id')";
			$query = $this->query($sql);
			if ($this->affected_rows() > 0)
			{
				return true;
			}else
			{
				return false;
			}
		}else
		{
			return false;
		}
	}

	function update_contact($id, $name, $email, $work_phone, $home_phone, $fax, $cellular, $country, $state, $city, $zip, $address, $company, $work_country, $work_state, $work_city, $work_zip, $work_address, $work_fax, $homepage, $department, $function, $comments = '', $group_id='0')
	{
		$sql = "UPDATE contacts SET ";
		$sql .= "name='$name', email='$email', work_phone='$work_phone', home_phone='$home_phone', fax='$fax', cellular='$cellular', state='$state'";
		$sql .= ", country='$country', city='$city', zip='$zip', address='$address', company='$company', department='$department', function='$function', work_country='$work_country', work_state='$work_state', work_city='$work_city', work_zip='$work_zip', work_address='$work_address', work_fax='$work_fax', homepage='$homepage', comments='$comments', group_id='$group_id'";
		$sql .= " WHERE id='$id'";
		$query = $this->query($sql);
		if (isset($query))
		{
			return true;
		}else
		{
			return false;
		}
	}

	function delete_contact($id)
	{
		$sql = "DELETE FROM contacts WHERE id='$id'";
		$this->query($sql);
                return true;
	}

	function synchronise($contact_id, $user_id)
	{
			$sql = "SELECT * FROM users WHERE id='$user_id'";
			$this->query($sql);
			if ($this->next_record())
			{
					$sql  = "UPDATE contacts SET ";
					$sql .= "name='".$this->f("name")."', email='".$this->f("email")."', work_phone='".$this->f("work_phone")."', home_phone='".$this->f("home_phone")."', fax='".$this->f("fax")."', cellular='".$this->f("cellular")."', state='".$this->f("state")."'";
					$sql .= ", country='".$this->f("country")."', city='".$this->f("city")."', zip='".$this->f("zip")."', address='".$this->f("address")."', company='".$this->f("company")."', department='".$this->f("department")."', function='".$this->f("function")."'";
					$sql .= " WHERE id='$contact_id'";
					$this->query($sql);
			}
	}

	function user_is_contact($user_id, $id)
	{
			$sql = "SELECT id FROM contacts WHERE source_id='$id' AND user_id='$user_id'";
			$this->query($sql);
			if ($this->next_record())
			{
					return $this->f("id");
			}else
			{
					return false;
			}
	}

	function get_contact_id_by_email($email, $user_id)
	{
		$sql = "SELECT id FROM contacts WHERE email='$email' AND user_id='$user_id'";
		$this->query($sql);
		if ($this->next_record())
			return $this->f("id");
		else
			return false;
	}

	function get_contact_profile_by_email($email, $user_id)
	{
		$sql = "SELECT * FROM contacts WHERE email='$email' AND user_id='$user_id'";
		$this->query($sql);
		if ($this->next_record())
			return $this->Record;
		else
			return false;
	}

	function get_groups($user_id)
	{
			$sql= "SELECT * FROM contact_groups WHERE user_id='$user_id'";
			$this->query($sql);
			return $this->num_rows();
	}

	function add_group($user_id, $name)
	{
			$group_id = $this->nextid("contact_groups");
			if ($group_id > 0)
			{
					$sql = "INSERT INTO contact_groups (id, user_id, name) VALUES ('$group_id', '$user_id', '$name')";
					$this->query($sql);
					return $group_id;
			}else
			{
					return false;
			}
	}

	function delete_group($group_id)
	{
			$sql = "UPDATE contacts SET group_id='0' WHERE group_id='$group_id'";
			if ($this->query($sql))
			{
					$sql = "DELETE FROM contact_groups WHERE id='$group_id'";
					$this->query($sql);
					return true;
			}
			return false;
	}

	function move_contact_to_group($contact_id, $group_id)
	{
			$sql = "UPDATE contacts SET group_id='$group_id' WHERE id='$contact_id'";
			$this->query($sql);
	}

	function clear_group($group_id)
	{
			$sql = "UPDATE contacts SET group_id='0' WHERE group_id='$group_id'";
			$this->query($sql);
	}

	function change_group_name($group_id, $name)
	{
			$sql = "UPDATE contact_groups SET name='$name' WHERE id='$group_id'";
			$this->query($sql);
	}

	function delete_user($user_id)
	{
		$sql = "UPDATE contacts SET source_id='0' WHERE source_id='$user_id'";
		$this->query($sql);
		$sql = "DELETE FROM contacts WHERE user_id ='$user_id'";
		$this->query($sql);
	}
}
?>