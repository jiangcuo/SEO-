<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 百度seo插件
 * @package BaiduLinkSeo
 * @author dlmg
 * @version 1.0.1
 * @link 
 */
class BaiduLinkSeo_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {

        Typecho_Plugin::factory('index.php')->begin = array('BaiduLinkSeo_Plugin', 'render');
        return _t('请设置 <b>站点域名</b> 和 <b>密钥</b>');

    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
         preg_match("/^(http(s)?:\/\/)?([^\/]+)/i", Helper::options()->siteUrl, $matches);
        $domain = $matches[2] ? $matches[2] : '';
        $site = new Typecho_Widget_Helper_Form_Element_Text('site', NULL, $domain, _t('站点域名'), _t('站长工具中添加的域名'));
        $form->addInput($site->addRule('required', _t('请填写站点域名')));

        $token = new Typecho_Widget_Helper_Form_Element_Text('token', NULL, '', _t('准入密钥'), _t('更新密钥后，请同步修改此处密钥，否则身份校验不通过将导致数据发送失败。'));
        $form->addInput($token->addRule('required', _t('请填写准入密钥')));
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render($widget)
    {
        $options = Helper::options();

        $site = $options->plugin('BaiduLinkSeo')->site;
        $token = $options->plugin('BaiduLinkSeo')->token;

        $urls = $widget;
        $api = sprintf('http://data.zz.baidu.com/urls?site=%s&token=%s', $site, $token);

        $client = Typecho_Http_Client::get();

        if ($client) {

            $client->setData( implode(PHP_EOL, $urls ) )
                ->setHeader('Content-Type', 'text/plain')
                ->setTimeout(30)
                ->send($api);

            $status = $client->getResponseStatus();
            $rs = $client->getResponseBody();
//            var_dump($rs);
        }

    }
}
