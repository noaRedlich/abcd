<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// Version: 1.02 Release date: 24 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

class imap
{
	var $conn;
	var $count;
	var $unseen;
	var $messages;
	var $next_message_id;
	var $message;
	var $servertype;
	var $connectstring;
	var $mailboxes;
	var $mailbox;
	var $username;


	/*
	Open connection to server and count total and new messages
	*/

	function open($host, $type, $port, $username, $password, $mailbox = "INBOX", $flags = "")
	{
		$this->username = $username;
		$this->servertype = strtolower($type);
		$this->mailbox = imap_utf7_encode($mailbox);
		$this->connectstring = $host.":".$port."/".$this->servertype;
		$this->conn = @imap_open("{".$this->connectstring."}".$this->mailbox, $username, $password, $flags);
		return $this->conn;
	}

	/*
	Close connection with server
	*/

	function close()
	{
		unset($this->messages);
		unset($this->count);
		unset($this->unseen);
		unset($this->next_message_id);
		@imap_close($this->conn);
		unset($this->conn);
	}

	/*
	count total and new messages on server
	*/
	function count()
	{
		$status = @imap_status($this->conn, "{".$this->connectstring."}".$this->mailbox, SA_ALL);
		if ($this->is_imap())
		{
			$this->unseen = $status->unseen;
		}

		$this->count = $status->messages;
		return $status;
	}

	function status($mailbox)
	{
		$status = imap_status($this->conn, "{".$this->connectstring."}".$mailbox, SA_ALL);
		return $status;
	}


	/*
	return true when server is an IMAP server.
	*/

	function is_imap()
	{
		if ($this->servertype == "imap")
		{
			return true;
		}else
		{
			return false;
		}
	}

	/*
	Return sorted messages into $this->messages
	*/

	function sort($type = SORTDATE, $reverse = "0", $first_uid=0)
	{
		$this->next_message_id = $first_uid;
		$this->messages = imap_sort($this->conn,$type,$reverse,SE_UID);
	}

	/*
	Loop through messages and return array with message info needed for inbox listing.
	*/

	function next_message()
	{
		if (!is_array($this->messages))
		{
			$this->sort();
		}

		if (!isset($this->count))
		{
			$this->count;
		}

		if ($this->next_message_id < $this->count)
		{
			unset($this->message);

			$uid = $this->messages[$this->next_message_id];
			$this->message["uid"] = $uid;

			$headerinfo = imap_header($this->conn, imap_msgno($this->conn, $uid));

			$tmp = $headerinfo->from;
			$this->message["sender"] = $tmp[0]->mailbox."@".$tmp[0]->host;
			$from = isset($tmp[0]->personal) ? $tmp[0]->personal : $this->message["sender"];
			$tmp = imap_mime_header_decode($from);
			$this->message["from"] = htmlspecialchars($tmp[0]->text);

			if (isset($headerinfo->to))
			{
				$tmp = $headerinfo->to;
				for ($x=0;$x<sizeof($tmp);$x++) {
					$email = htmlspecialchars($tmp[$x]->mailbox ."@".$tmp[$x]->host);
					$this->message["to"][$x] = isset($tmp[$x]->personal) ? $tmp[$x]->personal : $email;
				}
			}

			if (isset($headerinfo->Subject))
			{
				$tmp = imap_mime_header_decode($headerinfo->Subject);
				$this->message["subject"] = isset($tmp[0]->text) ? $tmp[0]->text : '';
			}

			if (isset($headerinfo->Unseen) && isset($headerinfo->Recent) && $this->is_imap() && (($headerinfo->Unseen == 'U') || ($headerinfo->Recent == 'N')))
			{
				$this->message["new"] = "1";
			}else
			{
				$this->message["new"] = "0";
			}
			$this->message["udate"] = $headerinfo->udate;

			$header = imap_fetchheader($this->conn, $uid, FT_UID);
			$content_type = $this->get_header_value("Content-Type:", $header);
			if (!empty($content_type) && eregi("([^/]*)/([^ ;\n\t]*)", $content_type, $regs)) {
				$mtype = strtolower($regs[1]);
				$stype = strtolower($regs[2]);
			}else
			{
				$mtype = '';
				$stype = '';
			}

			if ($mtype != "text" && $stype != "alternative" && $stype != "related" && !empty($mtype) && !empty($stype))
			{
				$this->message["attachment"] = "1";
			}

			$this->message["priority"] = $this->get_header_value("X-Priority:", $header);

			$overview = imap_fetch_overview($this->conn, $uid, FT_UID);
			$this->message["flagged"] = $overview[0]->flagged;
			$this->message["size"] = $overview[0]->size;
			$this->message["answered"] = $overview[0]->answered;
			$this->next_message_id++;
			return is_array($this->message);
		}else
		{
			return false;
		}
	}

