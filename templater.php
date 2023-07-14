<?php

class Templater {

    // Render a template with a content function
    public static function render($template_file, $insert_content, $insert_title = "") {
        if (!str_ends_with($template_file, '.php'))
            $template_file .= '.php';
        return function(...$insert_args) use ($template_file, $insert_title, $insert_content) {
            $Templater_title = $insert_title;
            $Templater_content = function() use ($insert_content, $insert_args) {
                call_user_func_array($insert_content, $insert_args);
            };
            include_once($template_file);
        };
    }

}