<?php

class JJ_NGG_JQuery_Slider extends WP_Widget
{
  
  function JJ_NGG_JQuery_Slider()
  {
    $widget_ops = array('classname' => 'jj-nexgen-jquery_slider', 'description' => "Allows you to pick a gallery from the 'NextGen Gallery' plugin to use as a 'JQuery Nivo Slider'.");
    $this->WP_Widget('jj-nexgen-jquery_slider', 'JJ NextGEN JQuery Slider', $widget_ops);
  }
  
  function widget($args, $instance)
  {
    global $wpdb;
    extract($args);

    // Set params
    $title = apply_filters('widget_title', $instance['title']);
    $html_id = $this->get_val($instance, 'html_id', 'slider');
    $width = $this->get_val_numeric($instance, 'width');
    $height = $this->get_val_numeric($instance, 'height');
    $order = $this->get_val($instance, 'order', 'asc', false);
    $tags = $this->get_val($instance, 'tags');
    $shuffle = $this->get_val($instance, 'shuffle');
    $limit = $this->get_val_numeric($instance, 'max_pictures');
    $center = $this->get_val($instance, 'center');
    $gallery = $this->get_val_numeric($instance, 'gallery');
    $shortcode = $this->get_val($instance, 'shortcode'); 
    
    // Set nivo params
    $effect = $this->get_val($instance, 'effect');
    $slices = $this->get_val($instance, 'slices');
    $boxcols = $this->get_val($instance, 'boxcols');
    $boxrows = $this->get_val($instance, 'boxrows');
    $animspeed = $this->get_val($instance, 'animspeed');
    $pausetime = $this->get_val($instance, 'pausetime');
    $startslide = $this->get_val($instance, 'startslide');
    $directionnav = $this->get_val($instance, 'directionnav');
    $directionnavhide = $this->get_val($instance, 'directionnavhide');
    $controlnav = $this->get_val($instance, 'controlnav');
    $controlnavthumbs = $this->get_val($instance, 'controlnavthumbs');    
    $thumbswidth = $this->get_val_numeric($instance, 'thumbswidth');
    $thumbsheight = $this->get_val_numeric($instance, 'thumbsheight');
    $thumbscontainerheight = $this->get_val_numeric($instance, 'thumbscontainerheight');
    $thumbsgap = $this->get_val_numeric($instance, 'thumbsgap');    
    $controlnavthumbsfromrel = $this->get_val($instance, 'controlnavthumbsfromrel');
    $controlnavthumbssearch = $this->get_val($instance, 'controlnavthumbssearch');
    $controlnavthumbsreplace = $this->get_val($instance, 'controlnavthumbsreplace');
    $keyboardnav = $this->get_val($instance, 'keyboardnav');
    $pauseonhover = $this->get_val($instance, 'pauseonhover');
    $manualadvance = $this->get_val($instance, 'manualadvance');
    $captionopacity = $this->get_val($instance, 'captionopacity');
    $disablecaptions = $this->get_val($instance, 'disablecaptions');
    $beforechange = $this->get_val($instance, 'beforechange', '', false);
    $afterchange = $this->get_val($instance, 'afterchange', '', false);
    $slideshowend = $this->get_val($instance, 'slideshowend', '', false);
    $lastslide = $this->get_val($instance, 'lastslide', '', false);
    $afterload = $this->get_val($instance, 'afterload', '', false);

    // SQL defaults
    $sql_order = '';
    $sql_where = ' WHERE exclude = 0';
    $sql_limit = '';
    
    // Set SQL order
    if($order == 'random')
    {
      $sql_order = 'RAND()';
    }
    elseif($order == 'asc') 
    {
       $sql_order = 'galleryid ASC';
    }        
    elseif($order == 'desc') 
    {
      $sql_order = 'galleryid DESC';
    }
    elseif($order == 'sortorder')
    {
      $sql_order = 'sortorder ASC';
    }
    else
    {
      $sql_order = 'galleryid ASC';
    }

    if($gallery != '')
    {
      $sql_where .= ' AND galleryid = ' . $gallery;
    }
    
    // Set limit defaults only it tags are not being used
    $num_limit = -1;
    if(is_numeric($limit)) 
    {
      $num_limit = (int)$limit;
      if($tags == '')
      {
        $sql_limit = ' LIMIT 0, ' . $limit;
      }
    }        

    $results = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures" . $sql_where . " ORDER BY " . $sql_order . $sql_limit);
    $p_size = 0;
    if(is_array($results))
    {           
      // Filter by tag if entered
      if($tags != '')
      {
        $tagged_images = nggTags::find_images_for_tags($tags);
      
        if($tagged_images)
        {
          $tagged_image_ids = array();
          foreach($tagged_images as $image)
          {
            $tagged_image_ids[] = $image->pid;
          }
          
          if(sizeof($tagged_image_ids) > 0)
          {      
            $filtered_results = array();
            $tagged_count = 0;
            foreach($results as $result)
            {    
              if($num_limit != -1 && $tagged_count >= $num_limit)
              {   
                break;
              }
              else
              {       
                if(in_array($result->pid, $tagged_image_ids))
                {
                  $filtered_results[] = $result;
                  $tagged_count += 1;
                }
              }
            }        
            $results = $filtered_results;
          }
        }
        else
        {
          $results = array();
        }
      }
      
      $p_size = count($results);              
    }
    
    $output = '';                
    if($p_size > 0) 
    {     
      if($title != '')
      {      
        if($shortcode != '1')
        {      
          $output .= "\n" . $before_title . $title . $after_title;
        }
        else
        {
          $output .= "\n<h3>" . $title . "</h3>";
        }
      }
      
      $has_control_nav = ($controlnav == '' || $controlnav == 'true');
      $has_thumbs = ($controlnavthumbs == 'true' || $controlnavthumbs == 'nextgen_thumbs' || $controlnavthumbs == 'nextgen_original');
      $has_center = $center == '1' && $width != '';
      
      if($width != '' || $height != '')
      {
        $width_s = '';
        $height_s = '';
        if($width != '') { $width_s = "width: " . $width . "px !important;"; }
        if($height != '') { $height_s = "height: " . $height . "px !important;"; }
        $output .= "\n<style type=\"text/css\">";     
        if($width_s != '' || $height_s != '')
        {
          $output .= "\n  div#" . $html_id . " { " . $width_s . $height_s . " }"; 
        }
        if($width_s != '')
        {
          $output .= "\n  div#" . $html_id . "_container .nivo_slider .nivo-controlNav { " . $width_s . " }";   
        }  
        if($has_control_nav && $has_thumbs)
        {
          if($thumbscontainerheight != '')
          {
            $output .= "\n  div#" . $html_id . "_container { padding-bottom: " . $thumbscontainerheight . "px; }";
            $output .= "\n  div#" . $html_id . "_container .nivo-controlNav { bottom: -" . $thumbscontainerheight . "px; }";
          }
          if($thumbsgap != '' || $thumbswidth != '' || $thumbsheight != '')
          {
            $output .= "\n  div#" . $html_id . "_container .nivo-controlNav img { ";
            if($thumbsgap != '')
            {
              $output .= "margin-right: " . $thumbsgap . "px;";
            }
            if($thumbswidth != '')
            {
              $output .= "width: " . $thumbswidth . "px ;";
            }  
            if($thumbsheight != '')
            {
              $output .= "height: " . $thumbsheight . "px;";
            }                    
            $output .= " }";
          } 
          if($has_center && $thumbsgap != '')
          {
            $output .= "\n  div#" . $html_id . "_container .nivo-controlNav img.first_thumb { margin-left: " . $thumbsgap . "px; }";
          } 
        }        
        $output .= "\n</style>";         
      }
      
      $container_class = '';      
      if($has_center)
      {
        $container_class = ' nivo_slider_center';
      }
      if($has_control_nav)
      {
        if($has_thumbs)
        {
          $container_class .= ' nivo_slider_controlNavImages';  
        }
        else
        {
          $container_class .= ' nivo_slider_controlNavText';
        }
      }
      
      $output .= "\n<div id=\"" . $html_id . "_container\" class=\"nivo_slider_container" . $container_class . "\">";      
      $output .= "\n  <div id=\"" . $html_id . "\" class=\"nivo_slider\">";
      $image_alt = null;
      $image_description = null;
      foreach($results as $result) 
      {
        $gallery = $wpdb->get_row("SELECT * FROM $wpdb->nggallery WHERE gid = '" . $result->galleryid . "'");
        foreach($gallery as $key => $value) 
        {
            $result->$key = $value;
        }        
        $image = new nggImage($result);
                
        $image_alt = trim($image->alttext);   
        $image_description = trim($image->description);                   
        
        $output .= "\n    ";
        
        // check that alt is url with a simple validation
        $use_url = false;        
        if($image_alt != '' && (substr($image_alt, 0, 1) == '/' || substr($image_alt, 0, 4) == 'http' || substr($image_alt, 0, 3) == 'ftp'))
        {
          $use_url = true;
        }
                
        if($use_url != '')
        {
          $output .= "<a href=\"" . esc_attr($image_alt) . "\">";
        }
        
        if($disablecaptions != '1' && $image_description != '')
        {
          $image_description = "title=\"" . $this->quote_fix($image_description) . "\" ";
        }
        else
        {
          $image_description = '';
        }
          
        $output .= "<img src=\"" . $image->imageURL . "\" " . $image_description . "class=\"nivo_image\" alt=\"nivo slider image\" />";
        
        if($use_url != '')
        {
          $output .= "</a>";
        }          
      }
      $output .= "\n  </div>";
      $output .= "\n</div>";
    }    
    
    // Nivo arguments
    $javascript_args = array();
    
    // Modifications if only 1 picture
    if($p_size <= 1)
    {
      $startslide = '0';
      $directionnav = 'false';
      $controlnav = 'false';
      $keyboardnav = 'false';
      $pauseonhover = 'false';
      $manualadvance = 'false';    
      $beforechange = '';
      $afterchange = '';
      $slideshowend = '';
      $lastslide = '';
      $afterload  = '';
    }

    if($effect != "") { $javascript_args[] = "effect: '" . $effect . "'"; }
    if($slices != "") { $javascript_args[] = "slices: " . $slices . ""; }
    if($boxcols != "") { $javascript_args[] = "boxCols: " . $boxcols . ""; }
    if($boxrows != "") { $javascript_args[] = "boxRows: " . $boxrows . ""; }
    if($animspeed != "") { $javascript_args[] = "animSpeed: " . $animspeed; }
    if($pausetime != "") { $javascript_args[] = "pauseTime: " . $pausetime; }
    if($startslide != "") { $javascript_args[] = "startSlide: " . $startslide; }
    if($directionnav != "") { $javascript_args[] = "directionNav: " . $directionnav; }
    if($directionnavhide != "") { $javascript_args[] = "directionNavHide: " . $directionnavhide; }
    if($controlnav != "") { $javascript_args[] = "controlNav: " . $controlnav; }
    if($controlnavthumbs != "") { $javascript_args[] = "controlNavThumbs: " . ($has_thumbs ? 'true' : 'false'); }
    if($controlnavthumbsfromrel != "") { $javascript_args[] = "controlNavThumbsFromRel: " . $controlnavthumbsfromrel; }
    if($controlnavthumbssearch != "") { $javascript_args[] = "controlNavThumbsSearch: '" . $controlnavthumbssearch . "'"; }
    if($controlnavthumbsreplace != "") { $javascript_args[] = "controlNavThumbsReplace: '" . $controlnavthumbsreplace . "'"; }
    if($keyboardnav != "") { $javascript_args[] = "keyboardNav: " . $keyboardnav; }
    if($pauseonhover != "") { $javascript_args[] = "pauseOnHover: " . $pauseonhover; }
    if($manualadvance != "") { $javascript_args[] = "manualAdvance: " . $manualadvance; }
    if($captionopacity != "") { $javascript_args[] = "captionOpacity: " . $captionopacity; }
    if($beforechange != "") { $javascript_args[] = "beforeChange: " . $beforechange; }
    if($afterchange != "") { $javascript_args[] = "afterChange: " . $afterchange; }
    if($slideshowend != "") { $javascript_args[] = "slideshowEnd: " . $slideshowend; }
    if($lastslide != "") { $javascript_args[] = "lastSlide: " . $lastslide; }
    if($afterload != "") { $javascript_args[] = "afterLoad: " . $afterload; }  
    
    // Add javascript
    $output .= "\n<script type=\"text/javascript\">";
    $output .= "\n  jQuery(window).load(function() {";                
    // Shuffle results on random order so even if page is cached the order will be different each time
    if($order == 'random' && $shuffle == 'true')
    {
      $output .= "\n    jQuery('div#" . $html_id . "').jj_ngg_shuffle();";
    }
    $output .= "\n    jQuery('div#" . $html_id . "').nivoSlider(";
    if(count($javascript_args) > 0)
    {
      $output .= "{" . implode(",", $javascript_args) . "}";
    }
    $output .= ");";
    if($has_control_nav && $has_thumbs)
    {
      if($controlnavthumbs == 'nextgen_thumbs' || $controlnavthumbs == 'nextgen_original')
      {
        $output .= "\n    JJNGGUtils.wordpressThumbs('" . $html_id . "', " . ($controlnavthumbs == 'nextgen_thumbs' ? 'true' : 'false') . ");";
      }
      if($has_center && $thumbsgap != '')
      {
        $output .= "\n    JJNGGUtils.wordpressThumbsCenterFix('" . $html_id . "');";
      }
      $output .= "\n    jQuery('div#" . $html_id . " div.nivo-controlNav').css('visibility', 'visible');";
    }
    $output .= "\n  });";  
    $output .= "\n</script>\n";

    if($shortcode != '1')
    {
      echo $before_widget . "\n<ul class=\"ul_jj_slider\">\n    <li class=\"li_jj_slider\">" . $output . "\n    </li>\n  </ul>\n" . $after_widget;     
    }
    else
    {
      echo $output;
    }
  }

