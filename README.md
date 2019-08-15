# SEO-
一个Typecho博客SEO优化插件
 
 使用指南:
 下载之后，放入plugins文件夹，在后台启动输入域名和token秘钥
 
 然后在前台主题模版找到index.php，粘贴以下代码:
 
 <?php
        $arr = array(
            'http://www.programcat.top'
        );
        while($this->next()){
            array_push($arr,$this->permalink);
        }
        Typecho_Plugin::factory('index.php')->begin($arr);
    ?>