	/*
		return an array with all message info.
	*/

	function get_message($uid, $sort = "", $reverse = "", $preferred_type = "html", $part="")
	{
		if ($this->conn)
		{
			unset($this->message);

			//determine next and previous message
			$this->sort($sort);

			for ($i=0;$i<sizeof($this->messages);$i++)
			{
				if ($uid == $this->messages[$i])
				{
					$this->message["next"] = ($i - 1 >= 0) ? $this->messages[$i - 1] : 0;
					$this->message["previous"] = ($i + 1 < sizeof($this->messages)) ? $this->messages[$i + 1] : 0;
					break;
				}
			}

			$headerinfo = imap_header($this->conn, imap_msgno($this->conn, $uid));

			$this->message["udate"] = $headerinfo->udate;
			if ((($headerinfo->Unseen == 'U') || ($headerinfo->Recent == 'N')) && $this->is_imap())
			{
				$this->message["new"] = "1";
			}else
			{
				$this->message["new"] = "0";
			}

			$tmp = $headerinfo->from;
			$this->message["sender"] = $tmp[0]->mailbox.'@'.$tmp[0]->host;

			$from = isset($tmp[0]->personal) ? $tmp[0]->personal : $this->message["sender"];

			$tmp = imap_mime_header_decode($from);
			$this->message["from"] = htmlspecialchars($tmp[0]->text);

			if (isset($headerinfo->Subject))
			{
				$tmp = imap_mime_header_decode($headerinfo->Subject);
				$this->message["subject"] = isset($tmp[0]->text) ? htmlspecialchars($tmp[0]->text) : '';
			}

			if (isset($headerinfo->to))
			{
				$tmp = $headerinfo->to;
				for ($x=0;$x<sizeof($tmp);$x++) {
					$personal = isset($tmp[$x]->personal) ? htmlspecialchars($tmp[$x]->personal).' ' : '';
					$email = htmlspecialchars($tmp[$x]->mailbox . "@" . $tmp[$x]->host);
					$this->message["to"][$x] = $personal. "&lt;".$email. "&gt;";
				}
			}
			if (isset($headerinfo->cc))
			{
				$tmp = $headerinfo->cc;
				for ($x=0;$x<sizeof($tmp);$x++) {
					$personal = isset($tmp[$x]->personal) ? $tmp[$x]->personal.' ' : '';
					$email = $tmp[$x]->mailbox . "@" . $tmp[$x]->host;
					$this->message["cc"][$x] = htmlspecialchars($personal) . "&lt;" . htmlspecialchars($email) . "&gt;";
				}
			}

			if (isset($headerinfo->bcc))
			{
				$tmp = $headerinfo->bcc;
				for ($x=0;$x<sizeof($tmp);$x++) {
					$personal = isset($tmp[$x]->personal) ? $tmp[$x]->personal.' ' : '';;
					$email = $tmp[$x]->mailbox . "@" . $tmp[$x]->host;
					$this->message["bcc"][$x] = htmlspecialchars($personal) . "&lt;" . htmlspecialchars($email) . "&gt;";
				}
			}


			$this->message["parts"] = array();

			if ($part == '')
			{
				$structure = imap_fetchstructure($this->conn, $uid, FT_UID);
			}else
			{
				$structure = imap_bodystruct($this->conn, imap_msgno($this->conn, $uid), $part);
			}
			$this->mail["parts"] = array();
			$this->get_parts($structure, $preferred_type);

			//$this->print_structure($structure);

			$header = imap_fetchheader($this->conn, $uid, FT_UID);
			$this->message["priority"] = $this->get_header_value("X-Priority:", $header);
			$this->message["notification"] = $this->get_header_value("Disposition-Notification-To:", $header);
			$this->message["header"] = $header;

			$overview = imap_fetch_overview($this->conn, $uid, FT_UID);
			$this->message["flagged"] = $overview[0]->flagged;
			$this->message["size"] = $overview[0]->size;
			$this->message["answered"] = $overview[0]->answered;

			return $this->message;

		}else
		{
			return false;
		}
	}

