<?php
/*
Plugin Name: Plugin Cache
Plugin URI: http://www.osclass.org/
Description: Cache system for OSClass, make your web load faster!
Version: 1.0.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: plugincache
Plugin update URI: plugin-cache
*/


	function delete_directory($dir)
	{
		if ($handle = opendir($dir))
		{
		$array = array();

		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {

					if(is_dir($dir.$file))
					{
						if(!@rmdir($dir.$file)) // Empty directory? Remove it
						{
		                delete_directory($dir.$file.'/'); // Not empty? Delete the files inside it
						}
					}
					else
					{
		               @unlink($dir.$file);
					}
		        }
		    }
		    closedir($handle);

			@rmdir($dir);
		}

	}

    function plugincache_install() {
		@mkdir(osc_content_path().'uploads/cache_files/', 0777, true);

        osc_set_preference('upload_path', osc_content_path().'uploads/cache_files/', 'plugincache', 'STRING');
		osc_set_preference('main_time', '1', 'plugincache', 'INTEGER');
        osc_set_preference('search_time', '1', 'plugincache', 'INTEGER');
        osc_set_preference('item_time', '24', 'plugincache', 'INTEGER');
		osc_set_preference('static_time', '24', 'plugincache', 'INTEGER');
		osc_set_preference('main_cache', 'active', 'plugincache', 'INTEGER');
		osc_set_preference('item_cache', 'active', 'plugincache', 'INTEGER');
		osc_set_preference('search_cache', 'active', 'plugincache', 'INTEGER');
		osc_set_preference('static_cache', 'active', 'plugincache', 'INTEGER');
    }

    function plugincache_uninstall() {
        osc_delete_preference('upload_path', 'plugincache');
        osc_delete_preference('search_time', 'plugincache');
        osc_delete_preference('item_time', 'plugincache');
        osc_delete_preference('main_time', 'plugincache');
		osc_delete_preference('static_time', 'plugincache');
		osc_delete_preference('main_cache', 'plugincache');
		osc_delete_preference('item_cache', 'plugincache');
		osc_delete_preference('search_cache', 'plugincache');
		osc_delete_preference('static_cache', 'plugincache');

        $dir = osc_content_path().'uploads/cache_files/'; // IMPORTANT: with '/' at the end
        $remove_directory = delete_directory($dir);
    }

if(!function_exists('cache_start')) {
	function cache_start() {
		if( osc_is_home_page() || osc_is_ad_page() || osc_is_search_page() || osc_is_static_page() || osc_get_osclass_location() == 'contact') {
			if(!osc_is_web_user_logged_in()) {

				if ((osc_is_ad_page())&&(osc_get_preference('item_cache', 'plugincache')== 'active')&&(!osc_item_is_spam())&&(osc_item_is_active())) {
				    $cachetitle = osc_item_id();
				    $cachefile = osc_get_preference('upload_path', 'plugincache')."item/".$cachetitle.".html";
					$cachetime = osc_get_preference('item_time', 'plugincache')*3600;
					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
				    {
				        include($cachefile);
				        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
				        exit;
				    }
				    ob_start(); // start the output buffer
				}

				elseif ((osc_is_static_page() || osc_get_osclass_location() == 'contact')&&(osc_get_preference('static_cache', 'plugincache')== 'active')) {
					$cachetime = osc_get_preference('static_time', 'plugincache')*3600;

					if(osc_get_osclass_location() == 'contact') {
						$cachefile = osc_get_preference('upload_path', 'plugincache')."static/contact.html";
					} else {
						$page = Page::newInstance()->findByPrimaryKey(Params::getParam('id'));
						$cachetitle = $page['pk_i_id'];
						$cachefile = osc_get_preference('upload_path', 'plugincache')."static/".$cachetitle.".html";
					}

					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
				    {
						include($cachefile);
				        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
				        exit;
				    }
					ob_start(); // start the output buffer
				}

				elseif ((osc_is_home_page())&&(osc_get_preference('main_cache', 'plugincache')== 'active')) {
					$cachefile = osc_get_preference('upload_path', 'plugincache')."main/cache.html";
				    $cachetime = osc_get_preference('main_time', 'plugincache')*3600;

					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
				    {
						include($cachefile);
				        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
				        exit;
				    }
				    ob_start(); // start the output buffer
				}

				elseif ((osc_is_search_page())&&(osc_get_preference('search_cache', 'plugincache')== 'active')) {
					function t(&$a, &$b, &$c, &$d) {
						$a = osc_search_region();
						$b = osc_search_city();
						$c = osc_search_pattern();
						$dcat = osc_search_category();
						foreach($dcat as $d) {
							osc_esc_html($d);
						}
					}

					t($a,$b,$c,$d);

					$cachetitle = $a . '' . $b . ''  . $c . '' . $d ;

				    $cachefile = osc_get_preference('upload_path', 'plugincache')."search/".$cachetitle.".html";
					$cachetime = osc_get_preference('search_time', 'plugincache')*3600;

					// Serve from the cache if it is younger than $cachetime
				    if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
				    {
						include($cachefile);
				        echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
				        exit;
				    }
				    ob_start(); // start the output buffer
				}
			}
		}
	}
	osc_add_hook('before_html', 'cache_start');
}

