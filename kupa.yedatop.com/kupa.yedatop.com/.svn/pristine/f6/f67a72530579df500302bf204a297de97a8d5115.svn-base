<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/


class button
{
	function button($text, $action, $width='100',$add='')
	{
		echo '<input type="button" class="button" '.$add.' style="width: '.$width.'px;" value="'.$text.'" onclick="'.$action.'" onmouseover="javascript:this.className=\'button_mo\';" onmouseout="javascript:this.className=\'button\';" />';
	}
}

class statusbar
{
	var $usage_color = "#0000CC";
	var $background_color = "#f1f1f1";
	var $border_color = "#000000";
	var $width = "250";
	var $height = "10";
	var $info_text;
	var $turn_red_point = 0;

	function print_bar($usage, $limit)
	{
		if ($limit != 0)
		{
				$percentage = ($usage/$limit)*100;
				$usage_width = number_format(($usage/$limit)*$this->width);
				$remaining_width = $this->width - $usage_width;
		}else
		{
				$usage_width= 0;
				$remaining_width = $this->width;
		}
		if ($this->turn_red_point > 0 && $percentage >= $this->turn_red_point)
		{
			$this->usage_color = '#FF0000';
		}

		echo '<table border="0" cellpadding="1" cellspacing="0">';
		echo '<tr><td bgcolor="'.$this->border_color.'">';
		echo '<table border="0" cellpadding="0" cellspacing="0" width="250">';
		echo '<tr><td height="'.$this->height.'" bgcolor="'.$this->usage_color.'" width="'.$usage_width.'"></td>';
		echo '<td height="'.$this->height.'" bgcolor="'.$this->background_color.'" width="'.$remaining_width.'"></td></tr>';
		echo '</table></td></tr>';
		echo '</table><table border="0" cellpadding="1" cellspacing="0">';
		echo '<tr><td class="small" height="20">'.number_format($percentage).'% '.$this->info_text.'</td></tr>';
		echo '</table>';
	}

}

class dropbox
{
	var $value;
	var $text;
	var $optgroup;

	function add_value($value, $text)
	{
		if ($text != '')
		{
			$this->value[] = $value;
			$this->text[] = $text;
			return true;
		}else
		{
			return false;
		}
	}

	function add_optgroup($name)
	{
		$this->optgroup[count($this->value)] = $name;
	}

	function add_sql_data($sql_object, $value, $text)
	{
		global $$sql_object;

		while ($$sql_object->next_record())
		{
			$this->value[] = $$sql_object->f($value);
			$this->text[] = $$sql_object->f($text);
		}
	}

	function add_arrays($value, $text)
	{
		if (is_array($this->value))
		{
			$this->value = array_merge($this->value, $value);
			$this->text = array_merge($this->text, $text);
		}else
		{
			$this->value = $value;
			$this->text = $text;
		}
		return true;
	}

	function print_dropbox($name, $selected_field='', $attributes='')
	{
		$optgroup_open = false;
		echo '<select name="'.$name.'" class="textbox" '.$attributes.'>';

		for ($i=0;$i<count($this->value);$i++)
		{
			if(isset($this->optgroup[$i]))
			{
				if ($optgroup_open == true)
				{
					echo '</optgroup>';
				}else
				{
					$optgroup_open = true;
				}
				echo '<optgroup label="'.$this->optgroup[$i].'">';
			}

			if ($this->text[$i] != '')
			{
				echo '<option value="'.$this->value[$i].'"';
				if ($this->value[$i] == $selected_field)
				{
					echo ' selected';
				}
				echo '>';
				echo $this->text[$i];
				echo '</option>';
			}
		}
		if ($optgroup_open == true)
		{
			echo '</optgroup>';
		}
		echo '</select>';
	}
}

