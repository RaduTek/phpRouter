<!DOCTYPE html>
<html>
    <head>
        <title>Template - <?= $Templater_title ?></title>
        <link rel="stylesheet" href="/static/style.css" />
    </head>
    <body>
        <h1>PHP Router + Templater</h1>
        <h2>
            Current Page:
            <?= $Templater_title ?>
        </h2>
        <hr />
        <?php $Templater_content(); ?>
    </body>
</html>
