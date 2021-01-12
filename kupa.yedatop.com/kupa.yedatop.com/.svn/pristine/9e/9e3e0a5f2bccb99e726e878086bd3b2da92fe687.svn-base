<?php
/*
How to create a typical Group-Office module

To see this example working open Group-office go to the menu: administrator->modules
select the module named: 'example' and click at the 'save' button.


Now go to the menu: applications->example !

SOME CODING RULES:

1. Always use '<?php' not '<?'
2. code readable functions should be put in this form:

funtion example($example_var1, $example_var2)
{
	//this is a comment
	if($example_var1)
	{
		$example_var1 = '';
	}else
	{
		$example_var2 = '';
	}

	return true;
}

3. use single quotes rather then double quotes
4. don't double code. Try to re use every single line of code on other pages.
   so when something changes there is only place to edit
5. create classes
6. Examine existing code. The classes the stylesheet. The common.Language.inc files so you won;t double code.
   Also look at other modules to get more understanding of the system
   very important classes are:
   security.class.inc
   users.class.inc
   contacts.class.inc
   modules.class.inc
   filesystem.class.inc

   also take a look at the controls:
   acl_control.inc
7. Use correct names. Includable documents should be named *.inc and normal files *.php
   classes are called: *.class.inc

8. Use the multi-language method for Group-Office see the language folder.
   While doing this know that you can always use the common language vars. See 'common.<language>.inc'.
9. When developing set php notices on in php.ini. This way you have to declare every variable and this is
   a good habit.

*/

//first add some info:

/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

/*
require the Group-Office.php file
This creates:

$GO_CONFIG - configuration settings - see GroupOffice.php
$GO_SECURITY - security class - see classes/security.class.inc
$GO_LANGUAGE - language class - not really relevant now
$GO_THEME - theme class - not really relevant now

*/
require("../../Group-Office.php");

//authenticate the user
//if $GO_SECURITY->authenticate(true); is used the user needs admin permissons

$GO_SECURITY->authenticate();

//see if the user has access to this module
//for this to work there must be a module named 'example'
$GO_MODULES->authenticate('example');

//set the page title for the header file
$page_title = "Example";
//require the header file. This will draw the logo's and the menu
// there is also the simple_header.inc file without logo's and menu
require($GO_THEME->theme_path."header.inc");
//put html in a table because margins are set to 0
?>
<table border="0" cellpadding="10">
<tr>
	<td>
	<h1>Example module for starting Group-Office developers</h1>
	<?php
	//put module code here!
	//severall control classes are available from controls.class.inc

	//the $cmdOk var comes from languages/common.Language.php. take a look at that file.
	//the strings from the file can be used everywhere inside Group-Office

	//button control
	$button = new button($cmdOk, "javascript:alert('You clicked on a Group-Office button!')");

	echo '<br /><br />';

	//dropbox control
	//always declare vars in Group-Office!
	$dropdown = isset($dropdown) ? $dropdown : '1';
	$dropbox = new dropbox();
	$dropbox->add_value('1', 'one');
	$dropbox->add_value('2', 'two');
	$dropbox->add_value('3', 'three');
	$dropbox->print_dropbox('dropdown', $dropdown);

	echo '<br /><br />';

	//or direct database link:

	//this is how you should load a class:
	require_once($GO_CONFIG->class_path.'users.class.inc');
	//create a users object:
	$users = new users();
	//this function gets all users
	$users->get_users();
	//we can now pass the users object to the dropdown box and add all the users to it
	//declare the user var
	$user = isset($user) ? $user : '0';
	$dropbox = new dropbox();
	//add the users class, use 'id' for value and 'name' for text
	$dropbox->add_sql_data('users', 'id', 'name');
	//print the dropbox
	$dropbox->print_dropbox('user',$user);

	echo '<br /><br />';

	//statusbar control
	$statusbar = new statusbar();
	$statusbar->info_text = 'Group-Office usage';
	$statusbar->turn_red_point = 90;
	$statusbar->print_bar(75, 100);

	//how do you get user info

	//the current user is stored in:
	echo 'Your user ID is "'.$GO_SECURITY->user_id.'"<br />';
	$user = $users->get_user($GO_SECURITY->user_id);
	echo 'Your name is "'.$user['name'].'"<br /><br />';

	//now for some permission management.
	//You can secure an object by giving it an ACL (Access Control List). When the user
	//you logged in with was created it also got an ACL. This acl is used to protect your personal profile.
	//We already got the user information in the above example so the user acl = stored in $user['acl_id'].
	//So if we want to set the permissions this can be done really easily with the control: 'acl_control.inc'

	echo 'This user is visible to:<br />';

	//The acl_control must always be put in a form.
	echo '<form name="save" method="post" action="'.$PHP_SELF.'">';
	//we set the following for the control
	$acl_control_acl_id = $user["acl_id"];
	//we hide ourself because we do not need to protect ourself from us.
	$acl_control_hide_self = true;
	//TIP: look in this file for more options.

	//we actually require the control here
	require($GO_CONFIG->control_path."acl_control.inc");
	//and we need a buttton to update this and that's all what's to it.
	$button = new button($cmdSave, 'javascript:document.forms[0].submit()');

	//When you are creating your own secured objects you just call th e function: $GO_SECURITY->get_new_acl()
	//to create a new ACL.

	echo '<br /><br />';

	echo '<p><b>A dropbox filled with grouped contacts</b></p>';

	//an advanced dropbox example with contacts. You will need to have some contacts and contact groups to
	//see this in action.
	require_once($GO_CONFIG->class_path.'contacts.class.inc');
	$contacts = new contacts();
	$contact_id = isset($contact_id) ? $contact_id : $GO_SECURITY->user_id;

	$contact_groups = new contacts;
	$dropbox = new dropbox();
	$contact_groups->get_groups($GO_SECURITY->user_id);
	while ($contact_groups->next_record())
	{
		$dropbox->add_optgroup($contact_groups->f('name'));
		$contacts->get_contacts_group($GO_SECURITY->user_id, $contact_groups->f('id'));
		$dropbox->add_sql_data('contacts', 'id', 'name');
	}
	$dropbox->add_optgroup('Other contacts');
	$contacts->get_contacts_group($GO_SECURITY->user_id, 0);
	$dropbox->add_sql_data('contacts', 'id', 'name');
	$dropbox->print_dropbox('contact_id', $contact_id);

	?>
	</td>
</tr>
</table>
<?php
//require the footer file
require($GO_THEME->theme_path."footer.inc");
?>

