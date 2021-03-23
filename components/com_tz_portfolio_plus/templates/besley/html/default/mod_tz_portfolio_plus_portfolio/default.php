<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    TuanNATemPlaza

# copyright Copyright (C) 2015-2018 tzportfolio.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

$tzTemplate = TZ_Portfolio_PlusTemplate::getTemplateById($params -> get('template_id'));

$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/components/com_tz_portfolio_plus/js/tz_portfolio_plus.min.js');
$doc->addScript(JUri::root() . '/components/com_tz_portfolio_plus/js/jquery.isotope.min.js');
$doc->addStyleSheet(JUri::base(true) . '/components/com_tz_portfolio_plus/css/isotope.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_tz_portfolio_plus/css/tzportfolioplus.min.css');
$doc -> addStyleSheet(JUri::root() . '/components/com_tz_portfolio_plus/templates/'.$tzTemplate -> template.'/css/photoswipe/photoswipe.css');
$doc -> addStyleSheet(JUri::root() . '/components/com_tz_portfolio_plus/templates/'.$tzTemplate -> template.'/css/photoswipe/default-skin/default-skin.css');
$doc -> addScript(JUri::root() . '/components/com_tz_portfolio_plus/templates/'.$tzTemplate -> template.'/js/photoswipe.min.js');
$doc -> addScript(JUri::root() . '/components/com_tz_portfolio_plus/templates/'.$tzTemplate -> template.'/js/photoswipe-ui-default.min.js');
$doc -> addScript(JUri::root() . '/components/com_tz_portfolio_plus/templates/'.$tzTemplate -> template.'/js/lightbox.min.js');

$tplParams = $tzTemplate->params;

$ratio      =   $tplParams->get('ratio','5:3');
list($rwidth,$rheight)  =   explode(':', $ratio);

