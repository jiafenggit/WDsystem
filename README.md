 涉及到的资源 laravel | webdriver | firefox | geckodriver | selenium
 
 ###该项目用于练手。
 
 涉及到的相关资源
 ````
 •	Php-webdriver（https://github.com/popzhangzhi/php-webdriver/）
 •	Selenium服务器端 （http://seleniumrelease.storage.googleapis.com/index.html?path=3.4/）selenium-server-standalone-3.4.0.jar
 •	Firefox+Geckodriver驱动（https://github.com/lmc-eu/steward/wiki/Selenium-server-&-browser-drivers#option-2-start-selenium-server--browser-inside-docker-）
 •  laravel 5.4
 •  php 7.1 （至少大于5.6，因为laravel需求） 
 •  nginx
 •  mysql 5.7
 ````
 
 #####接下来是启动项目流程的命令
 
 因为没有配置自动启动的原因，需要手动启动（mac）
 
 本机启动 nginx
 
 启动php-fpm:sudo php-fpm -y /etc/php-fpm.conf (
 因为升级php到7.1.4 需要手动加载配置文件。)
 
 启动selenium:java  -jar selenium-server-standalone-3.4.0.jar
 
 这里注意需要下载geckodricer驱动firefox游览器，启动命令时会自动查找该插件，找不到需要手动指定
 ````
 java -Dwebdriver.gecko.driver = "/Users/zhangzhi/PhpstormProjects/php-webdriver/php-webdriver/geckodriver" -jar selenium-server-standalone-3.4.0.jar
 我在mac 尝试手动指定未成功，原因未知。
 ````
 
 以上为正常启动php驱动firefox游览器，可以实现php控制游览器操作，模拟人工的形式去游览网页，提交，上传，下载。

 
 
 ####已实现功能：模拟游览器人工登录网站。
 
 环境：只能在shell下运行，开发在nginx php7.1.4 mysql5.7 laravel5.4
 
 启动命令入口：php artisan startSys:run
 
 项目启动中可以输入命令：
 ````
 login：命令进行登录
 exit：退出
 delcookie：清除cookie
 main:进入业务入口，除开手动校验的存在，其他的都会自动进入到main方法体里
 
 ````
 