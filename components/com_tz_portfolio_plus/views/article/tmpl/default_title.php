<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

$params = $this -> item -> params;
if($params -> get('show_title',1)) {
    ?>
    <h2 class="tpp-item-title" itemprop="name">
        <?php echo $this->escape($this->item->title); ?>
    </h2>
    <?php
}
//Call event onContentAfterTitle on plugin
    echo $this->item->event->afterDisplayTitle;
?>