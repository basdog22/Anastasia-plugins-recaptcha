<?php


use \ReCaptcha\ReCaptcha;

class recaptchacheck{
    public function check(){
        $rec = requested('g-recaptcha-response');
        $secret = get_config_value('recaptcha_secret');
        if (isset($rec)){

            $recaptcha = new ReCaptcha($secret);
            $resp = $recaptcha->verify($rec, $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()){
                return t('recaptcha::strings.captcha_wrong');
            }

        }

    }
}

register_plugin_route_filter('recaptchacheck@check');


register_tpl_hook('form.closes.here','recaptcha::recaptcha');


function recaptcha_install(){
    \Settings::create(
        array(
            'namespace' => 'cms',
            'setting_name' => 'recapctha_sitekey',
            'setting_value' => '0',
            'autoload' => 1,
        )
    );
    \Settings::create(
        array(
            'namespace' => 'cms',
            'setting_name' => 'recaptcha_secret',
            'setting_value' => '0',
            'autoload' => 1,
        )
    );
}
function recaptcha_uninstall(){
    $setting = \Settings::whereNamespace('cms')->whereSettingName('recapctha_sitekey')->first();
    $setting->delete();
    $setting = \Settings::whereNamespace('cms')->whereSettingName('recaptcha_secret')->first();
    $setting->delete();
}

register_plugin_install_handler('recaptcha','recaptcha_install');
register_plugin_uninstall_handler('recaptcha','recaptcha_uninstall');