<?php
/*
 * $Header: /cvsroot/nocc/nocc/webmail/sendmail.class.inc,v 1.44 2001/11/16 12:06:21 rossigee Exp $
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

class mime_mail
{
	var $parts;
	var $to;
	var $cc;
	var $bcc;
	var $from;
	var $headers;
	var $subject;
	var $body;
	var $smtp_server;
	var $smtp_port;
	var $charset;
	var $crlf;
	var $priority;
	var $sender_name;
    var $mime;
	var $body_ctype;

  /*
  *     void mime_mail()
  *     class constructor
  */
	function mime_mail()
	{
		$this->parts = Array();
		$this->to =  Array();
		$this->cc = Array();
		$this->bcc = Array();
		$this->from =  null;
		$this->headers = null;
		$this->subject =  null;
		$this->body =  null;
		$this->smtp_server =  '';
		$this->smtp_port = '';
		$this->charset = 'iso-8859-1';
		$this->crlf = null;
		$this->priority = '3 (Normal)';
		$this->body_ctype = "text/plain";
	}

  /*
  *     void add_attachment(string message, [string name], [string ctype], [string encoding], [string charset])
  *     Add an attachment to the mail object
  */
	function add_attachment($message, $name, $ctype, $encoding, $charset, $content_id = '')
	{
	        $this->parts[] = array(
                        'ctype' => $ctype,
                        'message' => $message,
                        'encoding' => $encoding,
						'charset' => $charset,
                        'name' => $name,
                     	'Content-ID' => $content_id);
	}

/*
 *      void build_message(array part)
 *      Build message parts of a multipart mail
 */
	function build_message($part)
	{
		$message = $part['message'];
		$encoding = $part['encoding'];
		$charset = $part['charset'];
		switch($encoding)
		{
			case 'base64':
				$message = chunk_split(base64_encode($message));
				break;
			case 'quoted-printable':
				$message = imap_8bit($message);
				break;
			default:
				break;
		}
		$val = 'Content-Type: ' . $part['ctype'] . ';';
		$val .= ($part['charset'] ? $this->crlf . "\tcharset=\"" . $part['charset'] . '"' : '');
		$val .= ($part['name'] ? $this->crlf . "\tname=\"" . $part['name'] . '"' : '');
		$val .= $this->crlf . 'Content-Transfer-Encoding: ' . $encoding;
		if ($part['Content-ID'] != '')
		{
			$val .= $this->crlf.'Content-ID: '.$part['Content-ID'];
		}else
		{
			$val .= ($part['name'] ? $this->crlf . 'Content-Disposition: attachment;' . $this->crlf . "\tfilename=\"" . $part['name'] . '"' : '');
		}
		$val .= $this->crlf . $this->crlf . $message . $this->crlf;
		return($val);
	}

/*
 *      void build_multipart()
 *      Build a multipart mail
 */
	function build_multipart()
	{
		$boundary = 'NextPart'.md5(uniqid(time()));
		$multipart = 'Content-Type: multipart/mixed;' . $this->crlf . "\tboundary = $boundary" . $this->crlf . $this->crlf . 'This is a MIME encoded message.' . $this->crlf . $this->crlf . '--' . $boundary;

		for($i = sizeof($this->parts) - 1; $i >= 0; $i--)
			$multipart .= $this->crlf . $this->build_message($this->parts[$i]) . '--'.$boundary;
		return ($multipart .= '--' . $this->crlf);
	}

/*
 *		void build_body()
 *		build a non multipart mail
*/

	function build_body()
	{
		if (sizeof($this->parts) == 1)
			$part = $this->build_message($this->parts[0]);
		else
			$part = '';
		return ($part . $this->crlf);
	}