if($params -> get('load_style', 0)) {
    $doc->addStyleSheet(JUri::base(true) . '/modules/'.$module -> module.'/css/basic.css');
}
if ($params->get('height_element')) {
    $doc->addStyleDeclaration('
        #portfolio' . $module->id . ' .TzInner{
            height:' . $params->get('height_element') . 'px;
        }
    ');
}
if($params -> get('enable_resize_image', 0)){
    $doc -> addScript(JUri::base(true) . '/modules/'.$module -> module.'/js/resize.js');
    if ($params->get('height_element')) {
        $doc->addStyleDeclaration('
        #portfolio' . $module->id . ' .tzpp_media img{
            max-width: none;
        }
        #portfolio' . $module->id . ' .tzpp_media{
            height:' . $params->get('height_element') . 'px;
        }
    ');
    }
}
$doc->addScriptDeclaration('
jQuery(function($){
    $(document).ready(function(){
        $("#portfolio' . $module->id . '").tzPortfolioPlusIsotope({
            "mainElementSelector"       : "#TzContent' . $module->id . '",
            "containerElementSelector"  : "#portfolio' . $module->id . '",
            "sortParentTag"             : "filter'.$module->id.'",
            isotope_options             : {
                "filterSelector"            : "#tz_options'.$module -> id.' .option-set"
            },
            "params"                    : {
                "orderby_sec"           : "'.$params -> get('orderby_sec', 'rdate').'",
                "tz_column_width"       : ' . $params->get('width_element') . ',
                "tz_show_filter"        : ' . $params->get('show_filter', 1) . ',
                "tz_filter_type"        : "'.$params -> get('tz_filter_type', 'categories').'"
            },
            "afterColumnWidth" : function(newColCount,newColWidth){
                jQuery(\'#portfolio' . $module->id . ' .element\').map(function () {
                    var colHeight = (newColWidth * '.$rheight.')/'.$rwidth.';
                    jQuery(this).find(\'.TzArticleMedia\').height(colHeight);
                    if (jQuery(this).hasClass(\'tz_feature_item\')) {
                        jQuery(this).width(newColWidth * 2).find(\'.TzArticleMedia\').height(colHeight * 2 + 40);
                    } 
                });
                '.($params -> get('enable_resize_image', 0)?'TzPortfolioPlusArticlesResizeImage($("#portfolio' . $module->id . ' > .element .tzpp_media"));':'').'
            },
            afterImagesLoaded       : function(){
                jQuery(\'.besleylightbox\').remove();
                besley_lightbox();
            } 
        });
    });
    $(window).load(function(){
        var $tzppisotope    = $("#portfolio' . $module->id . '").data("tzPortfolioPlusIsotope");
        if(typeof $tzppisotope === "object"){
            $tzppisotope.imagesLoaded(function(){
                $tzppisotope.tz_init();
            });
        }
    });
});
');

if ($list):
    ?>
    <div id="TzContent<?php echo $module->id; ?>" class="tz_portfolio_plus_portfolio<?php echo $moduleclass_sfx;?> tplBesley TzContent">
        <?php if($show_filter && isset($filter_tag) && isset($categories)):?>
            <div id="tz_options<?php echo $module -> id;?>" class="clearfix">
                <div class="option-combo">
                    <div id="filter<?php echo $module->id;?>" class="option-set clearfix" data-option-key="filter">
                        <a href="#show-all" data-option-value="*" class="btn btn-default btn-small selected"><?php echo JText::_('MOD_TZ_PORTFOLIO_PLUS_PORTFOLIO_SHOW_ALL');?></a>
                        <?php if($params->get('tz_filter_type','categories') == 'tags' && $filter_tag):?>
                            <?php foreach($filter_tag as $i => $itag):?>
                                <a href="#<?php echo $itag -> alias; ?>"
                                   class="btn btn-default btn-small"
                                   data-option-value=".<?php echo $itag -> alias; ?>">
                                    <?php echo $itag -> title;?>
                                </a>
                            <?php endforeach;?>
                        <?php endif;?>
                        <?php if($params->get('tz_filter_type','categories') == 'categories' && $filter_cat): ?>
                            <?php foreach($filter_cat as $i => $icat):?>
                                <a href="#<?php echo $icat -> alias; ?>"
                                   class="btn btn-default btn-small"
                                   data-option-value=".<?php echo $icat -> alias; ?>">
                                    <?php  echo $icat -> title;?>
                                </a>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php endif?>
        <div id="portfolio<?php echo $module->id; ?>" class="masonry row ">
            <?php foreach ($list as $i => $item) : ?>
                <?php
                $item_filter    = array();
                if ($params->get('tz_filter_type','') == 'tags' && isset($tags[$item->content_id]) && !empty($tags[$item->content_id])) {
                    $item_filter = ArrayHelper::getColumn($tags[$item->content_id], 'alias');
                }

                if ($params->get('tz_filter_type','') == 'categories' && isset($categories[$item->content_id]) && !empty($categories[$item->content_id])) {
                    if(isset($categories[$item->content_id])){
                        $item_filter    = ArrayHelper::getColumn($categories[$item->content_id], 'alias');
                    }
                }
                ?>
                <div class="element <?php echo implode(' ', $item_filter)?>"
                     data-date="<?php echo strtotime($item -> created); ?>"
                     data-title="<?php echo $item -> title; ?>"
                     data-hits="<?php echo (int) $item -> hits; ?>">
                    <div class="TzInner">
                        <?php
                        if(isset($item->event->onContentDisplayMediaType)){
                            ?>
                            <div class="TzArticleMedia">
                                <?php echo $item->event->onContentDisplayMediaType;?>
                            </div>
                            <?php
                        }

                        if(!isset($item -> mediatypes) || (isset($item -> mediatypes) && !in_array($item -> type,$item -> mediatypes))){
                            if($params->get('show_title',1) or $params -> get('show_readmore',1) or $params -> get('show_author', 1) or $params->get('show_created_date', 1)
                                or $params->get('show_hit', 1) or $params->get('show_tag', 1)
                                or $params->get('show_category', 1)
                                or !empty($item -> event -> beforeDisplayAdditionInfo)
                                or !empty($item -> event -> afterDisplayAdditionInfo)) {
                            ?>
                            <div class="TzPortfolioDescription">
                                <?php
                                if ($params -> get('show_title', 1)) {
                                    echo '<h3 class="TzPortfolioTitle"><a href="' . $item->link . '">' . $item->title . '</a></h3>';
                                }

                                //Call event onContentBeforeDisplay on plugin
                                if(isset($item -> event -> beforeDisplayContent)) {
                                    echo $item->event->beforeDisplayContent;
                                }

                                if($params -> get('show_author', 1) or $params->get('show_created_date', 1)
                                    or $params->get('show_hit', 1) or $params->get('show_tag', 1)
                                    or $params->get('show_category', 1)
                                    or !empty($item -> event -> beforeDisplayAdditionInfo)
                                    or !empty($item -> event -> afterDisplayAdditionInfo)) {
                                    ?>
                                    <div class="muted tpMeta">
                                        <?php
                                        if (isset($item->event->beforeDisplayAdditionInfo)) {
                                            echo $item->event->beforeDisplayAdditionInfo;
                                        }

                                        if ($params->get('show_author', 1)) {
                                            ?>
                                            <div class="TzPortfolioCreatedby" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                                <i class="tp tp-pencil"></i>
                                                <?php $author =  $item->user_name; ?>
                                                <?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>
                                                <?php $author = '<span itemprop="name">' . $author . '</span>'; ?>

                                                <?php if ($params->get('cat_link_author', 1)):?>
                                                    <?php 	echo JHtml::_('link', $item -> author_link, $author, array('itemprop' => 'url')); ?>
                                                <?php else :?>
                                                    <?php echo JText::sprintf('COM_TZ_PORTFOLIO_PLUS_WRITTEN_BY', $author); ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                        }
                                        if ($params->get('show_created_date', 1)) {
                                            ?>
                                            <div class="TzPortfolioDate" itemprop="dateCreated">
                                                <i class="tp tp-clock-o"></i>
                                                <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC')); ?>
                                            </div>
                                            <?php
                                        }
                                        if ($params->get('show_hit', 1)) {
                                            ?>
                                            <div class="TzPortfolioHits">
                                                <i class="tp tp-eye"></i>
                                                <?php echo $item->hits; ?>
                                                <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
                                            </div>
                                            <?php
                                        }
                                        if ($params->get('show_tag', 1)) {
                                            if (isset($tags[$item->content_id])) {
                                                echo '<div class="tz_tag"><i class="fa fa-tag" aria-hidden="true"></i> ';
                                                foreach ($tags[$item->content_id] as $t => $tag) {
                                                    echo '<a href="' . $tag->link . '">' . $tag->title . '</a>';
                                                    if ($t != count($tags[$item->content_id]) - 1) {
                                                        echo ', ';
                                                    }
                                                }
                                                echo '</div>';
                                            }
                                        }
                                        if ($params->get('show_category', 1)) {
                                            if (isset($categories[$item->content_id]) && $categories[$item->content_id]) {
                                                if (count($categories[$item->content_id]))
                                                    echo '<div class="TZcategory-name"><i class="tp tp-folder-open"></i>';
                                                foreach ($categories[$item->content_id] as $c => $category) {
                                                    echo '<a itemprop="genre" href="' . $category->link . '">' . $category->title . '</a>';
                                                    if ($c != count($categories[$item->content_id]) - 1) {
                                                        echo ', ';
                                                    }
                                                }
                                                echo '</div>';
                                            }
                                        }
                                        if(isset($item -> event -> afterDisplayAdditionInfo)){
                                            echo $item -> event -> afterDisplayAdditionInfo;
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if ($params->get('show_introtext', 1)) {
                                    ?>
                                    <div class="TzPortfolioIntrotext" itemprop="description"><?php echo $item->introtext;?></div>
                                <?php }

                                if(isset($item -> event -> contentDisplayListView)) {
                                    echo $item->event->contentDisplayListView;
                                }
                                if($params -> get('show_readmore',1)){
                                    ?>
                                    <a href="<?php echo $item->link?>"
                                       class="btn btn-primary readmore"><?php echo $params -> get('readmore_text','Read More');?></a>
                                <?php }
                                ?>
                            </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>