<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'BP_Better_Messages_Hooks' ) ):

    class BP_Better_Messages_Hooks
    {

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new BP_Better_Messages_Hooks();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action( 'bp_init', array( $this, 'redirect_standard_component' ) );
            add_action( 'admin_bar_menu', array( $this, 'remove_standard_topbar' ), 999 );

            add_filter( 'bp_nouveau_get_members_buttons',   array( $this, 'pm_link_nouveau' ), 20, 3);
            
            add_filter( 'bp_get_send_private_message_link', array( $this, 'pm_link' ), 20, 1 );
            add_filter( 'yz_get_send_private_message_url',  array( $this, 'pm_link' ), 20, 1 );

            add_filter( 'bp_get_send_message_button_args',  array( $this, 'pm_link_args'), 20, 1 );

            add_filter( 'bp_get_message_thread_view_link',  array( $this, 'thread_link' ), 20, 2 );

            add_filter( 'cron_schedules', array( $this, 'cron_intervals' ) );
	        add_action( 'ajax_query_attachments_args',  array( $this, 'exclude_attachments' ) );

	        add_action( 'wp_head', array( $this, 'themes_adaptation' ) );

            if( BP_Better_Messages()->settings['chatPage'] !== '0' ){
                add_filter( 'the_content', array( $this, 'chat_page' ) );
            }

            add_action( 'admin_notices', array( $this, 'admin_notice') );
            if( BP_Better_Messages()->settings['fastStart'] == '1' ) {
                add_action('template_redirect', array($this, 'catch_fast_thread'));
            }

            if( BP_Better_Messages()->settings['friendsMode'] == '1' && function_exists('friends_check_friendship') ) {
                add_filter( 'bp_better_messages_can_send_message', array( $this, 'disable_non_friends_reply' ), 10, 3);
                add_action( 'bp_better_messages_before_new_thread', array( $this, 'disable_start_thread_for_non_friends' ), 10, 2 );
            }


            /*
             * Block, Suspend, Report for BuddyPress integration
             */
            add_filter( 'bp_better_messages_can_send_message', array( $this, 'disable_message_for_blocked_user' ), 10, 3);


            if( isset(BP_Better_Messages()->settings['restrictNewReplies'])
                && is_array(BP_Better_Messages()->settings['restrictNewReplies'])
                && count(BP_Better_Messages()->settings['restrictNewReplies']) > 0
            ) {
                add_filter( 'bp_better_messages_can_send_message', array( $this, 'disable_message_for_blocked_restricted_role' ), 10, 3);
            }


            if( isset(BP_Better_Messages()->settings['restrictNewThreads'])
                && is_array(BP_Better_Messages()->settings['restrictNewThreads'])
                && count(BP_Better_Messages()->settings['restrictNewThreads']) > 0
            ) {
                add_action( 'bp_better_messages_before_new_thread', array( $this, 'disable_thread_for_blocked_restricted_role' ), 10, 2);
            }

            if( BP_Better_Messages()->settings['singleThreadMode'] == '1' ) {
                add_action( 'bp_better_messages_before_new_thread', array( $this, 'disable_start_thread_if_thread_exist' ), 10, 2 );
            }

            if( BP_Better_Messages()->settings['disableGroupThreads'] == '1' ) {
                add_action( 'bp_better_messages_before_new_thread', array( $this, 'disable_group_threads' ), 10, 2 );
            }

            if( BP_Better_Messages()->settings['mechanism'] == 'websocket' && BP_Better_Messages()->settings['messagesStatus'] == '0'){
                add_action( 'wp_head', array( $this, 'disableStatuses') );
            }

            add_action( 'template_redirect', array( $this, 'update_last_activity' ) );

            // I have 0 idea why this fixes messages link when using Youzer plugin
            add_action( 'init', array( $this, 'fix_youzer' ) );

            add_action( 'bp_screens', array( $this, 'fix_404' ) );

            /*
             * BuddyBoss trying to fix
             */
            add_action('wp_head', array( $this, 'buddyboss_inbox_counter' ) );
            add_filter('messages_thread_get_inbox_count', array( $this, 'replace_unread_count' ), 10, 2 );

            add_action( 'wp_footer', array( $this, 'mobile_popup_button') );

            /*
             * BeeHive premium theme integration
             * https://www.wordplus.org/beehive
            */
            add_action('wp_head', array( $this, 'beehive_theme_integration' ) );
        }

        public function beehive_theme_integration(){
            if( ! class_exists('Beehive') ) return false;

            $options = get_option('beehive_opts', [
                'primary' => '#21759b'
            ]);

            if( is_array($options) && isset($options['primary'] ) ) {
                $main_color = $options['primary'];
                $rgba_color_075 = BP_Better_Messages()->functions->hex2rgba($main_color, 0.075);
                $rgba_color_06  = BP_Better_Messages()->functions->hex2rgba($main_color, 0.6);
                ?><style type="text/css">
                    body.bp-messages-mobile header{
                        display: none;
                    }


                    .bp-messages-wrap.bp-messages-mobile .reply .send button[type=submit]{
                        background: #f7f7f7 !important;
                    }

                    .bp-better-messages-list .tabs>div[data-tab=messages] .unread-count, .bp-better-messages-mini .chats .chat .head .unread-count{
                        background: <?php echo $main_color; ?> !important;
                    }

                    .bp-messages-wrap .chat-header .fas,
                    .bp-messages-wrap .chat-header>a,
                    .bp-messages-wrap .reply .send button[type=submit],
                    .uppy-Dashboard-browse,
                    .bp-messages-wrap.mobile-ready:not(.bp-messages-mobile) .bp-messages-mobile-tap{
                        color: <?php echo $main_color; ?> !important;
                    }

                    .uppy-Dashboard-close .UppyIcon{
                        fill: <?php echo $main_color; ?> !important;
                    }

                    .bp-messages-wrap .bp-emojionearea.focused,
                    .bp-messages-wrap .new-message form>div input:focus,
                    .bp-messages-wrap .active .taggle_list,
                    .bp-messages-wrap .chat-header .bpbm-search form input:focus{
                        border-color: <?php echo $main_color; ?>;
                        -moz-box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                        -webkit-box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                        box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                    }

                    .bp-messages-wrap #send-to .ui-autocomplete{
                        border-color: <?php echo $main_color; ?>;
                        -moz-box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                        -webkit-box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                        box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                    }
                </style>
                <script type="text/javascript">
                    jQuery(document).on('bp-better-messages-update-unread', function( event, unread ) {
                        var private_messages = jQuery('#nav_private_messages');

                        if( unread > 0 ){
                            var count = private_messages.find('span.count');
                            if( count.length === 0 ){
                                private_messages.append('<span class="count">' + unread + '</span>');
                            } else {
                                private_messages.find('.count').text(unread);
                            }
                        } else {
                            private_messages.find('span.count').remove();
                        }
                    });
                </script>
                <?php
            }
        }

        public function update_last_activity(){
            if( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                bp_update_user_last_activity($user_id);
            }
        }

        public function mobile_popup_button(){
            if( ! is_user_logged_in() || BP_Better_Messages()->settings['mobilePopup'] == '0' ) return '';
            $count = BP_Messages_Thread::get_total_threads_for_user( get_current_user_id(), 'inbox', 'unread' );
            $class = ($count === 0) ? 'no-count' : '';
            echo '<div id="bp-better-messages-mini-mobile-open" class="bp-messages-wrap"><i class="fas fa-comments"></i><span class="count ' . $class . ' bp-better-messages-unread">' . $count . '</span></div>';
            echo '<div id="bp-better-messages-mini-mobile-container" class="bp-messages-wrap"></div>';
        }

        public function buddyboss_inbox_counter(){
            if( class_exists('BuddyBoss_Theme') ){ ?>
                <script type="text/javascript">
                    jQuery(document).on('bp-better-messages-update-unread', function( event, unread ) {
                        var messages_count = jQuery('.header-notifications.user-messages span');
                        if( unread > 0 ){
                            messages_count.text(unread).attr('class', 'count');
                        } else {
                            messages_count.text(unread).attr('class', 'no-alert');
                        }
                    });
                </script>
            <?php } else if( function_exists( 'buddyboss_theme_register_required_plugins' ) ){ ?>
                <script type="text/javascript">
                    jQuery(document).on('bp-better-messages-update-unread', function( event, unread ) {
                        var messages_count = jQuery('.notification-wrap.messages-wrap .count');
                        if( unread > 0 ){
                            messages_count.text(unread).show();
                        } else {
                            messages_count.text(unread).hide();
                        }
                    });
                </script>
            <?php }
        }



        public function replace_unread_count( $unread_count, $user_id ){
            return BP_Messages_Thread::get_total_threads_for_user( $user_id, 'inbox', 'unread' );
        }
        

        public function fix_404(){
            if ( function_exists('bp_core_no_access') && bp_is_current_component( 'bp-messages' ) && ! is_user_logged_in() ) {
                bp_core_no_access();
            }
        }

        public function fix_youzer(){
            if( function_exists('bp_nav_menu_get_item_url') ){
                $messages_link = bp_nav_menu_get_item_url( 'messages' );
            }
        }

        public function themes_adaptation(){
            $theme = wp_get_theme();
            $theme_name = $theme->get_template();

            switch ($theme_name){
                case 'boss':
                    echo '<style type="text/css">';
                    echo 'body.bp-messages-mobile #mobile-header{display:none}';
                    echo 'body.bp-messages-mobile #inner-wrap{margin-top:0}';
                    echo 'body.bp-messages-mobile .site{min-height:auto}';
                    echo '</style>';
                    break;
            }
        }

        public function disableStatuses(){
            ?><style type="text/css">.bp-messages-wrap .list .messages-stack .content .messages-list li .status{display: none !important;}.bp-messages-wrap .list .messages-stack .content .messages-list li .favorite{right: 5px !important;}</style><?php
        }

        public function disable_group_threads(&$args, &$errors){
            $recipients = $args['recipients'];
            if(count($recipients) > 1) {
                $message = __('You can start conversation only with 1 user per thread', 'bp-better-messages');
                $errors[] = $message;
            }
        }

        public function disable_message_for_blocked_user( $allowed, $user_id, $thread_id ){
            if( ! class_exists('BPTK_Block') ) return $allowed;

            $participants = BP_Better_Messages()->functions->get_participants($thread_id);

            foreach( $participants['recipients'] as $recipient_user_id ){
                $list = get_user_meta( $recipient_user_id, 'bptk_block', true );
                if ( empty($list) ) {
                    $list = array();
                }
                $_list = apply_filters( 'get_blocked_users', $list, $recipient_user_id );
                $recipient_blocked = array_filter( $_list );

                if( in_array( $user_id, $recipient_blocked ) ){
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['blocked_by_user'] = __('You was blocked by recipient', 'bp-better-messages');
                    $allowed = false;
                }
            }


            return $allowed;
        }

        public function disable_message_for_blocked_restricted_role( $allowed, $user_id, $thread_id ){

            $user             = wp_get_current_user();
            $restricted_roles = (array)  BP_Better_Messages()->settings['restrictNewReplies'];
            $user_roles       = (array) $user->roles;

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['role_reply_restricted'] = BP_Better_Messages()->settings['restrictNewRepliesMessage'];
            }
            return $allowed;
        }

        public function disable_thread_for_blocked_restricted_role( &$args, &$errors ){

            $user             = wp_get_current_user();
            $restricted_roles = (array)  BP_Better_Messages()->settings['restrictNewThreads'];
            $user_roles       = (array) $user->roles;

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ) {
                $errors['restrictNewThreadsMessage'] = BP_Better_Messages()->settings['restrictNewThreadsMessage'];
            }

        }

        public function disable_non_friends_reply( $allowed, $user_id, $thread_id ){
            $participants = BP_Better_Messages()->functions->get_participants($thread_id);
            if(count($participants['users']) !== 2) return $allowed;
            unset($participants['users'][$user_id]);
            reset($participants['users']);

            $friend_id = key($participants['users']);
            return friends_check_friendship($user_id, $friend_id);
        }

        public function disable_start_thread_if_thread_exist(&$args, &$errors){
            $recipients = $args['recipients'];
            if(count($recipients) > 1) return false;
            $threadExists = array();
            foreach($recipients as $recipient){
                $user = get_user_by('slug', $recipient);

                $from = get_current_user_id();
                $to   = $user->ID;

                $threads = BP_Better_Messages()->functions->find_existing_threads($from, $to);

                if( count($threads) > 0) {
                    $threadExists[] = $recipient;

                    if(BP_Better_Messages()->settings['redirectToExistingThread'] == '1'){
                        $args['thread_id'] = $threads[0];
                        messages_new_message( $args );

                        wp_send_json( array(
                            'result'   => $threads[0]
                        ) );
                        exit;
                    }
                }
            }

            if(count($threadExists) > 0){
                $message = sprintf(__('You already have threads with %s', 'bp-better-messages'), implode(', ', $threadExists));
                $errors[] = $message;
            }
        }

        public function disable_start_thread_for_non_friends(&$args, &$errors){
            $recipients = $args['recipients'];

            $notFriends = array();
            foreach($recipients as $recipient){
                $user = get_user_by('slug', $recipient);
                if( ! friends_check_friendship( get_current_user_id(), $user->ID ) ) {
                    $notFriends[] = $recipient;
                }
            }

            if(count($notFriends) > 0){
                $message = sprintf(__('%s not on your friends list', 'bp-better-messages'), implode(', ', $notFriends));
                $errors[] = $message;
            }
        }

        public function catch_fast_thread(){
            if(
                (rtrim(str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '?') === str_replace(site_url(''), '', BP_Better_Messages()->functions->get_link()))
                && isset($_GET['new-message'])
                && isset($_GET['fast'])
                && isset($_GET['to'])
                && ! empty($_GET['fast'])
                && ! empty($_GET['to'])
            ){
                $to = get_user_by('slug', sanitize_text_field($_GET['to']));
                if( ! $to ) return false;

                if( BP_Better_Messages()->settings['singleThreadMode'] == '1' ) {
                    $threads = BP_Better_Messages()->functions->find_existing_threads(get_current_user_id(), $to->ID);
                    if( count($threads) > 0) {
                        wp_redirect(BP_Better_Messages()->functions->get_link() . '?thread_id=' . $threads[0]);
                        exit;
                    }
                }

                $thread_id = BP_Better_Messages()->functions->get_pm_thread_id($to->ID);
                $url = BP_Better_Messages()->functions->get_link() . '?thread_id=' . $thread_id;

                wp_redirect($url);
                exit;
            }
        }

        public function admin_notice(){
            if( ! class_exists('BuddyPress')){
               if(BP_Better_Messages()->settings['chatPage'] == '0'){
                   echo '<div class="notice notice-error">';
                   echo '<p><b>BP Better Messages</b> require <b><a href="'. admin_url('options-general.php?page=bp-better-messages#general').'">installing Chat Page</a></b> or installed <b>BuddyPress</b> with <b>Messages Component</b> active.</p>';
                   echo '</div>';
               }
            } else {
                if( ! bp_is_active('messages') ) {
                    echo '<div class="notice notice-error">';
                    echo '<p><b>BP Better Messages</b> require <b>BuddyPress</b> <b>Messages Component</b> to be active. <a href="'.admin_url('options-general.php?page=bp-components').'">Activate</a></p>';
                    echo '</div>';
                }
            }
        }

        public function chat_page($content){
            $page_id = get_the_ID();
            $chat_page_id = BP_Better_Messages()->settings['chatPage'];

            if( defined('ICL_LANGUAGE_CODE') ){
                $chat_page_id = apply_filters( 'wpml_object_id', $chat_page_id, 'page', true, ICL_LANGUAGE_CODE );
            }

            if( $chat_page_id != $page_id ) return $content;

            if( ! is_user_logged_in() ){
                ob_start();
                wp_login_form();
                return ob_get_clean();
            }

            error_reporting(0);

            $path = BP_Better_Messages()->path . '/views/';

            $template = false;
            $thread_id = false;

            if ( isset( $_GET[ 'thread_id' ] ) ) {
                $thread_id = absint( $_GET[ 'thread_id' ] );
                if ( ! BP_Messages_Thread::check_access( $thread_id ) && ! current_user_can('manage_options') ) {
                    $thread_id = false;
                    echo '<p>' . __( 'Access restricted', 'bp-better-messages' ) . '</p>';
                    $template = 'layout-index.php';
                } else {
                    $template =  'layout-thread.php';
                }
            } else if ( isset( $_GET[ 'new-message' ] ) ) {
                $template =  'layout-new.php';
            } else if ( isset( $_GET[ 'starred' ] ) ) {
                $template = 'layout-starred.php';
            } else if ( isset( $_GET[ 'search' ] ) ) {
                $template = 'layout-search.php';
            } else if ( isset( $_GET[ 'bulk-message' ] ) && current_user_can('manage_options')){
                $template = 'layout-bulk.php';
            } else if (isset( $_GET[ 'settings' ] ) ){
                $template = 'layout-user-settings.php';
            } else {
                $template = 'layout-index.php';
            }

            $template = apply_filters( 'bp_better_messages_current_template', $template );

            ob_start();

            if($template !== false) include($path . $template);

            if( isset($thread_id) && is_int($thread_id)  && ! isset($_GET['mini']) ){
                messages_mark_thread_read( $thread_id );
                update_user_meta(get_current_user_id(), 'bpbm-last-seen-thread-' . $thread_id, time());
            }

            $messages_content = ob_get_clean();

            if( strpos($content, '[bp-better-messages]') !== FALSE ){
                $content = str_replace( '[bp-better-messages]', $messages_content, $content );
            } else {
                $content = $messages_content;
            }

            return $content;
        }

        function exclude_attachments($query){
	        if( BP_Better_Messages()->settings['attachmentsHide'] !== '1' ) return $query;

		    $meta_query = $query['meta_query'];
	        if( ! is_array($meta_query) ) $meta_query = array();

	        $meta_query[] = array(
	        	'key'     => 'bp-better-messages-attachment',
		        'value'   => '1',
		        'compare' => 'NOT EXISTS'
	        );

	        $query['meta_query'] = $meta_query;
	        return $query;
        }

        function cron_intervals( $schedules )
        {
            /*
             * Cron for our new mailer!
             */
            $schedules[ 'fifteen_minutes' ] = array(
                'interval' => 60 * 15,
                'display'  => esc_html__( 'Every Fifteen Minutes' ),
            );

            $schedules[ 'one_minute' ] = array(
                'interval' => 60,
                'display'  => esc_html__( 'Every Minute' ),
            );

            return $schedules;
        }

        function pm_link_nouveau($buttons, $user_id, $type){
            $buttons['private_message']['button_attr']['href'] = $this->pm_link();
            return $buttons;
        }

        public function pm_link( $link = false )
        {
            if( BP_Better_Messages()->settings['fastStart'] == '1' ){
                return BP_Better_Messages()->functions->get_link(get_current_user_id()) . '?new-message&fast=1&to=' . bp_core_get_username( BP_Better_Messages()->functions->get_member_id() );
            } else {
                return BP_Better_Messages()->functions->get_link(get_current_user_id()) . '?new-message&to=' . bp_core_get_username( BP_Better_Messages()->functions->get_member_id() );
            }
        }

        public function pm_link_args($args){
            if ( ! is_user_logged_in() ) {
                return false;
            }

            $args['link_href'] = $this->pm_link();

            return $args;
        }

        public function thread_link( $thread_link, $thread_id )
        {
            return BP_Better_Messages()->functions->get_link() . '?thread_id=' . $thread_id;
        }

        public function redirect_standard_component()
        {
            if ( bp_is_messages_component() ) {
                $link = BP_Better_Messages()->functions->get_link();

                if(bp_action_variable(0) !== false){
                    $link = BP_Better_Messages()->functions->get_link() . '?thread_id=' . bp_action_variable(0);
                }

                wp_redirect( $link );
                exit;
            }
        }

        public function remove_standard_topbar( $wp_admin_bar )
        {
            $wp_admin_bar->remove_node( 'my-account-messages' );
        }

    }

endif;

function BP_Better_Messages_Hooks()
{
    return BP_Better_Messages_Hooks::instance();
}