/*
 *      void send()
 *      Send the mail (last class-function to be called)
 */
	function send()
	{
		$this->mime = '';
		$mime = '';

		if (isset($this->to[0]))
		{
			$this->mime .= 'To: <'.$this->to[0].'>';
			for ($i=1;$i<count($this->to);$i++)
			{
				$this->mime .= ', <'.$this->to[$i].'>';
			}
			$this->mime .= $this->crlf;
		}
		if (!empty($this->subject))
			$this->mime .= 'Subject: ' . $this->subject . $this->crlf;



		if (!empty($this->from))
		{
			$mime .= "From: \"".$this->sender_name."\" <".$this->from .">". $this->crlf;
		}
		if (isset($this->cc[0]))
		{
			$mime .= 'Cc: <'.$this->cc[0].'>';
			for ($i=1;$i<count($this->cc);$i++)
			{
				$mime .= ', <'.$this->cc[$i].'>';
			}
			$mime .= $this->crlf;

		}
		if (isset($this->bcc[0]))
		{
			$mime .= 'Bcc: <'.$this->bcc[0].'>';
			for ($i=1;$i<count($this->bcc);$i++)
			{
				$mime .= ', <'.$this->bcc[$i].'>';
			}
			$mime .= $this->crlf;
		}
		if (ereg("[4-9]\.[0-9]\.[4-9].*", phpversion()))
		{
			$mime .= 'Date: ' . date("r") . $this->crlf;
		}else
		{
			$mime .= 'Date: ' . date("D, j M Y H:i:s T") . $this->crlf;
		}
		if (!empty($this->from))
		{
			$mime .= 'Reply-To: ' . $this->from . $this->crlf . 'Errors-To: '.$this->from . $this->crlf;
		}

		$mime .= 'X-Priority: ' . $this->priority . $this->crlf;

		if (!empty($this->headers))
		{
			$mime .= $this->headers . $this->crlf;
		}

		if (sizeof($this->parts) >= 1)
		{
			if (strtolower($this->body_ctype) == 'text/html')
				$encoding = 'base64';
			else
				$encoding = 'quoted-printable';

			$this->add_attachment($this->body,  '', $this->body_ctype, $encoding, $this->charset);
			$mime .= 'MIME-Version: 1.0' . $this->crlf . $this->build_multipart();
		}else
		{
			if (strtolower($this->body_ctype) == 'text/html')
				$encoding = 'base64';
			else
				$encoding = '8bit';

			$this->add_attachment($this->body,  '', $this->body_ctype, $encoding, $this->charset);
			$mime .= 'MIME-Version: 1.0' . $this->crlf . $this->build_body();
		}

		$this->mime .= $mime;

		// Whether or not to use SMTP or sendmail
		if ($this->smtp_server == '' || $this->smtp_port == '')
		{
			$rcpt_to = '';
			if (isset($this->to[0]))
			{
				$rcpt_to.= '<'.$this->to[0].'>';
				for ($i=1;$i<count($this->to);$i++)
				{
					$rcpt_to .= ', <'.$this->to[$i].'>';
				}
			}
			return (mail($rcpt_to, $this->subject,  '', $mime,"-f".$this->from));
		}else
		{

			if (($smtp = new smtp()) != 0)
			{
				$smtp->smtp_server = $this->smtp_server;
				$smtp->port = $this->smtp_port;
				$smtp->from = $this->from;
				$smtp->to = $this->to;
				$smtp->cc = $this->cc;
				$smtp->bcc = $this->bcc;
				$smtp->subject = $this->subject;
				$smtp->data = $this->mime;
				return ($smtp->send());
			}
			else
				return (false);
		}
	}

	function strip_comment_array($array) {
		for($i = 0; $i < count($array); $i++) {
			$array[$i] = $this->strip_comment($array[$i]);
		}
		return $array;
	}

	function strip_comment($address) {
		$pos = strrpos($address, '<');
		if ($pos === false) {
			return '<'.$address.'>';
		}
		else {
			return substr($address, $pos);
		}
	}

}  // end of class
?>
