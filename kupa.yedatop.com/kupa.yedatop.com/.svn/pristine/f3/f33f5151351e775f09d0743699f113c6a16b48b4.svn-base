<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0  Release date: 14 March 2003									//
// 			1.01 Release date:  8 April 2003									//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if (isset($delete_type))
{
	$filetypes->delete_type($delete_type);
}

$max_rows = 15;
$first = isset($first) ? $first : 0;
?>

<table border="0" cellpadding="10" cellspacing="0" width="100%">
<tr>
        <td>
        <?php
        $count = $filetypes->get_filetypes($first, $max_rows);

        if ($count>0)
        {
                ?>
				<h1><?php echo $ft_title; ?></h1>
                <table border="0" cellspacing="0" cellpadding="3" width="600">

                <tr height="16">
					<td class="TableHead2" width="16">
					</td>
					<td class="TableHead2">
					<?php echo $ft_extension; ?>
					</td>
					<td class="TableHead2">
					<?php echo $ft_content; ?>
					</td>
					<td class="TableHead2">
					<?php echo $ft_friendly; ?>
					</td>
					<td class="TableHead2">&nbsp;</td>
					<td class="TableHead2">&nbsp;</td>
                </tr>

                <?php

                while ($filetypes->next_record())
                {
					echo '<tr><td><img src="icon.php?extension='.$filetypes->f("extension").'" border="0" height="16" width="16" /></td>';
					echo '<td>'.$filetypes->f("extension").'</td>';
					echo '<td>'.$filetypes->f("mime").'</td>';
					echo '<td>'.$filetypes->f("friendly").'</td>';
					echo '<td><a href="'.$PHP_SELF.'?task=filetype&extension='.$filetypes->f("extension").'&first='.$first.'" title="'.$strEdit.' '.$ft_friendly.'"><img src="'.$GO_THEME->image_url.'buttons/edit.gif" border="0" /></a></td>';
					echo "<td><a href='javascript:confirm_action(\"".$PHP_SELF."?delete_type=".$filetypes->f("extension")."&first=".$first."\",\"".rawurlencode($strDeletePrefix."'".$filetypes->f("extension")."'".$strDeleteSuffix)."\")' title=\"".$strDeleteItem." '".$filetypes->f("extension")."'\"><img src=\"".$GO_THEME->image_url."buttons/delete.gif\" border=\"0\"></a></td></tr>\n";
					echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
                }

				$links = '';
				if ($count > $max_rows)
				{
					$next_start = $first+$max_rows;
					$previous_start = $first-$max_rows;
					if ($first != 0)
					{
						$links .= '<a href="'.$PHP_SELF.'?first='.$previous_start.'">&lt&lt '.$cmdPrevious.'</a>&nbsp;';
					}else
					{
						$links .= '<font color="#cccccc">&lt&lt '.$cmdPrevious.'</font>&nbsp;';
					}
					for ($i=0;$i<$count;$i+=$max_rows)
					{
						$page = ($i/$max_rows)+1;
						if ($i==$first)
						{
							$links .= '<b><i>'.$page.'</i></b>&nbsp;';
						}else
						{
							$links .= '<a href="'.$PHP_SELF.'?first='.$i.'">'.$page.'</a>&nbsp;';
						}
					}


					if ($count > $next_start)
					{
						$links .= '<a href="'.$PHP_SELF.'?first='.$next_start.'">'.$cmdNext.' &gt&gt</a>';
					}else
					{
						$links .= '<font color="#cccccc">'.$cmdNext.' &gt&gt</font>';
					}
				}
				echo '<tr><td colspan="99"align="center">'.$links.'</td></tr>';
				echo '<tr><td colspan="99" height="1"><img src="'.$GO_THEME->image_url.'cccccc.gif" border="0" height="1" width="100%" /></td></tr>';
                echo '</table>';
        }else
        {
                echo '<p align="center">'.$ft_no_filetypes.'</p>';
        }
        ?>
        </td>
</tr>
</table>