/*

expirimental and useless
class treeview
{
	var $treeview_id;
	var $items = array();
	var $images = array();
	var $root = array();
	var $expanded = array();
	var $arguments;


	function treeview($treeview_id, $arguments='')
	{
		global $GO_CONFIG;

		$this->treeview_id = $treeview_id;
		$this->arguments = $arguments;

		//declare some standard images
		$this->images['mlastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/mlastnode.gif" border="0" height="22" width="16" />';
		$this->images['emptylastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/emptylastnode.gif" border="0" height="22" width="16" />';
		$this->images['plastnode'] = '<img src="'.$GO_THEME->image_url.'treeview/plastnode.gif" border="0" height="22" width="16" />';
		$this->images['mnode'] = '<img src="'.$GO_THEME->image_url.'treeview/mnode.gif" border="0" height="22" width="16" />';
		$this->images['emptynode'] = '<img src="'.$GO_THEME->image_url.'treeview/emptynode.gif" border="0" height="22" width="16" />';
		$this->images['pnode'] = '<img src="'.$GO_THEME->image_url.'treeview/pnode.gif" border="0" height="22" width="16" />';
		$this->images['vertline'] = '<img src="'.$GO_THEME->image_url.'treeview/vertline.gif" border="0" height="22" width="16" />';
		$this->images['blank'] = '<img src="'.$GO_THEME->image_url.'treeview/blank.gif" border="0" height="22" width="16" />';

		//get the global expanded folders array
		$expanded = 'expanded_'.$treeview_id;
		global $$expanded;
		if (!isset($$expanded))
		{
			$this->expanded[] = -1;
		}else
		{
			$this->expanded = $$expanded;
		}

		//expand or collaps the expand_id which is passed when a user clicks a node
		$expand_id = 'expand_id_'.$treeview_id;
		global $$expand_id;
		if (isset($$expand_id))
		{
			$key = array_search($$expand_id, $this->expanded);
			if (!$key)
			{
				$this->expanded[]= $$expand_id;
			}else
			{
				unset($this->expanded[$key]);
			}
		}
		//remember opened folders
		$$expanded = $this->expanded;
		session_register($expanded);
	}

	//adds the root item
	function add_root($id, $name, $url, $image)
	{
		$this->root['id'] = $id;
		$this->root['parent_id'] = $parent_id;
		$this->root['name'] = $name;
		$this->root['url'] = $url;
		$this->root['image'] = $image;
		return true;
	}

	//adds an item
	function add_item($id, $parent_id, $name, $url, $image)
	{
		$item['id'] = $id;
		$item['parent_id'] = $parent_id;
		$item['name'] = $name;
		$item['url'] = $url;
		$item['image'] = $image;
		$this->items[$parent_id][] = $item;
		return true;
	}

	function print_tree($images='')
	{
		$folders[] = $this->root;
		$this->print_arrays($folders);
	}

	//private function prints the tree
	function print_arrays($folders, $images='')
	{
		//count the folders
		$count = count($folders);
		while ($folder = array_shift($folders))
		{
			//get the subfolders
			$subfolders = $this->items[$folder['id']];
			//count the subfolders
			$subfolders_count = count($this->items[$subfolders]);
			//if the id is in the expanded array then this item is opened
			$open = in_array($folder['id'], $this->expanded);

			//determine the node to display
			if ($subfolders_count > 0)
			{
				if ($i < ($count-1))
				{
					$new_image = $this->images['vertline'];
					$node = $open ? $this->images['mnode'] : $this->images['pnode'];

				}else
				{
					$new_image = $this->images['blank'];
					$node = $open ? $this->images['mlastnode'] : $this->images['plastnode'];
				}
			}else
			{
				if ($i < ($count-1))
				{
					$new_image = $this->images['vertline'];
					$node = $this->images['emptynode'];
				}else
				{
					$new_image = $image['blank'];
					$node = $this->images['emptylastnode'];
				}
			}

			//actually print the current folder
			$short_name = cut_string($folder['name'], 30);
			echo '<table border="0" cellpadding="0" cellspacing="0">';
			echo '<tr><td><a href="'.$PHP_SELF.'?expand_id='.$folders['id'].'$this->arguments">'.$images.$node.$folder['image'].'</a></td>';
			echo '<td nowrap><a href="'.$folder['url'].'">'.$short_name.'</a></td></tr></table>';

			if ($open)
			{
				print_arrays($subfolders, $images.$new_image);
			}

		}

	}
}
*/
?>