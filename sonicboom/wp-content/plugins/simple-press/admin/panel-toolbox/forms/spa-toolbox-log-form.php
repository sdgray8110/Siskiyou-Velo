<?php
/*
Simple:Press
Admin Toolbox Uninstall Form
$LastChangedDate: 2011-09-04 17:43:47 -0700 (Sun, 04 Sep 2011) $
$Rev: 6968 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_toolbox_log_form() {
	$sflog = spa_get_log_data();

#== log Tab ==========================================================

	spa_paint_open_tab(spa_text('Toolbox')." - ".spa_text('Install Log'));

			if (!$sflog) {
				spa_etext("There are no Install Log Entries");
				return;
			}

			spa_paint_open_fieldset(spa_text('Install Log'), false, '', true);
				echo "<tr>";
				echo "<th>".spa_text('Version')."</th>";
				echo "<th>".spa_text('Build')."</th>";
				echo "<th>".spa_text('Release')."</th>";
				echo "<th>".spa_text('Installed')."</th>";
				echo "<th>".spa_text('By')."</th>";
				echo "</tr>";

				foreach ($sflog as $log) {
					echo "<tr>";
					echo "<td class='sflabel'>".$log['version']."</td>";
					echo "<td class='sflabel'>".$log['build']."</td>";
					echo "<td class='sflabel'>".$log['release_type']."</td>";
					echo "<td class='sflabel'>".sp_date('d', $log['install_date'])."</td>";
					echo "<td class='sflabel'>".sp_filter_name_display($log['display_name'])."</td>";
					echo "</tr>";
				}
			spa_paint_close_fieldset();
		spa_paint_close_panel();
		do_action('sph_toolbox_install_panel');
	spa_paint_close_tab();
}
?>