<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class cms extends db
{
	function cms()
	{
		$this->db();
	}

	//functions for personal sites
	function add_site($user_id, $name, $acl_id)
	{
		//create a root folder for the new site
		if ($root_folder_id = $this->add_folder(0, $name))
		{
			//create the site
			$site_id = $this->nextid('cms_sites');
			if ($site_id > 0)
			{
				return $this->query("INSERT INTO cms_sites (id, user_id, root_folder_id, name, acl_id) VALUES ('$site_id', '$user_id', '$root_folder_id', '$name', '$acl_id')");
			}else
			{
				//on error clean up
				$this->delete_folder($folder_id);
			}
		}
		return false;
	}

	function delete_site($site_id)
	{
		if($site = $this->get_site($site_id))
		{
			if ($this->delete_folder($site['root_folder_id']))
			{
				if($this->query("DELETE FROM cms_sites WHERE id='$site_id'"))
				{
					global $GO_SECURITY;
					$GO_SECURITY->delete_acl($site['acl_id']);
					return true;
				}
			}
		}
		return false;
	}

	function update_site($site_id, $name)
	{
		return $this->query("UPDATE cms_sites SET name='$name' WHERE id='$site_id'");
	}

	function get_site($site_id)
	{
		$this->query("SELECT * FROM cms_sites WHERE id='$site_id'");
		if ($this->next_record())
		{
			return $this->Record;
		}
		return false;
	}

	function get_sites($user_id)
	{
		$this->query("SELECT * FROM cms_sites WHERE user_id='$user_id'");
		return $this->num_rows();
	}


	//functions for files and folders of sites
	function get_files($folder_id)
	{
		$this->query("SELECT * FROM cms_files WHERE folder_id='$folder_id'");
		return $this->num_rows();
	}

	function get_folders($folder_id)
	{
		$this->query("SELECT * FROM cms_folders WHERE parent_id='$folder_id'");
		return $this->num_rows();
	}

	function add_folder($parent_id, $name)
	{
		$folder_id = $this->nextid('cms_folders');
		if ($folder_id > 0)
		{
			if($this->query("INSERT INTO cms_folders (id, parent_id, name) VALUES ('$folder_id', '$parent_id', '$name')"))
			{
				return $folder_id;
			}
		}
		return false;
	}

	function folder_exists($parent_id, $name)
	{
		$this->query("SELECT id FROM cms_folders WHERE parent_id='$parent_id' AND name='$name'");
		return $this->next_record();
	}

	function delete_folder($folder_id)
	{
		//add a second cms object for simultanious select and delete from the db
		$cms2 = new cms();

		//get all folders
		$this->get_folders($folder_id);
		while($this->next_record())
		{
			if (!$cms2->delete_folder($this->f('id')))
			{
				return false;
			}
		}

		$this->get_files($folder_id);
		while ($this->next_record())
		{
			if(!$cms2->delete_file($this->f('id')))
			{
				return false;
			}
		}

		return $this->query("DELETE FROM cms_folders WHERE id='$folder_id'");
	}

	function delete_file($file_id)
	{
		return $this->query("DELETE FROM cms_files WHERE id='$file_id'");
	}
}
?>