<?php
/*
 * $Header: /cvsroot/nocc/nocc/webmail/smtp.class.inc,v 1.24 2001/11/03 16:51:28 rossigee Exp $
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * Class based on a work from Unk <rgroesb_garbage@triple-it_garbage.nl>
 */
class smtp
{
	var $smtp_server;
	var $port;
	var $from;
	var $to;
	var $cc;
	var $bcc;
	var $subject;
	var $data;
	var $sessionlog = '';

	// This function is the constructor don't forget this one
	function smtp()
	{
		$this->smtp_server = '';
		$this->port = '';
		$this->from = '';
		$this->to = Array();
		$this->cc = Array();
		$this->bcc = Array();
		$this->subject = '';
		$this->data = '';
	}

	function smtp_open()
	{
		global $SMTP_GLOBAL_STATUS;
		$smtp = fsockopen($this->smtp_server, $this->port, $errno, $errstr);
		if (!$smtp)
			return false;

		$line = fgets($smtp, 1024);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] !=  '2')
			return (false);

		return $smtp;
	}

	function smtp_helo($smtp)
	{
		global $SMTP_GLOBAL_STATUS;

		/* 'localhost' always works [Unk] */
		fputs($smtp, "helo localhost\r\n");
		$this->sessionlog .= "Sent: helo localhost";
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";

		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] !=  '2')
			return (false);

		return (true);
	}

	function smtp_ehlo($smtp)
	{
		global $SMTP_GLOBAL_STATUS;

		/* Well, let's use "helo" for now.. Until we need the
		  extra func's   [Unk]
		*/
		fputs($smtp, "ehlo localhost\r\n");
		$this->sessionlog .= "Sent: ehlo localhost";
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '2')
			return (false);

		return (true);
	}


	function smtp_mail_from($smtp)
	{
		global $SMTP_GLOBAL_STATUS;
		fputs($smtp, "MAIL FROM:$this->from\r\n");
		$this->sessionlog .= "Sent: MAIL FROM:$this->from";
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";

		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '2')
			return (false);

		return (true);
	}

	function smtp_rcpt_to($smtp)
	{
		global $SMTP_GLOBAL_STATUS;

		// Modified by nicocha to use to, cc and bcc field
		while ($tmp = array_shift($this->to))
		{
			if($tmp == '' || $tmp == '<>')
				continue;
			fputs($smtp, "RCPT TO:$tmp\r\n");
			$this->sessionlog .= "Sent: RCPT TO:$tmp";
			$line = fgets($smtp, 1024);
			$this->sessionlog .= "Rcvd: $line";

			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);
			if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '2')
				return (false);
		}
		while ($tmp = array_shift($this->cc))
		{
			if($tmp == '' || $tmp == '<>')
				continue;
			fputs($smtp, "RCPT TO:$tmp\r\n");
			$this->sessionlog .= "Sent: RCPT TO:$tmp";
			$line = fgets($smtp, 1024);
			$this->sessionlog .= "Rcvd: $line";
			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

			if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '2')
				return (false);
		}
		while ($tmp = array_shift($this->bcc))
		{
			if($tmp == '' || $tmp == '<>')
				continue;
			fputs($smtp, "RCPT TO:$tmp\r\n");
			$this->sessionlog .= "Sent: RCPT TO:$tmp";
			$line = fgets($smtp, 1024);
			$this->sessionlog .= "Rcvd: $line";
			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
			$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

			if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '2')
				return (false);
		}
		return (true);
	}

	function smtp_data($smtp)
	{
		global $SMTP_GLOBAL_STATUS;

		fputs($smtp, "DATA\r\n");
		$this->sessionlog .= "Sent: DATA";
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";

		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] <>  '3')
			return (false);

		fputs($smtp, "$this->data");
		fputs($smtp, "\r\n.\r\n");
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";
		if (substr($line, 0, 1) !=  '2')
			return (false);

		return (true);
	}

	function smtp_quit($smtp)
	{
		global $SMTP_GLOBAL_STATUS;

		fputs($smtp,  "QUIT\r\n");
		$this->sessionlog .= "Sent: QUIT";
		$line = fgets($smtp, 1024);
		$this->sessionlog .= "Rcvd: $line";

		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] = substr($line, 0, 1);
		$SMTP_GLOBAL_STATUS[$smtp]['LASTRESULTTXT'] = substr($line, 0, 1024);

		if ($SMTP_GLOBAL_STATUS[$smtp]['LASTRESULT'] !=  '2')
			return (false);

		return (true);
	}

	function send()
	{
		$smtp = $this->smtp_open();
		if(!$smtp)
			return (false);
		if(!$this->smtp_helo($smtp))
			return (false);
		if(!$this->smtp_mail_from($smtp))
			return (false);
		if(!$this->smtp_rcpt_to($smtp))
			return (false);
		if(!$this->smtp_data($smtp))
			return (false);
		if(!$this->smtp_quit($smtp))
			return (false);

		return (true);
	}
}
?>
