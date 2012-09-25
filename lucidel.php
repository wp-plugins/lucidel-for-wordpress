<?php

/*
Plugin Name: lucidel
Plugin URI: http://lucidel.com
Description: lucidel plugin for wordpress. <strong>You must have an account with Lucidel for this plugin to work.</strong><p>If you do not have one,  Get one <a href="http://staging.lucidel.com/choose_plan/?installed=1">here !</p>
Version: 1.0
Author: Lucidel
Author URI: http://lucidel.com
*/

    class lucidel {

        public $adminOptionsName = "LucidelAdminOptions";

        #constructor
        public function lucidel(){


        }

        #init
        public function init(){

            #$this->codeOption();

        }

        #set code options
        public function codeOption(){

            $lucidelAdminOptions = array('code' => '0000');

            $options = get_option($this->adminOptionsName);

            if (!empty($options)){

                foreach ($options as $key => $option){

                    $lucidelAdminOptions[$key] = $option;

                }
            }

            update_option($this->adminOptionsName, $lucidelAdminOptions);
            return $lucidelAdminOptions;

        }

        #get option and put into JS
        public function addJsCode(){

            $options = $this->codeOption();

            $code = "<script type='text/javascript'>
                        (function() {
                            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
                            s.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'js.lucidel.com/script/" . $_SERVER['SERVER_NAME'] . ".js';
                            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
                        })();
                    </script>";

            echo $code;


        }

        #update option, show admin form
        public function adminPage(){

            $options = $this->codeOption();

            if($_REQUEST['code']){

                $options['code'] = $_REQUEST['code'];

            }

            update_option($this->adminOptionsName, $options);

            echo '<div class="wrap">';

            echo '<h2>Lucidel settings</h2>';
            echo '<form action = "' . $_SERVER["REQUEST_URI"] . '" method = "POST">';
            echo '<input type = "text" name = "code" value = "' . $options['code'] . '">';
            echo '<input type = "submit" name = "submit" value = "Save">';
            echo '</form>';
            echo 'Do not know your ID or do not have your lucidel account ? ';
            echo '<a href = "http://lucidel.com/plugin/wp/?callback=' . urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) . '">click here</a>';

            echo '</div>';

        }

        public function warning(){
          echo "<div id=\"message\" class=\"error\"><p><b>You must have an account with Lucidel for the plugin to work. If you don't have one,  Get one <a href=\"http://staging.lucidel.com/choose_plan/?callback=" . urlencode('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']) . "\">here !</p></div>";
        }

    }

    #init plugin class
    $lucidel = new lucidel;

    #add js
    add_action('wp_head', array($lucidel , 'addJsCode'));

    #add_action('admin_footer', array(&$lucidel, 'warning') );

    function adminPage() {

        global $lucidel;

        add_options_page('Lucidel', 'Lucidel', 8, basename(__FILE__), array($lucidel, 'adminPage'));
        add_action('activate_lucidel/lucidel.php',  array($lucidel, 'init'));

    }

    #add admin page
    add_action('admin_menu', 'adminPage');