  function get_val($instance, $key, $default = '', $escape = true)
  {
    $val = '';
    if(isset($instance[$key]))
    {
      $val = trim($instance[$key]);
    }
    if($val == '')
    {
      $val = $default;
    }
    if($escape)
    {
      $val = esc_attr($val);
    }
    return $val;
  }
  
  function get_val_numeric($instance, $key, $default = '')
  {
    $val = $this->get_val($instance, $key, $default, false);
    if($val != '' && !is_numeric($val))
    {
      $val = '';
    }
    return $val;
  }
  
  function quote_fix($phrase) {
    return str_replace(array('"', '\"', "\'"), array("'", "'", "'"), $phrase);               
  }

  function form($instance)
  {
    global $wpdb;
    $instance = wp_parse_args((array) $instance, array(
      'title' => '',
      'gallery' => '',
      'html_id' => 'slider',
      'width' => '',
      'height' => '',
      'order' => 'random',
      'tags' => '',
      'shuffle' => 'false',
      'max_pictures' => '',
      'center' => '',
      
      // nivo settings
      'effect' => '',
      'slices' => '',
      'boxcols' => '',
      'boxrows' => '',
      'animspeed' => '',
      'pausetime' => '',
      'startslide' => '',
      'directionnav' => '',
      'directionnavhide' => '',
      'controlnav' => '',
      'controlnavthumbs' => '',
      'thumbswidth' => '',
      'thumbsheight' => '',
      'thumbscontainerheight' => '',
      'thumbsgap' => '',
      'controlnavthumbsfromrel' => '',
      'controlnavthumbssearch' => '',
      'controlnavthumbsreplace' => '',
      'keyboardnav' => '',
      'pauseonhover' => '',
      'manualadvance' => '',
      'captionopacity' => '',
      'beforechange' => '',
      'afterchange' => '',
      'slideshowend' => '',
      'lastslide' => '',
      'afterload' => ''
    ));
    $order_values = array('random' => 'Random', 'asc' => 'Latest First', 'desc' => 'Oldest First', 'sortorder' => 'NextGen Sortorder');
    $galleries = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY name ASC");
?>
  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><strong>Widget title:</strong></label><br />
    <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"  class="widefat" />
  </p>
  <p>
    <label><strong>Select a gallery to use:</strong></label><br />
    <?php if(is_array($galleries) && count($galleries) > 0) { ?>
      <select id="<?php echo $this->get_field_id('gallery'); ?>" name="<?php echo $this->get_field_name('gallery'); ?>" class="widefat">
        <option value="">All images</option>
        <?php 
          $gallery_selected = '';        
          foreach($galleries as $gallery)
          {       
            if($gallery->gid == $instance['gallery'])
            {
              $gallery_selected = " selected=\"selected\"";
            }
            else
            {
              $gallery_selected = "";
            }
            echo "<option value=\"" . $gallery->gid . "\"" . $gallery_selected . ">" . $gallery->name . "</option>";
        } ?>
      </select>
    <?php }else{ ?>
      No galleries found
    <?php } ?>
  </p>  
  <p>
    <label for="<?php echo $this->get_field_id('order'); ?>"><strong>Order:</strong></label><br />
    <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" class="widefat">
      <?php 
        $order_selected = '';        
        foreach($order_values as $key => $value) 
        {       
          if($key == $instance['order'])
          {
            $order_selected = " selected=\"selected\"";
          }
          else
          {
            $order_selected = "";
          }
          echo "<option value=\"" . $key . "\"" . $order_selected . ">" . $value . "</option>";
      } ?>
    </select>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('tags'); ?>"><strong>Only display images tagged:</strong></label><br /><small>comma separated list</small><br />
    <input type="text" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" value="<?php echo $instance['tags']; ?>" class="widefat" />
  </p>   
  <p>
    <label><strong>Shuffle:</strong> <small>(Only for random order)</small></label><br />
    <input type="radio" id="<?php echo $this->get_field_id('shuffle'); ?>_true" name="<?php echo $this->get_field_name('shuffle'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['shuffle'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('shuffle'); ?>_true" style="vertical-align: middle;">true</label>
    <input type="radio" id="<?php echo $this->get_field_id('shuffle'); ?>_false" name="<?php echo $this->get_field_name('shuffle'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['shuffle'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('shuffle'); ?>_false" style="vertical-align: middle;">false</label>          
  </p>  
  <p>
    <label for="<?php echo $this->get_field_id('max_pictures'); ?>"><strong>Max pictures:</strong> <small>(Leave blank for all)</small></label><br />
    <input type="text" id="<?php echo $this->get_field_id('max_pictures'); ?>" name="<?php echo $this->get_field_name('max_pictures'); ?>" value="<?php echo $instance['max_pictures']; ?>" size="3" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('html_id'); ?>"><strong>HTML id:</strong></label><br />
    <input type="text" id="<?php echo $this->get_field_id('html_id'); ?>" name="<?php echo $this->get_field_name('html_id'); ?>" value="<?php echo $instance['html_id']; ?>" class="widefat" />
  </p> 
  <p>
    <label for="<?php echo $this->get_field_id('width'); ?>"><strong>Width:</strong>  <small>(Leave blank for auto)</small></label><br />
    <input type="text" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" size="3" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('height'); ?>"><strong>Height:</strong> <small>(Leave blank for auto)</small></label><br />
    <input type="text" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" size="3" />
  </p>     
  <p>
    <input type="checkbox" id="<?php echo $this->get_field_id('center'); ?>" style="vertical-align: middle;" name="<?php echo $this->get_field_name('center'); ?>" value="1"<?php if($instance['center'] == '1') { echo " checked=\"checked\""; } ?> />
    <label for="<?php echo $this->get_field_id('center'); ?>" style="vertical-align: middle;"><strong>Center content</strong></label><br />
  </p> 
  <div class="javascript_settings" style="display: none; border: 1px solid #cccccc; background-color: #f0f0f0;">
    <div style="padding: 10px;">
      <p><a href="http://nivo.dev7studios.com/#usage" target="jj_nextgen_jquery">Visit Nivo configuration page</a></p>
      <p>Leave blank to use defaults.</p>
      <p>
        <label for="<?php echo $this->get_field_id('effect'); ?>"><strong>effect:</strong> <small>(Leave blank for all)</small></label><br />
        <input type="text" id="<?php echo $this->get_field_id('effect'); ?>" name="<?php echo $this->get_field_name('effect'); ?>" value="<?php echo $instance['effect']; ?>" class="widefat" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('slices'); ?>"><strong>slices:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('slices'); ?>" name="<?php echo $this->get_field_name('slices'); ?>" value="<?php echo $instance['slices']; ?>" size="3" />
      </p>      
      <p>
        <label for="<?php echo $this->get_field_id('boxcols'); ?>"><strong>boxCols:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('boxcols'); ?>" name="<?php echo $this->get_field_name('boxcols'); ?>" value="<?php echo $instance['boxcols']; ?>" size="3" />
      </p> 
      <p>
        <label for="<?php echo $this->get_field_id('boxrows'); ?>"><strong>boxRows:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('boxrows'); ?>" name="<?php echo $this->get_field_name('boxrows'); ?>" value="<?php echo $instance['boxrows']; ?>" size="3" />
      </p>                               
      <p>      
        <label for="<?php echo $this->get_field_id('animspeed'); ?>"><strong>animSpeed:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('animspeed'); ?>" name="<?php echo $this->get_field_name('animspeed'); ?>" value="<?php echo $instance['animspeed']; ?>" size="3" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('pausetime'); ?>"><strong>pauseTime:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('pausetime'); ?>" name="<?php echo $this->get_field_name('pausetime'); ?>" value="<?php echo $instance['pausetime']; ?>" size="3" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('startslide'); ?>"><strong>startSlide:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('startslide'); ?>" name="<?php echo $this->get_field_name('startslide'); ?>" value="<?php echo $instance['startslide']; ?>" size="3" />
      </p>                   
      <p>
        <label><strong>directionNav:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('directionnav'); ?>_default" name="<?php echo $this->get_field_name('directionnav'); ?>" value="" style="vertical-align: middle;"<?php if($instance['directionnav'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnav'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('directionnav'); ?>_true" name="<?php echo $this->get_field_name('directionnav'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['directionnav'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnav'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('directionnav'); ?>_false" name="<?php echo $this->get_field_name('directionnav'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['directionnav'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnav'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>      
      <p>
        <label><strong>directionNavHide:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('directionnavhide'); ?>_default" name="<?php echo $this->get_field_name('directionnavhide'); ?>" value="" style="vertical-align: middle;"<?php if($instance['directionnavhide'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnavhide'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('directionnavhide'); ?>_true" name="<?php echo $this->get_field_name('directionnavhide'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['directionnavhide'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnavhide'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('directionnavhide'); ?>_false" name="<?php echo $this->get_field_name('directionnavhide'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['directionnavhide'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('directionnavhide'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>
        <label><strong>controlNav:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('controlnav'); ?>_default" name="<?php echo $this->get_field_name('controlnav'); ?>" value="" style="vertical-align: middle;"<?php if($instance['controlnav'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnav'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnav'); ?>_true" name="<?php echo $this->get_field_name('controlnav'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['controlnav'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnav'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnav'); ?>_false" name="<?php echo $this->get_field_name('controlnav'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['controlnav'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnav'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>
        <label><strong>controlNavThumbs:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbs'); ?>_default" name="<?php echo $this->get_field_name('controlnavthumbs'); ?>" value="" style="vertical-align: middle;"<?php if($instance['controlnavthumbs'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbs'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbs'); ?>_true" name="<?php echo $this->get_field_name('controlnavthumbs'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['controlnavthumbs'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbs'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbs'); ?>_false" name="<?php echo $this->get_field_name('controlnavthumbs'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['controlnavthumbs'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbs'); ?>_false" style="vertical-align: middle;">false</label>          
        <br /><input type="radio" id="<?php echo $this->get_field_id('controlnavthumbs'); ?>_nextgen_thumbs" name="<?php echo $this->get_field_name('controlnavthumbs'); ?>" value="nextgen_thumbs" style="vertical-align: middle;"<?php if($instance['controlnavthumbs'] == 'nextgen_thumbs') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbs'); ?>_nextgen_thumbs" style="vertical-align: middle;">nextgen thumbs</label>                    
        <br /><small>(auto link to NextGen thumbs)</small>
        <br /><input type="radio" id="<?php echo $this->get_field_id('controlnavthumbs'); ?>_nextgen_original" name="<?php echo $this->get_field_name('controlnavthumbs'); ?>" value="nextgen_original" style="vertical-align: middle;"<?php if($instance['controlnavthumbs'] == 'nextgen_original') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbs'); ?>_nextgen_original" style="vertical-align: middle;">nextgen original</label>                    
        <br /><small>(auto link to original image)</small>        
      </p>      
      <p>
        <label for="<?php echo $this->get_field_id('thumbswidth'); ?>"><strong>thumbsWidth:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('thumbswidth'); ?>" name="<?php echo $this->get_field_name('thumbswidth'); ?>" value="<?php echo $instance['thumbswidth']; ?>" size="3" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('thumbsheight'); ?>"><strong>thumbsHeight:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('thumbsheight'); ?>" name="<?php echo $this->get_field_name('thumbsheight'); ?>" value="<?php echo $instance['thumbsheight']; ?>" size="3" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('thumbscontainerheight'); ?>"><strong>thumbsContainerHeight:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('thumbscontainerheight'); ?>" name="<?php echo $this->get_field_name('thumbscontainerheight'); ?>" value="<?php echo $instance['thumbscontainerheight']; ?>" size="3" />
        <br /><small>(eg, image rows x thumb height)</small>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('thumbsgap'); ?>"><strong>thumbsGap:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('thumbsgap'); ?>" name="<?php echo $this->get_field_name('thumbsgap'); ?>" value="<?php echo $instance['thumbsgap']; ?>" size="3" />
      </p>                           
      <p>
        <label><strong>controlNavThumbsFromRel:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_default" name="<?php echo $this->get_field_name('controlnavthumbsfromrel'); ?>" value="" style="vertical-align: middle;"<?php if($instance['controlnavthumbsfromrel'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_true" name="<?php echo $this->get_field_name('controlnavthumbsfromrel'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['controlnavthumbsfromrel'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_false" name="<?php echo $this->get_field_name('controlnavthumbsfromrel'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['controlnavthumbsfromrel'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('controlnavthumbsfromrel'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>        
        <label for="<?php echo $this->get_field_id('controlnavthumbssearch'); ?>"><strong>controlNavThumbsSearch:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('controlnavthumbssearch'); ?>" name="<?php echo $this->get_field_name('controlnavthumbssearch'); ?>" value="<?php echo $instance['controlnavthumbssearch']; ?>" class="widefat" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('controlnavthumbsreplace'); ?>"><strong>controlNavThumbsReplace:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('controlnavthumbsreplace'); ?>" name="<?php echo $this->get_field_name('controlnavthumbsreplace'); ?>" value="<?php echo $instance['controlnavthumbsreplace']; ?>" class="widefat" />
      </p>
      <p>
        <label><strong>keyboardNav:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('keyboardnav'); ?>_default" name="<?php echo $this->get_field_name('keyboardnav'); ?>" value="" style="vertical-align: middle;"<?php if($instance['keyboardnav'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('keyboardnav'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('keyboardnav'); ?>_true" name="<?php echo $this->get_field_name('keyboardnav'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['keyboardnav'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('keyboardnav'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('keyboardnav'); ?>_false" name="<?php echo $this->get_field_name('keyboardnav'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['keyboardnav'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('keyboardnav'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>
        <label><strong>pauseOnHover:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('pauseonhover'); ?>_default" name="<?php echo $this->get_field_name('pauseonhover'); ?>" value="" style="vertical-align: middle;"<?php if($instance['pauseonhover'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('pauseonhover'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('pauseonhover'); ?>_true" name="<?php echo $this->get_field_name('pauseonhover'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['pauseonhover'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('pauseonhover'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('pauseonhover'); ?>_false" name="<?php echo $this->get_field_name('pauseonhover'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['pauseonhover'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('pauseonhover'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>
        <label><strong>manualAdvance:</strong></label><br />
        <input type="radio" id="<?php echo $this->get_field_id('manualadvance'); ?>_default" name="<?php echo $this->get_field_name('manualadvance'); ?>" value="" style="vertical-align: middle;"<?php if($instance['manualadvance'] == '') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('manualadvance'); ?>_default" style="vertical-align: middle;">default</label>
        <input type="radio" id="<?php echo $this->get_field_id('manualadvance'); ?>_true" name="<?php echo $this->get_field_name('manualadvance'); ?>" value="true" style="vertical-align: middle;"<?php if($instance['manualadvance'] == 'true') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('manualadvance'); ?>_true" style="vertical-align: middle;">true</label>
        <input type="radio" id="<?php echo $this->get_field_id('manualadvance'); ?>_false" name="<?php echo $this->get_field_name('manualadvance'); ?>" value="false" style="vertical-align: middle;"<?php if($instance['manualadvance'] == 'false') { echo " checked=\"checked\""; } ?> /><label for="<?php echo $this->get_field_id('manualadvance'); ?>_false" style="vertical-align: middle;">false</label>          
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('disablecaptions'); ?>" style="vertical-align: middle;" name="<?php echo $this->get_field_name('disablecaptions'); ?>" value="1"<?php if($instance['disablecaptions'] == '1') { echo " checked=\"checked\""; } ?> />
        <label for="<?php echo $this->get_field_id('disablecaptions'); ?>" style="vertical-align: middle;"><strong>Disable captions</strong></label><br />
      </p>       
      <p>
        <label for="<?php echo $this->get_field_id('captionopacity'); ?>"><strong>captionOpacity:</strong></label>
        <input type="text" id="<?php echo $this->get_field_id('captionopacity'); ?>" name="<?php echo $this->get_field_name('captionopacity'); ?>" value="<?php echo $instance['captionopacity']; ?>" size="3" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('beforechange'); ?>"><strong>beforeChange:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('beforechange'); ?>" name="<?php echo $this->get_field_name('beforechange'); ?>" value="<?php echo $instance['beforechange']; ?>" class="widefat" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('afterchange'); ?>"><strong>afterChange:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('afterchange'); ?>" name="<?php echo $this->get_field_name('afterchange'); ?>" value="<?php echo $instance['afterchange']; ?>" class="widefat" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('slideshowend'); ?>"><strong>slideshowEnd:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('slideshowend'); ?>"" name="<?php echo $this->get_field_name('slideshowend'); ?>" value="<?php echo $instance['slideshowend']; ?>" class="widefat"" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('lastslide'); ?>"><strong>lastSlide:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('lastslide'); ?>" name="<?php echo $this->get_field_name('lastslide'); ?>" value="<?php echo $instance['lastslide']; ?>" class="widefat" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('afterload'); ?>"><strong>afterLoad:</strong></label><br />
        <input type="text" id="<?php echo $this->get_field_id('afterload'); ?>" name="<?php echo $this->get_field_name('afterload'); ?>" value="<?php echo $instance['afterload']; ?>" class="widefat" />
      </p>                                                                              
    </div>
  </div>  
  <p><a href="#" onclick="jQuery(this).parent().prev('div.javascript_settings').toggle();return false;">Nivo Slider Settings</a></p>     
<?php
  }

  function update($new_instance, $old_instance)
  {
    $new_instance['title'] = esc_attr($new_instance['title']);
    return $new_instance;
  }
}