<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <title><?php echo __FILE__; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css" media="screen">
        body {
            margin: 2em;
            padding: 0;
            border: 0;
            font: 1em verdana, helvetica, sans-serif;
            color: #000;
            background: #fff;
            text-align: center;
        }
        ol.code {
            width: 90%;
            margin: 0 5%;
            padding: 0;
            font-size: 0.75em;
            line-height: 1.8em;
            overflow: hidden;
            color: #939399;
            text-align: left;
            list-style-position: inside;
            border: 1px solid #d3d3d0;
        }
        ol.code li {
            float: left;
            clear: both;
            width: 99%;
            white-space: nowrap;
            margin: 0;
            padding: 0 0 0 1%;
            background: #fff;
        }
        ol.code li.even { background: #f3f3f0; }
        ol.code li code {
            font: 1.2em courier, monospace;
            color: #c30;
            white-space: pre;
            padding-left: 0.5em;
        }
        .code .comment { color: #939399; }
        .code .default { color: #44c; }
        .code .keyword { color: #373; }
        .code .string { color: #c30; }
    </style>
</head>
<body>
<?php

// Use this file to produce an example listing.
$code = file_get_contents(__FILE__);

ini_set('highlight.comment', 'comment');
ini_set('highlight.default', 'default');
ini_set('highlight.keyword', 'keyword');
ini_set('highlight.string', 'string');
ini_set('highlight.html', 'html');

$code = highlight_string($code, TRUE);

// Clean up.
$code = substr($code, 33, -15);
$code = str_replace('&nbsp;', ' ', $code);
$code = str_replace('&amp;', '&#38;', $code);
$code = str_replace('<br />', "\n", $code);
$code = str_replace('<span style="color: ', '<span class="', $code);
$code = trim($code);

// Normalize newlines.
$code = str_replace("\r", "\n", $code);
$code = preg_replace("!\n\n\n+!", "\n\n", $code);

$lines = explode("\n", $code);

// Initialize previous style.
$previous = FALSE;

// Output listing.
echo "  <ol class=\"code\">\n";
foreach ($lines as $key => $line) {
    if (substr($line, 0, 7) == '</span>') {
        $previous = FALSE;
        $line = substr($line, 7);
    }

    if (empty($line)) {
        $line = '&#160;';
    }

    if ($previous) {
        $line = "<span class=\"$previous\">" . $line;
    }

    // Set previous style.
    if (strpos($line, '<span') !== FALSE) {
        switch (substr($line, strrpos($line, '<span') + 13, 1)) {
            case 'c':
                $previous = 'comment';
                break;
            case 'd':
                $previous = 'default';
                break;
            case 'k':
                $previous = 'keyword';
                break;
            case 's':
                $previous = 'string';
                break;
        }
    }

    // Unset previous style unless span continues.
    if (substr($line, -7) == '</span>') {
        $previous = FALSE;
    } elseif ($previous) {
        $line .= '</span>';
    }

    $num = $key + 1;
    if ($key % 2) {
        echo "    <li id=\"line-$num\" class=\"even\"><code>$line</code></li>\n";
    } else {
        echo "    <li id=\"line-$num\"><code>$line</code></li>\n";
    }
}
echo "  </ol>\n";

?>
</body>

</html>
