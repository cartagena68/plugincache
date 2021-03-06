<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
        if(Params::getParam('plugin_action')=='done') {
        osc_set_preference('search_time', Params::getParam('search_time'), 'plugincache', 'INTEGER');
        osc_set_preference('item_time', Params::getParam('item_time'), 'plugincache', 'INTEGER');
        osc_set_preference('main_time', Params::getParam('main_time'), 'plugincache', 'INTEGER');
        osc_set_preference('static_time', Params::getParam('static_time'), 'plugincache', 'INTEGER');
        osc_set_preference('main_cache', Params::getParam('main_cache'), 'plugincache', 'INTEGER');
        osc_set_preference('item_cache', Params::getParam('item_cache'), 'plugincache', 'INTEGER');
        osc_set_preference('search_cache', Params::getParam('search_cache'), 'plugincache', 'INTEGER');
        osc_set_preference('static_cache', Params::getParam('static_cache'), 'plugincache', 'INTEGER');
		osc_set_preference('posted_item_clean_cache', Params::getParam('posted_item_clean_cache'), 'plugincache', 'INTEGER');
        osc_set_preference('item_storage_folder', Params::getParam('item_storage_folder'), 'plugincache', 'STRING');
        echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('Congratulations. The plugin is now configured', 'plugincache') . '.<a href="#" title="Close Message" onclick="parentNode.remove()" style="float:right;font-weight:bold;padding-right:50px;color:#FFFFFF;">'. __('x', 'plugincache').'</a></p></div>';
        osc_reset_preferences();
        } else if(Params::getParam('plugin_action')=='clear') {
        if(Params::getParam('item')==1) {
                plugincache_clear_item();
        }
        if(Params::getParam('static')==1) {
                plugincache_clear_static();
        }
        if(Params::getParam('search')==1) {
                plugincache_clear_search();
        }
        if(Params::getParam('main')==1) {
                plugincache_clear_main();
        }
        echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('The selected cache has been deleted', 'plugincache') . '.<a href="#" title="Close Message" onclick="parentNode.remove()" style="float:right;font-weight:bold;padding-right:50px;color:#FFFFFF;">'. __('x', 'plugincache').'</a></p></div>' ;
        }
?>

