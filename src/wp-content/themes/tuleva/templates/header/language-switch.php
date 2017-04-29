<?php
$languages = icl_get_languages('skip_missing=0&orderby=code');

if (!empty($languages)) {
    foreach($languages as $l) {
        $link = '<a href="'.$l['url'].'" class="nav-lang visible-xs';

        if ($l['active']) {
            $link .= ' active">';
        } else {
            $link .='">';
        }

        if ($l['code'] === 'en') {
            $link .= 'In ';
        }

        $link .= icl_disp_language($l['native_name']);

        if ($l['code'] === 'et') {
            $link .= ' keeles';
        }

        $link .= '</a>';

        if (!$l['active']) {
            echo $link;
        }
    }
}

unset($languages);