	/*
		private function to get all the parts from a message.
	*/

	function get_parts($mimeobj,$preferred_type = "html", $section = 0)
	{
		if (isset($mimeobj->type))
		{
			$type = $this->get_mime_type($mimeobj->type);
		}else
		{
			$type = 'text';
		}

		$full_mime_type = $type ."/".$mimeobj->subtype;
	 	$encoding = $this->get_encoding($mimeobj->encoding);

		if (isset($mimeobj->parameters))
		{
			$params = $mimeobj->parameters;
			for ($x=0;$x<count($params);$x++)
			{
				$param = $params[$x];
				if ((strtolower($param->attribute) == 'name') && ($param->value != ''))
				{
					$name = $param->value;
					break;
				}
			}
		}
		$name = isset($name) ? $name : '';

		if (!isset($name) && isset($mimeobj->dparameters))
		{
			$params = $mimeobj->dparameters;
			for ($x=0;$x<count($params);$x++)
			{
				$param = $params[$x];
				if ((strtolower($param->attribute) == 'filename') && ($param->value != ''))
				{
					$name = $param->value;
					break;
				}
			}
		}
		$x=0;
		if (isset($mimeobj->parts))
		{
			for($x=0;$x<count($mimeobj->parts);$x++)
			{
				if ($mimeobj->subtype == "ALTERNATIVE" && $preferred_type == "html") $x++;

				// If we are in the root of the object increment by whole integers

				if($section == 0)
				{
					$nsection = $x + 1;
				}else if(($pos = strrpos($section, ".")) && $mimeobj->parts[0]->type != TYPEMULTIPART)
				{
					$subsection = (int) substr($section, $pos+1)+$x;
					if ($subsection == '')
					{
						$subsection = '0';
					}
					$nsection = substr($section, 0, $pos) .	"." . ($subsection + 1);
				}else
				{
					$nsection = $section;
				}

				// If there are more parts to the part about to be processed reference it as a header with ".0"
				// but only if the child of this child isn't MULTIPART

				if(isset($mimeobj->parts[$x]->parts))
				{
					// Funny really, if a mime section is a inline message that has a multipart body you reference the message
					// mime section with "2" the inline message header with "2.0" and the subsections with	"2.x"
					// However if the mime section is a inline message with only 1 part then you reference the
					// mime section in the message with 2.0 and the	inline message body with 2.1

					if(!($mimeobj->parts[$x]->type == TYPEMESSAGE && $mimeobj->parts[$x]->parts[0]->type ==	TYPEMULTIPART))
					{
						$nsection .= ".0";
					}else
					{
						$nsection .= "";
					}
				}

				$this->get_parts($mimeobj->parts[$x],$preferred_type, $nsection);

				if ($mimeobj->subtype == "ALTERNATIVE" && $preferred_type == "plain") $x++;

			}
		}

		// If after processing the entire MIME object the $x variable is still zero then we didn't
		// process a multipart mime message.

		if($x == 0 && $section == 0)
		{
		 	$section = "1";
		}

		if ($type != "multipart" && $full_mime_type)
		{
			if (eregi('message', $full_mime_type))
			{
				$section++;
			}
			$tmp = Array(
				'number' => $section,
				'id' => $mimeobj->ifid ? $mimeobj->id : 0,
				'name' => $name,
				'mime' => $full_mime_type,
				'transfer' => $encoding,
				'disposition' => $mimeobj->ifdisposition ? $mimeobj->disposition : '',
				'size' => $mimeobj->bytes
				);

			array_unshift($this->message["parts"], $tmp);
		}

	}

