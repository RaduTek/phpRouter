<?php

class Templater {

    // Render a template with a content function
    public static function render($template_file, $insert_content, $insert_title = "") {
        if (!str_ends_with($template_file, '.php'))
            $template_file .= '.php';
        return function(...$insert_args) use ($template_file, $insert_title, $insert_content) {
            $Templater_title = $insert_title;
            $Templater_content = function() use ($insert_content, $insert_args) {
                if (is_callable($insert_content)) {
                    // Content function
                    call_user_func_array($insert_content, $insert_args);
                } else {
                    // Page file
                    if (!str_ends_with($insert_content, '.php'))
                        $insert_content .= '.php';
                    // Export route arguments as variables
                    while ($arg = current($insert_args)) {
                        $arg_key = key($insert_args);
                        $$arg_key = $arg;
                        next($insert_args);
                    }
                    // Include page
                    include_once($insert_content);
                }
            };
            include_once($template_file);
        };
    }

}