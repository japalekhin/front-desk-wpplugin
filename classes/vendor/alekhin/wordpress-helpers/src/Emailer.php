<?php

namespace Alekhin\WordPressHelpers;

if (!class_exists(__NAMESPACE__ . '\Emailer')) {

    class Emailer {

        static function make_html_email($subject, $body) {
            $html = '';
            $html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            $html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
            $html .= '<head>';
            $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
            $html .= '<title>' . $subject . '</title>';
            $html .= '<style type="text/css">#outlook a{padding:0}body{font-family:sans-serif;width:100%!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:100%}#backgroundTable{margin:0;padding:0;width:100%!important;line-height:100%!important}img{outline:none;text-decoration:none;-ms-interpolation-mode:bicubic}a img{border:none}.image_fix{display:block}p{margin:1em 0}h1,h2,h3,h4,h5,h6{color:#000!important}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{color:blue!important}h1 a:active,h2 a:active,h3 a:active,h4 a:active,h5 a:active,h6 a:active{color:red!important}h1 a:visited,h2 a:visited,h3 a:visited,h4 a:visited,h5 a:visited,h6 a:visited{color:purple!important}table td{border-collapse:collapse}table{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}a{color:#2980b9}@media only screen and (max-device-width: 480px){a[href^="tel"],a[href^="sms"]{text-decoration:none;color:#000;pointer-events:none;cursor:default}.mobile_link a[href^="tel"],.mobile_link a[href^="sms"]{text-decoration:default;color:orange!important;pointer-events:auto;cursor:default}}@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){a[href^="tel"],a[href^="sms"]{text-decoration:none;color:blue;pointer-events:none;cursor:default}.mobile_link a[href^="tel"],.mobile_link a[href^="sms"]{text-decoration:default;color:orange!important;pointer-events:auto;cursor:default}}#box_main_container{text-align:justify}#box_content{padding:40px}#box_footer{font-size:12px}</style><!--[if IEMobile 7]><style type="text/css"></style><![endif]--><!--[if gte mso 9]><style></style><![endif]-->';
            $html .= '</head>';
            $html .= '<body>';
            $html .= '<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">';
            $html .= '<tr>';
            $html .= '<td id="box_main_container">';
            $html .= '<table cellpadding="0" cellspacing="0" border="0" align="center">';
            $html .= '<tr>';
            $html .= '<td width="200" valign="top"></td>';
            $html .= '<td width="200" valign="top"></td>';
            $html .= '<td width="200" valign="top"></td>';
            $html .= '</tr>';
            $html .= '</table>';

            $html .= '<div id="box_content">';
            $html .= $body;
            $html .= '</div>';
            $html .= '<hr />';
            $html .= '<br />';
            $html .= '<div id="box_footer">This email was sent by <a href="' . home_url() . '">' . get_bloginfo('name') . '</a>.</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</body>';
            $html .= '</html>';
            return $html;
        }

        static function send($recipient, $subject, $body) {
            add_filter('wp_mail_content_type', [__CLASS__, 'filter_wp_mail_content_type',]);
            $result = wp_mail($recipient, $subject, self::make_html_email($subject, $body));
            remove_filter('wp_mail_content_type', [__CLASS__, 'filter_wp_mail_content_type',]);
            return $result;
        }

        static function notify_admins($subject, $body) {
            foreach (users::get_admins() as $admin_id) {
                $admin_email = users::get_email($admin_id);
                self::send($admin_email, $subject, $body);
            }
        }

        static function filter_wp_mail_content_type($type) {
            $type = 'text/html';
            return $type;
        }

        static function filter_wp_mail_from($oval) {
            // this function is not used. to use add the line below somewhere
            // else and change the value
            //add_filter('wp_mail_from', [__CLASS__, 'filter_wp_mail_from',]);
            return 'email@example.com';
        }

        static function filter_wp_mail_from_name($oval) {
            // this function is not used. to use add the line below somewhere
            // else and change the value
            //add_filter('wp_mail_from_name', [__CLASS__, 'filter_wp_mail_from_name',]);
            return 'Someone';
        }

    }

}
