<?php
return array(
	'TPL_ENGINE'                  	=>	'php' ,             //模版引擎，支持Smarty和PHP
	'TPL_LEFT_DELIMITER'          	=>	'<?php' ,           //smarty左界符
	'TPL_RIGHT_DELIMITER'         	=>	'?>' ,              //smarty右界符
	'TPL_SUFFIX'                  	=>	'php' ,             //模板文件后缀名
	'TPL_PATH'                    	=>	'application/views/' ,//模板目录
	'STATIC_PATH'                 	=>	'resource/' ,       //静态资源的目录
	'FILE_ENCODING'               	=>	'utf-8' ,           //项目编码
	'MOD_DIRS_CHECK'              	=>	true ,              //目录结构检测
	'MOD_FILE_CHECK'              	=>	true ,              //文件命名和位置检测
	'MOD_HTML_REGULAR_CHECK'      	=>	true ,              //HTML规范检测
	'MOD_CSS_REGULAR_CHECK'       	=>	true ,              //CSS规范检测
	'MOD_EMPTY_CHECK'             	=>	true ,              //空文件检测
	'MOD_CSS_SPRITES'             	=>	false ,             //是否进行css sprites
	'MOD_CSS_AUTOCOMPLETE'        	=>	false ,             //css样式自动补全
	'MOD_JS_COMBINE'              	=>	true ,              //JS文件是否启用合并
	'MOD_CSS_COMBINE'             	=>	true ,              //CSS文件是否启用合并
	'MOD_HTML_COMPRESS'           	=>	false ,             //HTML文件是否启用压缩
	'MOD_JS_COMPRESS'             	=>	true ,              //JS文件是否启用压缩
	'MOD_CSS_COMPRESS'            	=>	true ,              //CSS文件是否启用压缩
	'MOD_OPTI_IMG'                	=>	false ,             //是否优化图片
	'MOD_XSS_AUTO_FIXED'          	=>	false ,             //是否进行XSS自动修复
	'MOD_IMG_DATAURI'             	=>	false ,             //将CSS中的图片地址转换为dataURI
	'MOD_STATIC_TO_CDN'           	=>	true ,              //是否将静态资源上线到CDN
	'MOD_JS_TPL_REPLACE'          	=>	false ,             //是否进行前端模版替换
	'MOD_STRING_REPLACE'          	=>	true ,              //是否进行代码替换功能
	'MOD_STATIC_VERSION'          	=>	1 ,                 //静态文件版本号，1或者true为query,2为新文件模式
	'DEBUG'                       	=>	true ,              
	'CACHE'                       	=>	true ,              
	'PLUGINS_PATH'                	=>	'smarty_plugins' ,  
	'MOD_TPL_SYNTAX_CHECK'        	=>	false ,             
	'MOD_USELESS_FILE_CHECK'      	=>	false ,             
	'CSS_SPRITES_REGULAR'         	=>	array(              
	),
	'MOD_CSS3_AUTOCOMPLETE'       	=>	false ,             
	'XSS_ESCAPE'                  	=>	array(              
		'url'               	=>	'', 
		'html'              	=>	'', 
		'js'                	=>	'', 
		'callback'          	=>	'', 
		'data'              	=>	'', 
		'event'             	=>	'', 
		'noescape'          	=>	'', 
		'xml'               	=>	''
	),
	'XSS_SAFE_VAR'                	=>	array(              
		'/^(?:Domain)/ies'
	),
	'IMG_DATAURI_MAX_SIZE'        	=>	3000 ,              
	'STRING_REPLACE_PATTERN'      	=>	array(              
	),
	'MOD_JS_TPL'                  	=>	false ,             
);