if(!function_exists('cache_end')) {
	function cache_end() {
		if( osc_is_home_page() || osc_is_ad_page() || osc_is_search_page() || osc_is_static_page() || osc_get_osclass_location() == 'contact') {
			if(!osc_is_web_user_logged_in()) {

				if ((osc_is_ad_page())&&(osc_get_preference('item_cache', 'plugincache')== 'active')&&(!osc_item_is_spam())&&(osc_item_is_active())) {
					$cachetitle = osc_item_id();
					$cachefile = osc_get_preference('upload_path', 'plugincache')."item/".$cachetitle.".html";
					$cachetime = osc_get_preference('item_time', 'plugincache')*3600;

					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
					{
						include($cachefile);
						echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
						exit;
					}

					@mkdir(osc_get_preference('upload_path', 'plugincache')."item/", 0777, true);
					// open the cache file for writing
					$fp = fopen($cachefile, 'w');

					// save the contents of output buffer to the file
					fwrite($fp, ob_get_contents());

					// close the file
					fclose($fp);

					// Send the output to the browser
					ob_end_flush();
				}

				elseif ((osc_is_static_page() || osc_get_osclass_location() == 'contact')&&(osc_get_preference('static_cache', 'plugincache')== 'active')) {
					$cachetime = osc_get_preference('static_time', 'plugincache')*3600;

					if(osc_get_osclass_location() == 'contact') {
						$cachefile = osc_get_preference('upload_path', 'plugincache')."static/contact.html";
					} else {
						$page = Page::newInstance()->findByPrimaryKey(Params::getParam('id'));
						$cachetitle = $page['pk_i_id'];
						$cachefile = osc_get_preference('upload_path', 'plugincache')."static/".$cachetitle.".html";
					}

					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
					{
						include($cachefile);
						echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
						exit;
					}

					@mkdir(osc_get_preference('upload_path', 'plugincache')."static/", 0777, true);
					// open the cache file for writing
					$fp = fopen($cachefile, 'w');

					// save the contents of output buffer to the file
					fwrite($fp, ob_get_contents());

					// close the file
					fclose($fp);

					// Send the output to the browser
					ob_end_flush();
				}

				elseif ((osc_is_home_page())&&(osc_get_preference('main_cache', 'plugincache')== 'active'))
				{
					$cachefile = osc_get_preference('upload_path', 'plugincache')."main/cache.html";
					$cachetime = osc_get_preference('main_time', 'plugincache')*3600;

					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
					{
						include($cachefile);
						echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
						exit;
					}

					@mkdir(osc_get_preference('upload_path', 'plugincache')."main/", 0777, true);

					// open the cache file for writing
					$fp = fopen($cachefile, 'w');

					// save the contents of output buffer to the file
					fwrite($fp, ob_get_contents());

					// close the file
					fclose($fp);

					// Send the output to the browser
					ob_end_flush();
				}

				elseif ((osc_is_search_page())&&(osc_get_preference('search_cache', 'plugincache')== 'active'))
				{
					if(!function_exists('t')) {

						function t(&$a, &$b, &$c, &$d) {
							$a = osc_search_region();
							$b = osc_search_city();
							$c = osc_search_pattern();
							$dcat = osc_search_category();
							foreach($dcat as $d) {
								osc_esc_html($d);
							}
						}
					}
					t($a,$b,$c,$d);

					$cachetitle = $a . '' . $b . ''  . $c . '' . $d ;

					$cachefile = osc_get_preference('upload_path', 'plugincache')."search/".$cachetitle.".html";
					$cachetime = osc_get_preference('search_time', 'plugincache')*3600;
					if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
					{
						include($cachefile);
						echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
						exit;
					}

					@mkdir(osc_get_preference('upload_path', 'plugincache')."search/", 0777, true);
					// open the cache file for writing
					$fp = fopen($cachefile, 'w');

					// save the contents of output buffer to the file
					fwrite($fp, ob_get_contents());

					// close the file
					fclose($fp);

					// Send the output to the browser
					ob_end_flush();
				}


				// Serve from the cache if it is younger than $cachetime
			}
		}
	}
	osc_add_hook('after_html', 'cache_end');
}

	function plugincache_item_edit_post($item) {
		$IdItem = osc_item_id($item);
		$file = osc_get_preference('upload_path', 'plugincache')."item/".$IdItem.".html";
		if (file_exists($file))
		{
			@unlink($file);
		}
	}

	function plugincache_add_comment($id) {
		$conn = getConnection();
        $commId = $conn->osc_dbFetchResult("SELECT fk_i_item_id FROM %st_item_comment WHERE pk_i_id = %d", DB_TABLE_PREFIX, $id);
		$IdCommAd = osc_get_preference('upload_path', 'plugincache')."item/".$Id.".html";
		if (file_exists($IdCommAd))
		{
			@unlink($IdCommAd);
		}
    }

	function plugincache_delete_item($id) {
		$ItemId = osc_get_preference('upload_path', 'plugincache')."item/".$id.".html";
		if (file_exists($ItemId))
		{
			@unlink($ItemId);
        }
	}

	function plugincache_clear_item() {
        $files = rglob(osc_get_preference('upload_path', 'plugincache')."item/*");
        foreach($files as $f) {
            @unlink($f);
        }
    }

	function plugincache_clear_static() {
        $files = rglob(osc_get_preference('upload_path', 'plugincache')."static/*");
        foreach($files as $f) {
            @unlink($f);
        }
    }

	function plugincache_clear_search() {
        $files = rglob(osc_get_preference('upload_path', 'plugincache')."search/*");
        foreach($files as $f) {
            @unlink($f);
        }
    }

	function plugincache_clear_main() {
        $files = rglob(osc_get_preference('upload_path', 'plugincache')."main/*");
        foreach($files as $f) {
            @unlink($f);
        }
    }

	function plugincache_admin_menu() {
        echo '<h3><a href="#">Plugin Cache</a></h3>
        <ul>
            <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'conf.php') . '">&raquo; ' . __('Settings', 'plugincache') . '</a></li>
            <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'help.php') . '">&raquo; ' . __('Help', 'plugincache') . '</a></li>
        </ul>';
    }

	/**
     * ADD HOOKS
     */
    osc_register_plugin(osc_plugin_path(__FILE__), 'plugincache_install');
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'plugincache_uninstall');


	// clear cahe after item actions
	if(osc_version()<320) {
		osc_add_hook('item_edit_post', 'plugincache_item_edit_post');
         } else {
        osc_add_hook('edited_item', 'plugincache_item_edit_post');
	    }
		osc_add_hook('activate_item', 'plugincache_delete_item');
        osc_add_hook('deactivate_item', 'plugincache_delete_item');
        osc_add_hook('enable_item', 'plugincache_delete_item');
        osc_add_hook('disable_item', 'plugincache_delete_item');
		osc_add_hook('delete_item', 'plugincache_delete_item');
		osc_add_hook('item_spam_on', 'plugincache_delete_item');
		osc_add_hook('item_spam_off', 'plugincache_delete_item');

		// clear cache after comment
		osc_add_hook('add_comment', 'plugincache_item_edit_post');
		osc_add_hook('activate_comment', 'plugincache_add_comment');
        osc_add_hook('deactivate_comment', 'plugincache_add_comment');
        osc_add_hook('enable_comment', 'plugincache_add_comment');
        osc_add_hook('disable_comment', 'plugincache_add_comment');
        osc_add_hook('delete_comment', 'plugincache_add_comment');

		// FANCY MENU

		osc_add_hook('admin_menu', 'plugincache_admin_menu');



?>