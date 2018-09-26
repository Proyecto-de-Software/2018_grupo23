<?php

class TwigRenderer extends TwigView {

    public function show($view, $params = array() ) {
        echo self::getTwig()->render($view, $params);
    }

}
