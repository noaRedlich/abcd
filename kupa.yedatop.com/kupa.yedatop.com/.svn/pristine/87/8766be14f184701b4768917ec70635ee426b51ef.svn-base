<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

//////////////////////////////////////////////////////////////////////////////////
////////////////////// This file contains the group management ///////////////////
////////////////////// functions.                              ///////////////////
//////////////////////////////////////////////////////////////////////////////////

class groups extends db
{
        var $group_everyone = "2";
        var $group_root = "1";

        function groups()
		{
			$this->db();
		}

		function delete_group($group_id)
        {

                $this->query("DELETE FROM users_groups WHERE group_id='$group_id'");
                $this->query("DELETE FROM groups WHERE id='$group_id'");
		global $GO_SECURITY;
		$GO_SECURITY->delete_group($group_id);
        }

        function clear_group($group_id)
        {

                $this->query("DELETE FROM users_groups WHERE group_id='$group_id'");
                return true;
        }

		function add_user_to_group($user_id, $group_id)
        {

                $this->query("INSERT INTO users_groups (user_id,group_id) VALUES ($user_id, $group_id)");
                return true;
        }

		function group_exists($name)
        {

                $this->query("SELECT id FROM groups WHERE name='$name'");
                if ($this->num_rows() > 0)
                {
                        return true;
                }else
                {
                        return false;
                }
        }

        function add_group($user_id, $name)
        {
			$group_id = $this->nextid("groups");
			if ($group_id > 0)
			{
						$this->query("INSERT INTO groups (id, user_id, name) VALUES ('$group_id','$user_id','$name')");
						return $group_id;
			}else
			{
				return -1;
			}
        }

		function user_owns_group($user_id, $group_id)
        {

                $this->query("SELECT user_id FROM groups WHERE user_id='$user_id' AND id='$group_id'");
                if ($this->num_rows() > 0)
                {
                        return true;
                }else
                {
                        return false;
                }
        }

        function is_in_group($user_id, $group_id)
        {

                $sql = "SELECT user_id FROM users_groups WHERE user_id='$user_id' AND group_id='$group_id'";
                $this->query($sql);
                if ($this->num_rows() > 0)
                {
                        return true;
                }else
                {
                        return false;
                }
        }

		function get_users_in_group($group_id, $sort="name", $direction="ASC")
		{
			$sql = "SELECT users.id, users.name FROM users LEFT JOIN users_groups ON (users.id = users_groups.user_id) WHERE users_groups.group_id='$group_id' ORDER BY ".$sort." ".$direction;
			$this->query($sql);
			return $this->num_rows();
		}

        function group_is_visible($user_id, $group_id)
        {
                if ($this->user_owns_group($user_id, $group_id) || $this->is_in_group($user_id, $group_id))
                        return true;
                else
                        return false;
        }

		// Gets all groups. Not for user display. Use get_authorised groups.
        ////////////////////////////////////////////////////////////////////////
        function get_all_groups()
        {

                $this->query("SELECT groups.*,users.username FROM groups, users WHERE groups.user_id = users.id ORDER BY groups.id ASC");
        }

        //Users can only see groups when they own it or are in it. Therefore this
        //complicated query.
        //////////////////////////////////////////////////////////////////////
        function get_authorised_groups($user_id)
        {

                $sql = "SELECT DISTINCT groups.*,users.username FROM groups, users, users_groups";
                $sql .= " WHERE groups.user_id=users.id AND ((groups.user_id='$user_id')";
                $sql .= " OR (users_groups.user_id='$user_id' AND users_groups.group_id=groups.id)) ORDER BY groups.id ASC";
                $this->query($sql);
        }

		function delete_user($user_id)
		{
			$sql = "DELETE FROM users_groups WHERE user_id='$user_id'";
			$this->query($sql);
			$sql = "SELECT id FROM groups WHERE user_id='$user_id'";
			$this->query($sql);
			$del = new groups;
			while ($this->next_record())
			{
				$del->delete_group($this->f("id"));
			}

		}
}
?>
