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
?>

<div class="tpHits">
    <i class="tp tp-eye"></i>
    <?php echo JText::sprintf('COM_TZ_PORTFOLIO_PLUS_ARTICLE_HITS',$this->item->hits); ?>
    <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $this->item->hits; ?>" />
</div>