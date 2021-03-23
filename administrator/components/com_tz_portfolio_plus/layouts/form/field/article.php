<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015-2017 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

extract($displayData);

$allowEdit  = false;

if(isset($field) && $field) {
    if($edit = $field -> getAttribute('edit')) {
        $allowEdit  = $edit;
    }
}
?>

<?php
$function   = 'tppSelectArticle_'.$id;
$modalId    = 'tppModalArticle_' . $id;
// Render the modal
echo JHtml::_(
    'bootstrap.renderModal',
    $modalId,
    array(
        'url'        => $link.'&function='.$function,
        'title'      => JText::_('COM_TZ_PORTFOLIO_PLUS_CHANGE_ARTICLE'),
        'width'      => '400px',
        'height'     => '800px',
        'modalWidth' => '70',
        'bodyHeight' => '70',
        'closeButton' => true,
        'footer'      => '<a class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JCANCEL') . '</a>',
    )
);
?>
<div class="input-append">
    <input type="text" <?php echo $required; ?> readonly="readonly" id="<?php echo $id; ?>_name" value="<?php
            echo $title; ?>" <?php echo $size . $class; ?>  placeholder="<?php echo JText::_('COM_TZ_PORTFOLIO_PLUS_SELECT_AN_ARTICLE');?>" />
    <a id="<?php echo $id; ?>_select" class="btn btn-primary hasTooltip" title="<?php echo JText::_('COM_TZ_PORTFOLIO_PLUS_CHANGE_ARTICLE');
        ?>" data-toggle="modal" href="#<?php echo $modalId;?>"><i class="icon-file"></i><?php
        echo JText::_('JSELECT');?></a>
    <?php if($allowEdit){?>
        <a id="<?php echo $id; ?>_edit" class="btn<?php echo $value ? '' : ' hidden';?>" target="_blank"
           href="index.php?option=com_tz_portfolio_plus&task=article.edit&id=<?php
           echo $value; ?>"><span class="icon-edit"></span><?php echo JText::_('JACTION_EDIT'); ?></a>
    <?php } ?>
    <a href="javascript:" id="<?php echo $id; ?>_clear" class="btn btn-danger<?php echo $value ? '' : ' hidden';?>" onclick="return tppClearArticle('<?php
    echo $id; ?>')"><span class="icon-remove"></span> <?php echo JText::_('JCLEAR'); ?></a>
</div>

    <input class="input-small" id="<?php echo $id; ?>" type="hidden" name="<?php echo $name; ?>" value="<?php
    echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8') ?>"/>
<?php
$doc    = JFactory::getDocument();
$doc -> addScriptDeclaration('
    (function($, window){
        "use strict";
        window.tppClearArticle = function(id) {
            $("#" + id + "_name").val("");
            $("#" + id ).val("");
            $("#" + id + "_clear").addClass("hidden");
            $("#" + id + "_edit").addClass("hidden");
            $("#" + id + "_select").removeClass("hidden");
                '.($submitform?'$("#'.$id.'").parents("form").first().submit()':'').'
            return false;
        };
        window.'.$function.' = function(id, title, category){
            if(id.length){
                var fieldId = "'.$id.'";
                $("#" + fieldId).val(id);
                $("#" + fieldId + "_name").val(title);
                $("#'.$modalId.'").modal("hide");
                $("#" + fieldId + "_clear").removeClass("hidden");
                '.($allowEdit?'
                $("#" + fieldId + "_edit").removeClass("hidden")
                    .attr("href",function(index, href){
                        return "index.php?option=com_tz_portfolio_plus&task=article.edit&id="+id;
                    });
                $("#" + fieldId + "_select").addClass("hidden");':'')
                .($submitform?'$("#'.$id.'").parents("form").first().submit()':'').'
            }
        };
    })(jQuery, window);');