	function print_structure($mimeobj, $depth = 0, $section = 0)
	{

	        for($y = 0; $y < $depth; $y++)
	        {
				echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
			}
			echo($this->get_mime_type($mimeobj->type) . "/{$mimeobj->subtype},");
			echo($this->get_encoding($mimeobj->encoding) . "(<B>$section</B>)<br>");

	        for($x = 0; $x < count($mimeobj->parts); $x++)
	        {
	                // If we are in the root of the object increment by whole	integers

	                if($section == 0)
	                {
						$nsection = $x + 1;
						$subsection = 0;
	                	// If we are in the object and the first sub-object of our	object isn't multipart
		                // then increment the postfix by ".1" otherwise	we are multipart or a message
		                // and leave the section id alone to be handled by the next	code block

	                    //else if(($pos = strrpos($section, ".")) && sizeof($mimeobj->parts) > 1)
					}else if(($pos = strrpos($section, ".")) && $mimeobj->parts[0]->type != TYPEMULTIPART)
					//}elseif($pos = strrpos($section, "."))
					{
						$subsection = (int) substr($section, $pos+1)+$x;
						if ($subsection == '')
						{
							$subsection = '0';
						}
						$nsection = substr($section, 0, $pos) .	"." . ($subsection + 1);
					}else
					{
						$nsection = $section;

					}

	                // If there are more parts to the part about to be processed reference it as a header with ".0"
	                // but only if the child of this child isn't MULTIPART

	                if(count($mimeobj->parts[$x]->parts))
	                {
	                        // Funny really, if a mime section is a inline message that has a multipart body you reference the message
	                        // mime section with "2" the inline	message header with "2.0" and the subsections with	"2.x"
	                        // However if the mime section is a inline message with only 1 part then you reference the
	                        // mime section in the message with 2.0 and the	inline message body with 2.1

	                        if(!($mimeobj->parts[$x]->type == TYPEMESSAGE && $mimeobj->parts[$x]->parts[0]->type ==	TYPEMULTIPART))
	                                $nsection .= ".0";
	                        else
	                                $nsection .= "";
					}

	                $this->print_structure($mimeobj->parts[$x], $depth + 1, $nsection);

	        }

	        // If after processing the entire MIME object the $x variable is still zero then we didn't
	        // process a multipart mime message, it's just normal email so say so here.

	        if($x == 0 && $section == 0)
	        {
	                echo($this->get_mime_type($mimeobj->type) . "/{$mimeobj->subtype}, ");
	                echo($this->get_encoding($mimeobj->encoding) . "(<B>1</B>) (<B>NOT MIME MULTIPART</B>)<br>");
	        }
	}


	/*
		private function to get mimetype in text
	*/

	function get_encoding($encoding)
	{
		switch ($encoding)
		{
			case 0:
				$encoding = '7BIT';
				break;
			case 1:
				$encoding = '8BIT';
				break;
			case 2:
				$encoding = 'BINARY';
				break;
			case 3:
				$encoding = 'BASE64';
				break;
			case 4:
				$encoding = 'QUOTED-PRINTABLE';
				break;
			case 5:
				$encoding = 'OTHER';
				break;
			default:
				$encoding = 'none';
				break;
		}

		return $encoding;
	}

	/*
		private function to get encoding in text
	*/

	function get_mime_type($type)
	{
		switch ($type)
		{
			case 0:
				$mime_type = 'text';
				break;
			case 1:
				$mime_type = 'multipart';
				break;
			case 2:
				$mime_type = 'message';
				break;
			case 3:
				$mime_type = 'application';
				break;
			case 4:
				$mime_type = 'audio';
				break;
			case 5:
				$mime_type = 'image';
				break;
			case 6:
				$mime_type = 'video';
				break;
			case 7:
				$mime_type = 'other';
				break;
			default:
				$mime_type = 'unknown';
		}
		return $mime_type;
	}