<div>
	<div id="settings_form" style="width:100%; height:30px; padding:5px; border:1px solid #CCC; border-radius:5px; background:#EEE; font-size:18px; font-weight:bold;">
													<legend><?php _e('Cache Plugin Settings', 'plugincache'); ?></legend>
	</div>

	<div style="padding:10px 10px 10px 0px;">
		<div style="float: left; width: 100%;">
			<table width="100%">
				<tr>
					<td style="width:50%; border:1px solid #B3B3B3; border-radius: 5px; padding:10px;">
						<fieldset>
							<form name="plugincache_form" id="plugincache_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
								<div style="float: left; width: 100%;">
									<input type="hidden" name="page" value="plugins" />
									<input type="hidden" name="action" value="renderplugin" />
									<input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>conf.php" />
									<input type="hidden" name="plugin_action" value="done" />
									<label for="main_cache" style="font-weight:700;"><?php _e('Cache For Main Page', 'plugincache'); ?></label>
											<br/>
									<?php $selectMainCache = osc_get_preference('main_cache', 'plugincache') ; ?>
									<input type="radio" name="main_cache" id="main_cache" value="active" <?php if ($selectMainCache=='active') { echo ' checked'; } ?> /><span>Active</span>
									<input type="radio" name="main_cache" id="main_cache" value="inactive" <?php if ($selectMainCache=='inactive') { echo ' checked'; } ?> /><span>Inactive</span>
											<br/>
											<br/>
									<label for="item_cache" style="font-weight:700;"><?php _e('Cache For Item Page', 'plugincache'); ?></label>
											<br/>
									<?php $selectItemCache = osc_get_preference('item_cache', 'plugincache') ; ?>
									<input type="radio" name="item_cache" id="item_cache" value="active" <?php if ($selectItemCache=='active') { echo ' checked'; } ?> /><span>Active</span>
									<input type="radio" name="item_cache" id="item_cache" value="inactive" <?php if ($selectItemCache=='inactive') { echo ' checked'; } ?> /><span>Inactive</span>
											<br/>
											<br/>
									<label for="search_cache" style="font-weight:700;"><?php _e('Cache For Search Page', 'plugincache'); ?></label>
											<br/>
									<?php $selectSearchCache = osc_get_preference('search_cache', 'plugincache') ; ?>
									<input type="radio" name="search_cache" id="search_cache" value="active" <?php if ($selectSearchCache=='active') { echo ' checked'; } ?> /><span>Active</span>
									<input type="radio" name="search_cache" id="search_cache" value="inactive" <?php if ($selectSearchCache=='inactive') { echo ' checked'; } ?> /><span>Inactive</span>
											<br/>
											<br/>
									<label for="static_cache" style="font-weight:700;"><?php _e('Cache For Static Page', 'plugincache'); ?></label>
											<br/>
									<?php $selectStaticCache = osc_get_preference('static_cache', 'plugincache') ; ?>
									<input type="radio" name="static_cache" id="static_cache" value="active" <?php if ($selectStaticCache=='active') { echo ' checked'; } ?> /><span>Active</span>
									<input type="radio" name="static_cache" id="static_cache" value="inactive" <?php if ($selectStaticCache=='inactive') { echo ' checked'; } ?> /><span>Inactive</span>
											<br/>
											<br/>
									<hr style="height:1px; border:none; color:#DDD; background-color:#DDD; border-color:#DDD; margin:20px 0;">
								</div>
								
								<div>
									<label for="posted_item_clean_cache" style="font-weight:700;"><?php _e('Clear main and search cache when a new ad is posted.<br/>(Is better to set "Disabled" for sites with many new ads added daily)', 'plugincache'); ?></label>
										<br/>
									<?php $selectposteditemcleancache = osc_get_preference('posted_item_clean_cache', 'plugincache') ; ?>
									<input type="radio" name="posted_item_clean_cache" id="posted_item_clean_cache" value="active" <?php if ($selectposteditemcleancache=='active') { echo ' checked'; } ?> /><span>Enabled</span>
									<input type="radio" name="posted_item_clean_cache" id="posted_item_clean_cache" value="inactive" <?php if ($selectposteditemcleancache=='inactive') { echo ' checked'; } ?> /><span>Disabled</span>
										<br/>
												
									<hr style="height:1px; border:none; color:#DDD; background-color:#DDD; border-color:#DDD; margin:20px 0;">
								</div>

								<div>
									<label for="item_storage_folder" style="font-weight:700;"><?php _e('Select how you want to split the item folder<br/>(Based on item publication date)', 'plugincache'); ?></label>
										<br/>
									<?php $selectitemstoragefolder = osc_get_preference('item_storage_folder', 'plugincache') ; ?>
									<input type="radio" name="item_storage_folder" id="item_storage_folder" value="Y" <?php if ($selectitemstoragefolder=='Y') { echo ' checked'; } ?> /><span>By Year</span>
									<input type="radio" name="item_storage_folder" id="item_storage_folder" value="Y-m" <?php if ($selectitemstoragefolder=='Y-m') { echo ' checked'; } ?> /><span>By Month</span>
									<input type="radio" name="item_storage_folder" id="item_storage_folder" value="Y-m-d" <?php if ($selectitemstoragefolder=='Y-m-d') { echo ' checked'; } ?> /><span>By Day</span>
										<br/>
									<hr style="height:1px; border:none; color:#DDD; background-color:#DDD; border-color:#DDD; margin:20px 0;">
								</div>

								<div>
										<p style="font-weight:700;"><?php _e('Regeneration Intervals', 'plugincache'); ?></p>
										<label for="main_time"><?php _e('Time before re-generation of cache<br/>files for main page (In Hours)', 'plugincache'); ?></label>
											<br/>
										<input type="text" name="main_time" id="main_time" value="<?php echo osc_get_preference('main_time', 'plugincache'); ?>"/>
											<br/>
											<br/>
										<label for="search_time"><?php _e('Time before re-generation of cache<br/>files for search results (In Hours) ', 'plugincache'); ?></label>
											<br/>
										<input type="text" name="search_time" id="search_time" value="<?php echo osc_get_preference('search_time', 'plugincache'); ?>"/>
											<br/>
											<br/>
										<label for="item_time"><?php _e('Time before re-generation of cache files<br/>for item\'s page (In Hours)', 'plugincache'); ?></label>
											<br/>
										<input type="text" name="item_time" id="item_time" value="<?php echo osc_get_preference('item_time', 'plugincache'); ?>"/>
											<br/>
											<br/>
										<label for="static_time"><?php _e('Time before re-generation of cache<br/>files for static pages (In Hours)', 'plugincache'); ?></label>
											<br/>
										<input type="text" name="static_time" id="static_time" value="<?php echo osc_get_preference('static_time', 'plugincache'); ?>"/>
										<hr style="height:1px; border:none; color:#DDD; background-color:#DDD; border-color:#DDD; margin:20px 0;">
								</div>

								<p><button style="font-size:16px; font-weight:700;" type="submit" ><?php _e('Update', 'plugincache');?></button></p>
											<br/>
							</form>
						</fieldset>
					</td>

					<td align="center" style="float:left; margin-left:10px; border:3px solid #888888; border-radius:5px; padding:15px;">     
						<table>
								<p style=" font-size:16px; font-weight:700;"><?php _e('Delete Cached Files', 'plugincache'); ?></p>
							<form name="plugincache_form" id="plugincache_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="page" value="plugins" />
								<input type="hidden" name="action" value="renderplugin" />
								<input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>conf.php" />
								<input type="hidden" name="plugin_action" value="clear" />
								<p><?php _e('Select the elements you want to clear from cache', 'plugincache'); ?></p>
								<tr style="vertical-align: top;">
									<td>
										<span style="font-weight:700;"><?php _e("Item Pages"); ?></span>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<input type="checkbox" name="item" value="1" >
										<span><?php _e("Clear cache of item pages"); ?></span><br/><br/>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<span style="font-weight:700;"><?php _e("Main Page"); ?></span>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<input type="checkbox" name="main" value="1" >
										<span><?php _e("Clear cache of main page"); ?></span><br/><br/>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<span style="font-weight:700;"><?php _e("Search Pages"); ?></span>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<input type="checkbox" name="search" value="1">
										<span><?php _e("Clear cache of searh pages"); ?></span><br/><br/>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<span style="font-weight:700;"><?php _e("Static Pages"); ?></span>
									</td>
								</tr>
								<tr style="vertical-align: top;">
									<td>
										<input type="checkbox" name="static" value="1">
										<span><?php _e("Clear cache of static pages"); ?></span>
									</td>
								</tr>
						</table>
								<p><button style="font-size:16px; font-weight:bold;" type="submit" ><?php _e('Clear Cache', 'plugincache');?></button></p>
							</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
<div>
