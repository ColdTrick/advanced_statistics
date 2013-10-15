<?php

advanced_statistics_load_externals();

$body = "<div id='" . $vars["id"] . "' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $vars["title"], $body);