	function get_header_value($fieldname, $header)
	{
		$resu = '';
		$header = eregi_replace("\t", " ", $header);
		$results = array();
		if (eregi("$fieldname (.*)", $header, $results)) {
			$fieldval = $results[1];
			for ($b=0;$b<=strlen($fieldval);$b++) {
				$curr = substr($fieldval, $b, 1);
				$next = substr($fieldval, $b + 1, 1);
				if ($curr == "\n" && $next != " ") {
					break;
				}
				if ($curr == "\t") { $curr = " "; }
				if ($curr == "\n") { $curr = ""; }
				$resu .= $curr;
			}
		}
		$resu = eregi_replace("\([^\)]*\)", "", $resu);
		return trim($resu);
	}

	function delete($messages)
	{
		for ($i=0;$i<count($messages);$i++)
		{
			@imap_delete($this->conn,$messages[$i], FT_UID);
		}
		@imap_expunge($this->conn);
	}

	function view_part($uid, $part_no, $transfer)
	{
		$text = imap_fetchbody($this->conn, $uid, $part_no, FT_UID);
		if ($transfer == 'BASE64')
			$str = imap_base64($text);
		elseif($transfer == 'QUOTED-PRINTABLE')
			$str = quoted_printable_decode($text);
		else
			$str = $text;

		return ($str);
	}


	function f($name)
	{
		$value = isset($this->message[$name]) ? $this->message[$name] : false;
		return $value;
	}


	function get_mailboxes()
	{
			$this->mailboxes=array();
			$list = imap_getmailboxes($this->conn,"{".$this->connectstring."}","%");
			if(is_array($list))
			{
					while (list($key, $val) = each($list))
					{
			if (imap_utf7_decode($val->attributes) != LATT_NOSELECT)
			{
						$this->mailboxes[] = str_replace("{".$this->connectstring."}","",imap_utf7_decode($val->name));
			}
					}
					reset($list);

			}

			return $this->mailboxes;
	}

	function get_subscribed()
	{
		$this->mailboxes=array();

		$list = imap_getsubscribed($this->conn,"{".$this->connectstring."}","%");
		if(is_array($list))
		{
			while (list($key, $val) = each($list))
			{
					$this->mailboxes[] = str_replace("{".$this->connectstring."}","",imap_utf7_decode($val->name));
			}
			reset($list);

		}

		return $this->mailboxes;
	}

	function is_subscribed($name)
	{
		$this->get_subscribed();
		for ($i=0;$i<count($this->mailboxes);$i++)
		{
			if ($this->mailboxes==$name)
			{
				return true;
			}
		}
		return false;
	}

	function subscribe($name)
	{
		return imap_subscribe($this->conn, "{".$this->connectstring."}".imap_utf7_encode($name));
	}

	function unsubscribe($name)
	{
		return imap_unsubscribe($this->conn, "{".$this->connectstring."}".imap_utf7_encode($name));
	}

	function delete_folder($name)
	{
		if ($this->is_subscribed($name))
		{
			if ($this->unsubscribe($name))
			{
				return imap_deletemailbox($this->conn,"{".$this->connectstring."}".imap_utf7_encode($name));
			}
			return false;
		}else
		{
			return imap_deletemailbox($this->conn,"{".$this->connectstring."}".imap_utf7_encode($name));
		}

	}

	function create_folder($name)
	{
		if (imap_createmailbox($this->conn,"{".$this->connectstring."}".imap_utf7_encode($name)))
		{
			return $this->subscribe($name);
		}
	}

	function rename_folder($old_name, $new_name)
	{
		if ($this->unsubscribe($old_name))
		{
			if (imap_renamemailbox($this->conn,"{".$this->connectstring."}".$old_name,"{".$this->connectstring."}".$new_name))
			{
				return $this->subscribe($new_name);
			}
		}
		return false;
	}

	function move($folder, $messages)
	{
		$messageset = implode (",",$messages);
		if (imap_mail_move($this->conn,$messageset,$folder, CP_UID))
		{
			imap_expunge($this->conn);
			return true;
		}
		return false;
	}

	function get_quota()
	{
		return imap_get_quota($this->conn,"user.".$this->username);
	}

	function append_message($mailbox, $body, $flags = "")
	{
		if(@imap_append($this->conn, "{".$this->connectstring."}".$mailbox, $body, $flags))
		{
			return true;
		}else
		{
			return false;
		}
	}
}
?>
