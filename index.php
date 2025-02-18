<?php

use Kirby\Toolkit\A;

Kirby::plugin("estd/buttons", [
    "hooks" => [
        "panel.route:after" => function (
            Kirby\Http\Route $route,
            $path,
            $method,
            $response
        ) {
            if ($response->type() === "text/html") {
                $options = [];
                $options["marks"] = option("estd.buttons.marks", []);
                $options["links"] = option("estd.buttons.links", []);
                echo "<script nonce='" .
                    kirby()->nonce() .
                    "'>window.estd = {}; window.estd.buttons = JSON.parse('" .
                    json_encode($options) .
                    "');</script>'";
            }
        },
        "system.loadPlugins:after" => function () {
            $marks = option("estd.buttons.marks", []);
            $links = option("estd.buttons.links", []);
            $elems = A::merge($marks, $links);
            // var_dump($marks);
            // exit;
            foreach ($elems as $mark) {
                if (is_array($mark) && isset($mark["tag"])) {
                    // var_dump(Kirby\Sane\Html::$allowedTags[$mark["tag"]]);
                    // exit;
                    if (isset(Kirby\Sane\Html::$allowedTags[$mark["tag"]]) && is_array(Kirby\Sane\Html::$allowedTags[$mark["tag"]])) {
                        Kirby\Sane\Html::$allowedTags[$mark["tag"]] = A::merge(
                            Kirby\Sane\Html::$allowedTags[$mark["tag"]],
                            array_keys($mark["attrs"])
                        );
                    } else {
                        Kirby\Sane\Html::$allowedTags[$mark["tag"]] = array_keys($mark["attrs"]);
                    }
                }
            }
        },
    ],
]);
