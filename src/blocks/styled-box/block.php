<?php

function dbe_render_styled_box_bordered_content($attributes, $content){
    return $content;
}

function dbe_register_styled_box_bordered_box_block() {
	if( function_exists( 'register_block_type' ) ) {
		register_block_type( 'dbe/styled-box-bordered-content', array(
            'attributes' => array(),
            'render_callback' => 'dbe_render_styled_box_bordered_content')
        );
    }
}

add_action('init', 'dbe_register_styled_box_bordered_box_block');

function dbe_render_styled_box_numbered_box_column($attributes, $content){
    extract($attributes);
    return '<div class="number-panel">
        <div class="number-container">
            <p class="number-display">' . $number . '</p>
        </div>
        <p class="number-box-title">' . $title . '</p>
        <div class="number-box-body">' . $content . '</div>
    </div>';
}

function dbe_register_styled_box_numbered_box_column_block() {
	if( function_exists( 'register_block_type' ) ) {
		register_block_type( 'dbe/styled-box-numbered-box-column', array(
            'attributes' => array(
                'number' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'title' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'titleAlign' => array(
                    'type' => 'string',
                    'default' => 'center'
                ),
                'numberColor' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'backColor' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'borderColor' => array(
                    'type' => 'string',
                    'default' => ''
                )
            ),
            'render_callback' => 'dbe_render_styled_box_numbered_box_column')
        );
    }
}

add_action('init', 'dbe_register_styled_box_numbered_box_column_block');

function dbe_render_styled_box_block($attributes, $content){
    extract($attributes);
    $renderedBlock = '';
    if($mode === 'notification' && $text[0] != ''){
        $renderedBlock = '<div class="notification-text">'.$text[0].'</div>';
    }
    else if($mode === 'feature'){
        foreach(range(0, count($text)-1) as $i){
            $renderedBlock .= '<div class="feature">'.
                ($image[$i]['url'] === '' ? '' :
                    '<img class="feature-img" src="'.$image[$i]['url'].'"/>').
                    '<p class="feature-title">'.$title[$i].'</p>
                    <p class="feature-body">'.$text[$i].'</p>
            </div>';
        }
    }
    else if($mode === 'number'){
        if(count( array_filter($text, function($item){return $item !== '';}) ) > 0 ||
                count( array_filter($title, function($item){return $item !== '';}) ) > 0){
            foreach(range(0, count($text)-1) as $i){
                $renderedBlock .= '<div class="number-panel">
                    <div class="number-container">
                        <p class="number-display">'.$number[$i].'</p>
                    </div>
                    <p class="number-box-title">'.$title[$i].'</p>
                    <p class="number-box-body">'.$text[$i].'</p>
                </div>';
            }
        }
        else {
            $renderedBlock = $content;
        }
    }
    else if(in_array($mode, array('bordered', 'notification'))){
        $renderedBlock = $content;
    }

    return '<div class="styled-box '.$mode.'-box'.(isset($className) ? ' ' . esc_attr($className) : '')
            .'" id="styled-box-'.$blockID.'">'.
                $renderedBlock.'</div>';
}

function dbe_register_styled_box_block() {
	if( function_exists( 'register_block_type' ) ) {
        require dirname(dirname(__DIR__)) . '/defaults.php';
		register_block_type( 'dbe/styled-box', array(
            'attributes' => $defaultValues['dbe/styled-box']['attributes'],
            'render_callback' => 'dbe_render_styled_box_block'));
    }
}

add_action('init', 'dbe_register_styled_box_block');