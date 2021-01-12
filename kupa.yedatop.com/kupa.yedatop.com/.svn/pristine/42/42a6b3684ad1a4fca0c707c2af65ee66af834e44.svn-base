<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class email extends db
{
	function get_accounts($user_id)
	{
		$sql = "SELECT * FROM emAccounts WHERE user_id='$user_id' ORDER BY standard DESC";
		$result = $this->query($sql);
		return $this->num_rows($result);
	}

	function add_account($user_id, $type, $host, $port, $mbroot, $username, $password, $name, $email, $signature)
	{
		$next_id = $this->nextid("emAccounts");
		if ($next_id > 0)
		{
			if ($this->get_accounts($user_id) == 0)
					$default="1";
			else
					$default="0";

			$sql  = "INSERT INTO emAccounts (id, user_id, type, host, port, mbroot, username, password, name, email, signature, standard) ";
			$sql .= "VALUES ('$next_id', '$user_id', '$type', '$host', '$port', '$mbroot', '$username', '$password', '$name', '$email', '$signature','$default')";
			$this->query($sql);
			return $next_id;
		}else
		{
			return false;
		}
	}

	function update_account($id, $type, $host, $port, $mbroot, $username, $password, $name, $email, $signature)
	{
		$sql  = "UPDATE emAccounts SET type='$type', host='$host', port='$port', mbroot='$mbroot', username='$username', password='$password',";
		$sql .= " name='$name', email='$email', signature='$signature' WHERE id='$id'";

		return $this->query($sql);
	}

	function get_account($id = 0)
	{
		if ($id == 0)
		{
				global $user_id;
		$sql = "SELECT * FROM emAccounts WHERE standard='1' AND user_id='$user_id'";
		}else
		{
				$sql = "SELECT * FROM emAccounts WHERE id='$id'";
		}

		$this->query($sql);
		if ($this->next_record())
		{
				return $this->Record;
		}else
		{
				return false;
		}
	}

	function is_default_account($id)
	{
		$sql = "SELECT * FROM emAccounts WHERE id='$id' AND standard='1'";
		$this->query($sql);
		if ($this->num_rows() == 1)
				return true;
		else
				return false;
	}

	function delete_account($user_id, $id)
	{
		$default = $this->is_default_account($id);

		$sql = "DELETE FROM emAccounts WHERE id='$id' AND user_id='$user_id'";
		if ($this->query($sql))
		{
				$sql = "DELETE FROM emFolders WHERE account_id='$id'";
				$this->query($sql);
				$sql = "DELETE FROM emFilters WHERE account_id='$id'";
				$this->query($sql);
		}
		if ($default)
		{
				$this->get_accounts($user_id);
				$this->next_record();
				$this->set_as_default($this->f("id"), $user_id);
		}
		return true;
	}

	function set_as_default($account_id, $user_id)
	{
		$sql = "UPDATE emAccounts SET standard='0' WHERE user_id='$user_id' AND standard='1'";
		$this->query($sql);
		$sql = "UPDATE emAccounts SET standard='1' WHERE id='$account_id'";
		$this->query($sql);
	}

	function get_folders($id)
	{
		$sql = "SELECT * FROM emFolders WHERE account_id='$id'";
		$this->query($sql);
		return $this->num_rows();
	}


	function get_sent_folder($id)
	{
		$sql = "SELECT * FROM emFolders WHERE account_id='$id' AND type='sent'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->f("name");
		}else
		{
			return false;
		}
	}

	function get_spam_folder($id)
	{
			$sql = "SELECT * FROM emFolders WHERE account_id='$id' AND type='spam'";
			$this->query($sql);
			if ($this->next_record())
			{
				return $this->f("name");
			}else
			{
				return false;
			}
	}

	function add_folder($account_id, $name, $type='')
	{
		$next_id = $this->nextid("emFolders");
		if ($next_id > 0)
		{
			$sql = "INSERT INTO emFolders (id, account_id, name, type) VALUES ('$next_id', '$account_id', '$name', '$type')";
			if ($this->query($sql))
			{
					return true;
			}
		}
		return false;
	}
	function rename_folder($account_id, $old_name, $new_name)
	{
		$sql = "UPDATE emFolders SET name='$new_name' WHERE name='$old_name' AND account_id='$account_id'";
		$this->query($sql);
		$sql = "UPDATE emFilters SET folder='$new_name' WHERE folder='$old_name' AND account_id='$account_id'";
		$this->query($sql);

	}

	function delete_folder($account_id, $name)
	{
		$sql = "DELETE FROM emFolders WHERE account_id='$account_id' AND name='$name'";
		$this->query($sql);
		$sql = "DELETE FROM emFilters WHERE account_id='$account_id' AND folder='$name'";
		$this->query($sql);
	}
	function folder_exists($account_id, $name)
	{
		$sql = "SELECT id FROM emFolders WHERE name='$name' AND account_id='$account_id'";
		$this->query($sql);
		if ($this->next_record())
				return $this->f("id");
		else
				return false;
	}
	function set_as_sent_folder($account_id, $name)
	{
		$sql = "UPDATE emFolders SET type='' WHERE account_id='$account_id' AND type='sent'";
		$this->query($sql);
		$sql = "UPDATE emFolders SET type='sent' WHERE account_id='$account_id' AND name='$name'";
		$this->query($sql);
	}

	function set_as_spam_folder($account_id, $name)
	{
		$sql = "UPDATE emFolders SET type='' WHERE account_id='$account_id' AND type='spam'";
		$this->query($sql);
		$sql = "UPDATE emFolders SET type='spam' WHERE account_id='$account_id' AND name='$name'";
		$this->query($sql);
	}

	function synchronise($account_id, $subscribed)
	{
		//Add all missing subscribed folders to Group-Office
		for ($i=0;$i<sizeof($subscribed); $i++)
		{
			if ($subscribed[$i] != "INBOX" && !$this->folder_exists($account_id,$subscribed[$i]))
			{
					$this->add_folder($account_id, $subscribed[$i]);
			}
		}

		//get all the Group-Office folders as an array
		$this->get_folders($account_id);
		$GO_mailboxes = array();
		while ($this->next_record())
		{
			$GO_mailboxes[] = $this->f("name");
		}

		//delete Group-Office Folders that are not subscribed
		for ($i=0;$i<sizeof($GO_mailboxes); $i++)
		{
			if (!in_array($GO_mailboxes[$i], $subscribed))
			{
					$this->delete_folder($account_id, $GO_mailboxes[$i]);
			}
		}

	}

	function get_filters($account_id)
	{
		$sql = "SELECT * FROM emFilters WHERE account_id='$account_id' ORDER BY priority DESC";
		$this->query($sql);
		return $this->num_rows();
	}

	function add_filter($account_id, $field, $keyword, $folder)
	{
		$next_id = $this->nextid("emFilters");
		if ($next_id > 0)
		{
			$sql = "INSERT INTO emFilters (id, account_id, field, keyword, folder, priority) VALUES ('$next_id','$account_id','$field','$keyword','$folder','$next_id')";
			return $this->query($sql);
		}else
		{
			return false;
		}
	}

	function update_filter($id, $field, $keyword, $folder)
	{
		$sql = "UPDATE emFilters SET field='$field', keyword='$keyword', folder='$folder'";
		$this->query($sql);
	}

	function delete_filter($id)
	{
		$sql = "DELETE FROM emFilters WHERE id='$id'";
		$this->query($sql);
	}

	function move_up($move_up_id, $move_dn_id, $move_up_pr, $move_dn_pr)
	{
		if ($move_up_pr == $move_dn_pr)
			$move_up_pr++;

		$sql = "UPDATE emFilters SET priority='$move_up_pr' WHERE id='$move_up_id'";
		$this->query($sql);

		$sql = "UPDATE emFilters SET priority='$move_dn_pr' WHERE id='$move_dn_id'";
		$this->query($sql);
	}

	function delete_user($user_id)
	{
		$del = new email;
		$this->get_accounts($user_id);
		while ($this->next_record())
		{

			$del->delete_account($user_id,$this->f("id"));
		}
	}

	function register_attachment($tmp_file, $filename, $filesize, $filemime='', $content_id='')
	{
		global $GO_CONFIG, $attach_array, $num_attach;

		if (!is_array($attach_array))
		{
			$attach_array = array();
			$num_attach = 1;
		}else
		{
			$num_attach++;
		}

		require_once($GO_CONFIG->class_path.'filetypes.class.inc');
		$filetypes = new filetypes();

		$extension = get_file_extension($filename);
		if(!$type = $filetypes->get_type($extension))
		{
			$type = $filetypes->add_type($extension, $filemime);
		}


        $attach_array[$num_attach]->file_name = $filename;
        $attach_array[$num_attach]->tmp_file =  $tmp_file;
        $attach_array[$num_attach]->file_size = $filesize;

        $attach_array[$num_attach]->file_mime = $type['mime'];
        if ($content_id != '')
        {
        	$attach_array[$num_attach]->content_id = $content_id;
		}

        // Registering the attachments array into the session
        session_register('num_attach', 'attach_array');
	}
}
?>
