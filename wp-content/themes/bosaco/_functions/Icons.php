<?php

/* ========================================================================================================================
  Icon Function (show icon from your icons SVG sprite)
======================================================================================================================== */
function icon($iconName, $classes = null) {
  echo '<svg class="icon icon-'.$iconName.' '.$classes.'"><use href="'.get_stylesheet_directory_uri().'/_assets/images/icons-sprite.svg#icon-'.$iconName.'"></use></svg>';
}

?>
