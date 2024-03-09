<?php 
add_action( 'wp_body_open', 'install_js_snippet_sift' );

function install_js_snippet_sift() {
    if(empty(session_id())) session_start();
    $userJson       = (isset($_COOKIE['wp_ing_user'])) ? stripslashes($_COOKIE['wp_ing_user']) : "";
    $_session_id    = session_id();
    $userLogged     = json_decode($userJson, true);
    $_user_id       = (!empty($userLogged["userId"])) ? '31-'.$userLogged["userId"] : '';
    $_beacon_key    = '7014674c22' //Prod : 7014674c22 | DEV : 30c710ae5d
    ?>
    <script type="text/javascript">
        var _user_id    = '<?=$_user_id?>'; // Set to the user's ID, username, or email address, or '' if not yet known.
        var _session_id = '<?=$_session_id?>'; // Set to a unique session ID for the visitor's current browsing session.
        var _beacon_key = '<?=$_beacon_key?>'; 
        var _sift = window._sift = window._sift || [];
        _sift.push(['_setAccount', _beacon_key]);
        _sift.push(['_setUserId', _user_id]);
        _sift.push(['_setSessionId', _session_id]);
        _sift.push(['_trackPageview']);

        (function() {
        function ls() {
            var e = document.createElement('script');
            e.src = 'https://cdn.sift.com/s.js';
            document.body.appendChild(e);
        }
        if (window.attachEvent) {
            window.attachEvent('onload', ls);
        } else {
            window.addEventListener('load', ls, false);
        }
        })();
        jQuery(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.type === 'POST' && settings.data === 'action=ings_fluxo_compra_step_3') {
                const formData = new FormData();
                formData.append('action', "ings_get_data_user");
                (async () => {
                    const rawResponse = await fetch(my_ajax_object.ajax_url, {
                        method: 'POST',
                        body: formData,
                    }).then((response) => {
                        return response.json();
                    }).then( responseGetUserData => {
                        _user_id    = (responseGetUserData.id !== "") ? '31-'+responseGetUserData.id : "";
                        _sift.push(['_setUserId', _user_id]);
                    });
                })();
            }
        });
    </script>
<?php }

