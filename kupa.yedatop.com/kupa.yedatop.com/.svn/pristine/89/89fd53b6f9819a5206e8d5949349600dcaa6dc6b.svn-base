<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class files extends db
{
	var $folders = array();

	function files()
	{
		$this->db();
	}

	function get_all_sub_folders($folder_id)
	{

		$sql = "SELECT * FROM fbFolders WHERE parent_id='$folder_id' ORDER BY name DESC";
		$this->query($sql);
		return $this->num_rows();
	}

	function get_authorized_sub_folders($user_id, $folder_id, $sort = "name", $direction = "ASC")
	{
                //get the users groups
                $sql = "SELECT group_id FROM users_groups WHERE user_id='$user_id'";

                $this->query($sql);


                if ($this->next_record())
                {
                        $groups = $this->Record["group_id"];
                }

                //After the first group has been added put a ',' after each group.
                while ($this->next_record())
                {
                        $groups .= ",".$this->Record["group_id"];
                }

                //if user is not in groups don't check if the acl is permitted to one of his groups.
                if ($groups == "")
                {
					$sql  = "SELECT DISTINCT fbFolders.* FROM fbFolders LEFT JOIN acl ON (fbFolders.acl_read=acl.acl_id OR fbFolders.acl_write=acl.acl_id) ";
					$sql .= "WHERE fbFolders.parent_id='$folder_id' AND ";
					$sql .= "((acl.user_id='$user_id') OR fbFolders.user_id='$user_id') ";
					$sql .= "ORDER BY ".$sort." ".$direction;

                }else
                {
					$sql  = "SELECT DISTINCT fbFolders.* FROM fbFolders LEFT JOIN acl ON (fbFolders.acl_read=acl.acl_id OR fbFolders.acl_write=acl.acl_id) ";
					$sql .= "WHERE fbFolders.parent_id='$folder_id' AND ";
					$sql .= "((acl.user_id='$user_id' OR (acl.group_id IN($groups))) OR fbFolders.user_id='$user_id') ";
					$sql .= "ORDER BY ".$sort." ".$direction;
                }
                $this->query($sql);
                return $this->num_rows();
	}

	function get_parent_id($folder_id)
	{

		$sql = "SELECT parent_id FROM fbFolders WHERE id='$folder_id'";
		$this->query($sql);
		if ($this->num_rows() > 0)
		{
			$this->next_record();
			return $this->f("parent_id");
		}else
		{
			return -1;
		}
	}

	function get_path($folder_id)
	{
		$path = '';
		if ($folder_id == 0)
		{
			$path = '/';
		}
		while ($folder_id != 0)
		{
			$folder = $this->get_folder_properties($folder_id, false);
			$path = '/'.$folder["name"].$path;
			$folder_id = $folder["parent_id"];
		}
		return $path;
	}

	function get_folder_properties($folder_id, $include_username=false)
	{
		if ($include_username)
		{
			$sql = "SELECT fbFolders.*,users.username FROM fbFolders,users WHERE fbFolders.id='$folder_id' AND fbFolders.user_id=users.id";
		}else
		{
			$sql = "SELECT * FROM fbFolders WHERE id='$folder_id'";
		}
		$this->query($sql);
		if ($this->num_rows() > 0)
		{
			$this->next_record();
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function add_folder($parent_id, $user_id, $name, $acl_read, $acl_write)
	{
		$id = $this->nextid("fbFolders");
		if ($id > 0)
		{
			$sql = "INSERT INTO fbFolders (id, parent_id, user_id, name, acl_read, acl_write, time)";
			$sql .= " VALUES ('$id','$parent_id', '$user_id', '$name', '$acl_read', '$acl_write', '".time()."')";
			$this->query($sql);
			return $id;
		}else
		{
			return -1;
		}
	}

	function folder_exists($parent_id, $name)
	{

		$sql = "SELECT id FROM fbFolders WHERE name='$name' AND parent_id='$parent_id'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->f("id");
		}else
		{
			return false;
		}
	}

	function has_write_permission($user_id, $id, $type)
	{
		if ($type == "folder")
		{
			$item = $this->get_folder_properties($id, false);
		}else
		{
			$item = $this->get_file_properties($id, false);
		}

		global $GO_SECURITY;
		return $GO_SECURITY->has_permission($user_id, $item["acl_write"]);

	}

	function has_read_permission($user_id, $id, $type)
	{
		if ($type == "folder")
		{
			$item = $this->get_folder_properties($id, false);
		}else
		{
			$item = $this->get_file_properties($id, false);
		}

                global $GO_SECURITY;
                if ($GO_SECURITY->has_permission($user_id, $item["acl_read"]) == true)
                {
                        return true;
                }else
                {
                        return $GO_SECURITY->has_permission($user_id, $item["acl_write"]);
                }
	}


	function delete_folder($user_id, $folder_id)
	{
		$children = $this->get_all_sub_folders($folder_id);
		while($this->next_record())
		{
			$folder_query = new files;
			if ($folder_query->delete_folder($user_id, $this->Record["id"]) == -1)
			{
				return -1;
			}
		}
		if ($this->has_write_permission($user_id, $folder_id, "folder"))
		{
                        $this->get_all_files($folder_id);
			while ($this->next_record())
			{
				$files_query = new files;
				if ($files_query->has_write_permission($user_id, $this->f("id"), "file"))
				{
					$files_query->delete_file($this->f("id"));
				}else
				{
						return -1;
				}
			}
            unset($files_query);

			$sql = "SELECT acl_write, acl_read FROM fbFolders WHERE id='$folder_id'";
			$this->query($sql);
			$this->next_record();

			global $GO_SECURITY;
			$GO_SECURITY->delete_acl($this->f("acl_read"));
			$GO_SECURITY->delete_acl($this->f("acl_write"));

			$sql = "DELETE FROM fbFolders WHERE id='$folder_id'";
			$this->query($sql);
		}else
		{
			return -1;
		}
	}

    function recurse_permissions($user_id, $folder_id, $source_acl, $acl_name)
	{
		$GO_SECURITY = new GO_SECURITY;
		$folder = $this->get_folder_properties($folder_id, false);
		if ($folder["user_id"] == $user_id)
		{


				if ($source_acl != $folder[$acl_name])
						$GO_SECURITY->copy_acl($source_acl, $folder[$acl_name]);
		}

		$this->get_all_files($folder_id);
		while ($this->next_record())
		{
				if ($this->f("user_id") == $user_id)
				{
						$GO_SECURITY->copy_acl($source_acl, $this->f($acl_name));
				}
		}

		$children = $this->get_all_sub_folders($folder_id);
		while($this->next_record())
		{
				$folder_query = new files;
				$folder_query->recurse_permissions($user_id, $this->Record["id"], $source_acl, $acl_name);
		}

	}

	function save_folder_name($folder_id,$name)
	{
		$sql = "UPDATE fbFolders SET name='$name' WHERE id='$folder_id'";
		$this->query($sql);
	}

	function add_file($file, $filename, $filesize, $filetype, $folder_id, $user_id, $acl_read, $acl_write, $versions)
	{
		$filetype = trim($filetype);
		$extension = get_extension($filename);

		global $GO_CONFIG;

		require($GO_CONFIG->class_path."filetypes.class.inc");
		$filetypes = new filetypes;

		$filetype_id= $filetypes->get_filetype_id($extension, $filetype);
		if ($filetype_id == -1)
		{
			return -1;
		}
		$file_id = $this->nextid("fbFiles");
        $version_id = $this->nextid("fbVersions");

		if ($file_id > 0 && $version_id > 0)
		{
			$sql  = "INSERT INTO fbFiles (id, folder_id, name,filetype_id,user_id,acl_read,acl_write,versions, latest_version_id) ";
			$sql .= "VALUES ('$file_id','$folder_id', '$filename','$filetype_id','$user_id','$acl_read','$acl_write','$versions','$version_id')";
			if ($this->query($sql))
            {
				$sql  = "INSERT INTO fbVersions (id, file_id, size, time, user_id) ";
				$sql .= "VALUES ('$version_id','$file_id' , '$filesize', '".time()."', '$user_id')";
				$this->query($sql);

				global $GO_CONFIG;
				if (@mkdir($GO_CONFIG->file_storage_path.$user_id."/".$file_id,0700) && @copy($file,$GO_CONFIG->file_storage_path.$user_id."/".$file_id."/".$version_id))
				{
					return file_id;
				}else
				{
					$sql = "DELETE FROM fbFiles WHERE id = '$file_id'";
					$this->query($sql);
					$sql = "DELETE FROM fbVersions WHERE file_id = '$file_id'";
					$this->query($sql);
					return -1;
				}
        	}

		}else
		{
			return -1;
		}
	}

	function add_version($file, $filesize, $file_id, $user_id)
	{

		$sql = "SELECT user_id FROM fbFiles WHERE id='$file_id'";
		$this->query($sql);
		$this->next_record();
		$owner_id = $this->f("user_id");

		if ($owner_id != "")
		{
			global $GO_CONFIG;

			$version_id = $this->nextid("fbVersions");
			if ($version_id > 0)
			{
				$sql  = "INSERT INTO fbVersions (id, file_id, size, time, user_id) ";
				$sql .= "VALUES ('$version_id','$file_id', '$filesize','".time()."', '$user_id')";
				if ($this->query($sql))
				{
						$sql = "UPDATE fbFiles SET latest_version_id='$version_id' WHERE id='$file_id'";
						if ($this->query($sql))
						{
								if(@copy($file,$GO_CONFIG->file_storage_path.$owner_id."/".$file_id."/".$version_id))
								{
										return $version_id;
								}else
								{
										$sql = "DELETE FROM fbVersions WHERE id='$version_id'";
										$this->query($sql);
										return -1;
								}
						}else
						{
								$sql = "DELETE FROM fbVersions WHERE id='$version_id'";
								$this->query($sql);
								return -1;
						}
				}
			}else
			{
				return -1;
			}
		}else
		{
			return -1;
		}
	}

	function file_exists($folder_id, $filename)
	{

		$sql = "SELECT id FROM fbFiles WHERE name='$filename' AND folder_id='$folder_id'";
		$this->query($sql);
		if ($this->num_rows() > 0)
		{
			$this->next_record();
			return $this->f("id");
		}else
		{
			return false;
		}
	}



	function get_all_files($folder_id)
	{

		$sql  = "SELECT fbFiles.*, filetypes.friendly AS type, fbVersions.size, fbVersions.time ";
		$sql .= "FROM fbFiles LEFT JOIN fbVersions ON (fbFiles.latest_version_id = fbVersions.id) ";
		$sql .= "LEFT JOIN filetypes ON (fbFiles.filetype_id = filetypes.id) WHERE fbFiles.folder_id = '$folder_id'";
		$this->query($sql);
	}

	function get_authorized_files($user_id, $folder_id, $sort = "name", $direction = "ASC")
	{
		//get the users groups
		$sql = "SELECT group_id FROM users_groups WHERE user_id='$user_id'";

		$this->query($sql);


		if ($this->next_record())
		{
				$groups = $this->Record["group_id"];
		}

		//After the first group has been added put a ',' after each group.
		while ($this->next_record())
		{
				$groups .= ",".$this->Record["group_id"];
		}

		//if user is not in groups don't check if the acl is permitted to one of his groups.
		if ($groups == "")
		{
			$sql  = "SELECT DISTINCT fbFiles.*, filetypes.friendly AS type, filetypes.type AS content, fbVersions.size, fbVersions.time, fbVersions.id as version_id ";
			$sql .= "FROM fbFiles LEFT JOIN fbVersions ON (fbFiles.latest_version_id = fbVersions.id) ";
			$sql .= "LEFT JOIN filetypes ON (fbFiles.filetype_id = filetypes.id) ";
			$sql .= "LEFT JOIN acl ON (fbFiles.acl_read=acl.acl_id OR fbFiles.acl_write=acl.acl_id) ";
			$sql .= "WHERE fbFiles.folder_id = '$folder_id' AND ((acl.user_id='$user_id') OR fbFiles.user_id='$user_id') ";
			$sql .= "GROUP BY fbFiles.name ORDER BY ".$sort." ".$direction;
                }else
                {
			$sql  = "SELECT DISTINCT fbFiles.*, filetypes.friendly AS type, filetypes.type AS content, fbVersions.size, fbVersions.time, fbVersions.id as version_id ";
			$sql .= "FROM fbFiles LEFT JOIN fbVersions ON (fbFiles.latest_version_id = fbVersions.id) ";
			$sql .= "LEFT JOIN filetypes ON (fbFiles.filetype_id = filetypes.id) ";
			$sql .= "LEFT JOIN acl ON (fbFiles.acl_read=acl.acl_id OR fbFiles.acl_write=acl.acl_id) ";
			$sql .= "WHERE fbFiles.folder_id = '$folder_id' AND ((acl.user_id='$user_id') OR (acl.group_id IN($groups)) ";
			$sql .= "OR fbFiles.user_id='$user_id') GROUP BY fbFiles.name ORDER BY ".$sort." ".$direction;
		}

		$this->query($sql);
		return $this->num_rows();
	}

	function get_file_properties($file_id, $include_username = false)
	{

		if ($include_username)
		{
			$sql  = "SELECT fbFiles.*,users.username,filetypes.friendly FROM fbFiles,users,filetypes ";
			$sql .= "WHERE fbFiles.id='$file_id' AND fbFiles.user_id=users.id AND filetypes.id=fbFiles.filetype_id";
		}else
		{
			$sql = "SELECT fbFiles.*,filetypes.friendly FROM fbFiles,filetypes WHERE fbFiles.id='$file_id' AND fbFiles.filetype_id=filetypes.id";
		}
		$this->query($sql);
		if ($this->num_rows() > 0)
		{
			$this->next_record();
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function update_file($file_id,$name, $versions)
	{

		$sql = "UPDATE fbFiles SET name='$name', versions='$versions' WHERE id='$file_id'";
		$this->query($sql);
	}

	function get_file($version_id)
	{

		$sql  = "SELECT fbVersions.*, filetypes.type, fbFiles.user_id AS file_user_id, fbFiles.name, fbFiles.latest_version_id FROM fbVersions LEFT JOIN fbFiles ON fbVersions.file_id=fbFiles.id ";
		$sql .= "LEFT JOIN filetypes ON fbFiles.filetype_id=filetypes.id WHERE fbVersions.id='$version_id'";
		$this->query($sql);
		$this->query($sql);
		if ($this->num_rows() > 0)
		{
			$this->next_record();
			return $this->Record;
		}else
		{
			return false;
		}
	}

	function delete_file($file_id)
	{

		$sql = "SELECT acl_write, acl_read, user_id FROM fbFiles WHERE id='$file_id'";
		$this->query($sql);
		$this->next_record();

		$user_id = $this->f("user_id");

		global $GO_SECURITY;

		$GO_SECURITY->delete_acl($this->f("acl_read"));
		$GO_SECURITY->delete_acl($this->f("acl_write"));

		$sql = "DELETE FROM fbVersions WHERE file_id='$file_id'";
		$this->query($sql);

		$sql = "DELETE FROM fbFiles WHERE id='$file_id'";
		$this->query($sql);

		global $GO_CONFIG;

		$location = $GO_CONFIG->file_storage_path.$user_id."/".$file_id."/";
		$file_dir = opendir($location);
		while ($file = readdir($file_dir))
		{
			if (!is_dir($location.$file))
			{
				unlink($location.$file);
				unset($file);
			}
		}
		rmdir($location);
	}

	function delete_version($version_id)
	{
		$file = $this->get_file($version_id);

		$sql = "DELETE FROM fbVersions WHERE id='$version_id'";
		$this->query($sql);
		global $GO_CONFIG;
		unlink($GO_CONFIG->file_storage_path.$file["file_user_id"]."/".$file["file_id"]."/".$version_id);
		if ($file["latest_version_id"] == $version_id)
		{
				$sql = "SELECT max(id) AS version_id FROM fbVersions WHERE file_id='".$file["file_id"]."'";
				$this->query($sql);
				$this->next_record();
				$sql = "UPDATE fbFiles SET latest_version_id='".$this->f("version_id")."' WHERE id='".$file["file_id"]."'";
				$this->query($sql);
		}
	}

	function delete_latest_version($file_id)
	{

		$sql = "SELECT user_id FROM fbFiles WHERE id='$file_id'";
		$this->query($sql);
		$this->next_record();
		$user_id = $this->f("user_id");

		$sql  = "SELECT max(id) FROM fbVersions WHERE file_id='$file_id'";
		$this->query($sql);
		$this->next_record();
		$version_id = $this->f(0);

		if ($version_id != "" && $user_id != "")
		{
			$sql = "DELETE FROM fbVersions WHERE id='$version_id'";
			$this->query($sql);

			global $GO_CONFIG;
			unlink($GO_CONFIG->file_storage_path.$user_id."/".$file_id."/".$version_id);
			$sql = "SELECT max(id) AS version_id FROM fbVersions WHERE file_id='".$file_id."'";
			$this->query($sql);
			$this->next_record();
			$sql = "UPDATE fbFiles SET latest_version_id='".$this->f("version_id")."' WHERE id='".$file_id."'";
			$this->query($sql);
		}else
		{
			$this->halt("Could not get latest version");
		}
	}

	function delete_oldest_version($file_id)
	{

		$sql = "SELECT user_id FROM fbFiles WHERE id='$file_id'";
		$this->query($sql);
		$this->next_record();
		$user_id = $this->f("user_id");

		$sql  = "SELECT min(id) FROM fbVersions WHERE file_id='$file_id'";
		$this->query($sql);
		$this->next_record();
		$version_id = $this->f(0);

		if ($version_id != "" && $user_id != "")
		{
			$sql = "DELETE FROM fbVersions WHERE id='$version_id'";
			$this->query($sql);

			global $GO_CONFIG;
			unlink($GO_CONFIG->file_storage_path.$user_id."/".$file_id."/".$version_id);
		}else
		{
			$this->halt("Could not get oldest version");
		}
	}

	function max_versions_reached($file_id)
	{

		$sql  = "SELECT fbVersions.id, fbFiles.versions FROM fbVersions LEFT JOIN fbFiles ON fbFiles.id=fbVersions.file_id ";
		$sql .= "WHERE file_id='$file_id'";
		$this->query($sql);
		$this->next_record();
		if ($this->f("versions") < $this->num_rows())
		{
			return true;
		}else
		{
			return false;
		}
	}

	function get_versions($file_id)
	{

		$sql  = "SELECT fbVersions.*, users.username FROM fbVersions, users WHERE file_id='$file_id' ";
		$sql .=" AND fbVersions.user_id=users.id ORDER BY time DESC";
		$this->query($sql);
		return $this->num_rows();
	}

	function paste($id, $new_folder_id, $type)
	{
		if ($type == "folder")
		{
			$sql = "UPDATE fbFolders SET parent_id='$new_folder_id' WHERE id='$id'";
		}else
		{
			$sql = "UPDATE fbFiles SET folder_id='$new_folder_id' WHERE id='$id'";
		}

		$this->query($sql);
	}


	function get_used_diskspace($user_id)
	{
			$sql = "SELECT SUM(size) AS size FROM fbVersions WHERE user_id='$user_id'";
			$this->query($sql);
			$this->next_record();
			return $this->f("size");
	}

    function search_files($user_id, $filename, $sort="name", $direction="ASC")
	{
		//get the users groups
		$sql = "SELECT group_id FROM users_groups WHERE user_id='$user_id'";

		$this->query($sql);


		if ($this->next_record())
		{
				$groups = $this->Record["group_id"];
		}

		//After the first group has been added put a ',' after each group.
		while ($this->next_record())
		{
				$groups .= ",".$this->Record["group_id"];
		}

		//if user is not in groups don't check if the acl is permitted to one of his groups.
		if ($groups == "")
		{
			$sql  = "SELECT DISTINCT fbFiles.*, filetypes.friendly AS type, filetypes.type AS content, fbVersions.size, fbVersions.time, versions.id as version_id ";
			$sql .= "FROM fbFiles LEFT JOIN fbVersions ON (fbFiles.latest_version_id = fbVersions.id) ";
			$sql .= "LEFT JOIN filetypes ON (fbFiles.filetype_id = filetypes.id) ";
			$sql .= "LEFT JOIN acl ON (fbFiles.acl_read=acl.acl_id OR fbFiles.acl_write=acl.acl_id) ";
			$sql .= "WHERE fbFiles.name LIKE '%".$filename."%' AND ((acl.user_id='$user_id') OR fbFiles.user_id='$user_id') ";
			$sql .= "GROUP BY fbFiles.name ORDER BY fbFiles.name ASC";
                }else
                {
			$sql  = "SELECT DISTINCT fbFiles.*, filetypes.friendly AS type, filetypes.type AS content, fbVersions.size, fbVersions.time, fbVersions.id as version_id ";
			$sql .= "FROM fbFiles LEFT JOIN fbVersions ON (fbFiles.latest_version_id = fbVersions.id) ";
			$sql .= "LEFT JOIN filetypes ON (fbFiles.filetype_id = filetypes.id) ";
			$sql .= "LEFT JOIN acl ON (fbFiles.acl_read=acl.acl_id OR fbFiles.acl_write=acl.acl_id) ";
			$sql .= "WHERE fbFiles.name LIKE '%".$filename."%' AND ((acl.user_id='$user_id') OR (acl.group_id IN($groups)) ";
			$sql .= "OR fbFiles.user_id='$user_id') GROUP BY fbFiles.id ORDER BY fbFiles.name ASC";
		}

		$this->query($sql);
		return $this->num_rows();
	}

    function search_folders($user_id, $foldername)
	{
		//get the users groups

		$sql = "SELECT group_id FROM users_groups WHERE user_id='$user_id'";

		$this->query($sql);


		if ($this->next_record())
		{
				$groups = $this->Record["group_id"];
		}

		//After the first group has been added put a ',' after each group.
		while ($this->next_record())
		{
				$groups .= ",".$this->Record["group_id"];
		}

		//if user is not in groups don't check if the acl is permitted to one of his groups.
		if ($groups == "")
		{
			$sql  = "SELECT DISTINCT fbFolders.* FROM fbFolders LEFT JOIN acl ON (fbFolders.acl_read=acl.acl_id OR fbFolders.acl_write=acl.acl_id) ";
			$sql .= "WHERE fbFolders.name LIKE '%".$foldername."%' AND ";
			$sql .= "((acl.user_id='$user_id') OR fbFolders.user_id='$user_id') ";
			$sql .= "ORDER BY fbFolders.name ASC";

		}else
		{
			$sql  = "SELECT DISTINCT fbFolders.* FROM fbFolders LEFT JOIN acl ON (fbFolders.acl_read=acl.acl_id OR fbFolders.acl_write=acl.acl_id) ";
						$sql .= "WHERE fbFolders.name LIKE '%".$foldername."%' AND ";
			$sql .= "((acl.user_id='$user_id' OR (acl.group_id IN($groups))) OR fbFolders.user_id='$user_id') ";
			$sql .= "ORDER BY fbFolders.name ASC";
		}

		//$sql = "SELECT * from fbFolders WHere name like '%".$foldername."%' ORDER BY ".$sort." ".$direction;
		$this->query($sql);
		return $this->num_rows();
	}

	function save_description($id, $type, $description)
	{
		if ($type == "file")
				$sql = "UPDATE fbFiles SET description='$description' WHERE id='$id'";
		else
				$sql = "UPDATE fbFolders SET description='$description' WHERE id='$id'";

		$this->query($sql);
	}

	function delete_user($user_id)
	{
		global $GO_SECURITY;
		global $GO_CONFIG;

		//change ownership of users files to the current user

		$sql = "UPDATE fbFiles SET user_id='".$GO_SECURITY->user_id."' WHERE user_id='$user_id'";
		$this->query($sql);
		$sql = "UPDATE fbVersions SET user_id='".$GO_SECURITY->user_id."' WHERE user_id='$user_id'";
		$this->query($sql);

		//move the root folder to the current users folder
		$sql="SELECT id FROM fbFolders WHERE parent_id='0' AND user_id='".$user_id."'";
		$this->query($sql);
		$this->next_record();
		$old_id = $this->f("id");
		$old_filename = $this->f("name");
		$new_filename = $this->f("name");


		$sql = "UPDATE fbFolders SET user_id='".$GO_SECURITY->user_id."' WHERE user_id='$user_id'";
		$this->query($sql);


		$sql="SELECT id FROM fbFolders WHERE parent_id='0' AND user_id='".$GO_SECURITY->user_id."'";
		$this->query($sql);
		$this->next_record();
		$new_id = $this->f("id");

		if ($new_id > 0 && $old_id > 0)
		{
			$x=0;
			while ($this->folder_exists($new_id, $new_filename))
			{
				$x++;
				$new_filename = $old_filename."(".$x.")";
			}
			if ($x>0)
				$this->save_folder_name($old_id,$new_filename);

			$this->paste($old_id, $new_id, "folder");
		}

		$old_path = $GO_CONFIG->file_storage_path.$user_id.'/';
		$new_path = $GO_CONFIG->file_storage_path.$GO_SECURITY->user_id.'/';
		$file_dir = opendir($old_path);
		while ($file = readdir($file_dir))
		{
			if (is_dir($old_path.$file) && $file != "." && $file != "..")
			{
				$this->move_dir($old_path.$file.'/', $new_path.$file.'/');
			}
		}
		rmdir($old_path);
	}

	function move_dir ($from_path, $to_path)
	{
		mkdir($to_path, 0700);
		$this_path = getcwd();
		if (is_dir($from_path))
		{
			chdir($from_path);
			$handle=opendir('.');
			while (($file = readdir($handle))!==false)
			{
				if (($file != ".") && ($file != ".."))
				{
					if (is_dir($file))
					{
						$this->move_dir($from_path.$file."/",$to_path.$file."/");
						chdir($from_path);
					}
					if (is_file($file)){
						copy($from_path.$file, $to_path.$file);
						unlink($from_path.$file);
					}
				}
			}
			closedir($handle);
			rmdir($from_path);
		}
	}

	function get_family($folder_id)
	{
		$this->folders[] = $folder_id;
		$sql = "SELECT id FROM fbFolders WHERE parent_id='$folder_id'";
		$files = new files;
		$files->query($sql);
		while ($files->next_record())
		{
			$this->get_family($files->f("id"));
		}
		return $this->folders;
	}

}

?>
