# Author: Ariel < ariel673770@gmail.com >

# 使用方式及参数说明
    1）、Add new `YAML` file: XML\Parameters.yaml
        Parameters:
          # 请求地址
          remoteUrl : https://xxxxxxx.com/
          # 请求路由
          urlRoute : wms****
          # 发货路由
          deliver : ----- (询问作者)
         # 奇门发送配置
          QimenUrlConfig:
            app_key: testerp_appkey
            customerId: c1898
            format: xml
            method: ''
            sign: 45678987654345678
            sign_metho: md5
            timestamp: ''
            v: 2.0
            version: 1
        
    
    2)、请求方式
         ./console
         php console
     
    3)、 参数说明
        发货单     : delivery
        入库单     : inStock
        出库单     : outStock
        退货入库单 : refund
        
        例如： ./console delivery 
        帮助： ./console -h

    4)、树状结构（文件夹形式）
    
    5)、本地需要安装php-zip扩展(ZipArchive)
        安装方法:
            wget http://pecl.php.net/get/zip
            tar -zxvf zip
            cd zip-1.xxx
            phpize
            ./configure
            make & make install
            cp -r modules/zip.so ../php/extension/no-debug****/zip.so
            
            php.ini   -  where php.ini 或者 php --ini
            extension=/php/extension/no-debug***/zip.so
            zlib.output_compression = On
            
            apachectl restart
