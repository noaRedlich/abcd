<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class bookmarks extends db
{
	function bookmarks()
	{
		$this->db();
	}

	function add_bookmark($user_id, $URL, $name, $new_window)
	{
		if ($new_window == true)
		{
			$new_window = 1;
		}
		else
		{
			$new_window = 0;
		}
		$bookmark_id = $this->nextid("bookmarks");
		if ($bookmark_id > 0)
		{
			$sql = "INSERT INTO bookmarks (id, user_id, URL, name, new_window)";
			$sql .= " VALUES ('$bookmark_id', '$user_id', '$URL', '$name', '$new_window')";
   			$this->query($sql);
			if ($this->affected_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}else
		{
			return false;
		}
	}

	function update_bookmark($bookmark_id, $URL, $name, $new_window)
	{
		if ($new_window == true)
		{
			$new_window = 1;
		}
		else
		{
			$new_window = 0;
		}

		$sql = "UPDATE bookmarks SET URL='$URL', name='$name', new_window='$new_window'";
		$sql .= " WHERE id='$bookmark_id'";
		return ($this->query($sql));

	}

	function delete_bookmark($user_id, $id)
	{

		$sql = "DELETE FROM bookmarks WHERE id='$id' AND user_id='$user_id'";
		$this->query($sql);
		if ($this->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_bookmarks($user_id)
	{
		$sql = "SELECT * FROM bookmarks WHERE user_id='$user_id'";
		$this->query($sql);
		return $this->num_rows();
	}

	function delete_user($user_id)
	{
		$this->get_bookmarks($user_id);
		$del = new bookmarks;
		while ($this->next_record())
		{

			$del->delete_bookmark($user_id, $this->f("id"));
		}

	}
}
