<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// 			1.01 Release date:  8 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class filetypes extends db
{
	function filetypes()
	{
		$this->db();
	}

	function add_type($extension, $mime='', $friendly='', $icon='',$return_image=false)
	{

		if ($mime == '')
		{
			$mime = $this->get_mime_type($extension);
		}

		if ($friendly == '')
		{
			$friendly = $mime;
		}

		if ($icon == '')
		{
			global $GO_CONFIG;
			$fd = fopen ($GO_CONFIG->default_filetype_icon, "r");
			$icon = fread ($fd, filesize($GO_CONFIG->default_filetype_icon));
			fclose ($fd);
		}

		$sql = "INSERT INTO filetypes (extension,mime, friendly, image) VALUES ('$extension', '$mime', '$friendly', '$icon')";
		if($this->query($sql))
		{
			$type['extension'] = $extension;
			$type['mime'] = $mime;
			$type['friendly'] = $friendly;
			if ($return_image)
			{
				$type['image'] = $icon;
			}
			return $type;
		}
		return false;
	}

	function get_type($extension, $return_image=false)
	{
		$fields = isset($return_image) ? '*' : 'mime, friendly, extension';
		$sql = "SELECT $fields FROM filetypes WHERE extension='$extension'";
		if($this->query($sql))
		{
			if ($this->next_record())
			{
				return $this->Record;
			}
		}
		return false;
	}

	function get_filetypes($first=0, $offset=0)
	{
		$sql = "SELECT * FROM filetypes ORDER BY extension ASC";
		if ($offset != 0)
		{
			$sql .= " LIMIT $first, $offset";
			$this->query("SELECT COUNT(*) FROM filetypes");
			if ($this->next_record())
			{
				$count = $this->f(0);
				if ($count > 0)
				{
					$this->query($sql);
				}
				return $count;
			}

		}else
		{
			$this->query($sql);
			return $this->num_rows();
		}
	}

	function update_filetype($extension, $friendly, $icon='')
	{
		if ($icon != '' && $data = addslashes(fread(fopen($icon, "r"), filesize($icon))))
		{
			$sql = "UPDATE filetypes SET friendly='$friendly', image='$data' WHERE extension='$extension'";
		}else
		{
			$sql = "UPDATE filetypes SET friendly='$friendly' WHERE extension='$extension'";
		}
		return $this->query($sql);
	}

	function delete_type($extension)
	{
		$sql = "DELETE FROM filetypes WHERE extension='$extension'";
		return $this->query($sql);
	}

	function get_mime_type($extension)
	{
		global $GO_CONFIG;
		$file = file($GO_CONFIG->mime_types_file);
		foreach($file as $line)
		{
			rtrim($line);
			if (preg_match('/^\#/', $line))
				continue;
			$elms = preg_split('/\s+/', $line);
			$mime = array_shift($elms);
			foreach ($elms as $elm)
			{
				if ($elm == $extension)
				{
					return $mime;
				}
		 	}
		}
		return 'application/OCTET-STREAM';
	}

	function get_file_extension($filename)
	{
		if ($filename{0} == '.')
		{
			$filename = substr($filename, 1);
		}
		ereg(".*\.([a-zA-z0-9]{0,5})$", $filename, $regs);
		return $regs[1];
	}

	function get_mime_extension($mime)
	{
		$sql = "SELECT extension FROM filetypes WHERE mime='$mime'";
		$this->query($sql);
		if ($this->next_record())
		{
			return $this->f('extension');
		}
		return false;
	}
}
?>
