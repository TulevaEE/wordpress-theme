<?php
/*
Template Name: Fund shared documents
*/

// This page only holds the documents every fund page links (helpers/acf/fund-disclosures.php).
// It is never shown: it answers 404 to everyone, even if it is published by mistake.
global $wp_query;
$wp_query->set_404();
status_header(404);
nocache_headers();
get_template_part('404');
