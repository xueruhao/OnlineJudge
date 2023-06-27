<?php

// 该文件为系统默认配置，后台设置会将新配置保存在数据库中。
return [
	"siteName"	                => "Sparks of Fire Online Judge",   //网站名称
    "APP_LOCALE"                => "en",    //网站前台默认语言
    "marquee_notice_id"         => "",      //前台滚动公告的编号

	"footer_info"               => "QQ群：529507453", //页脚信息
    "footer_customized_part"    => "",      //自定义页脚内容

	"web_page_display_wide" 	=> true,    //宽屏模式
    "web_page_loading_animation"=> true,    //页面载入动画（全屏覆盖/中部动画）

	"allow_register"	        => true,    //允许访客注册账号
	"login_reg_captcha"			=> true,    //登陆和注册时使用验证码
	"display_complete_userinfo" => true,    //对于未登录访客，个人信息页面 是否显示用户完整信息
	"display_complete_standings"=> true,    //对于未登录访客，排行榜页面 是否显示用户完整信息

	"guest_see_problem"	        => true,    //允许访客浏览题目内容
	"show_disscussions"			=> true,    //在题目页面显示讨论版
	"post_discussion"			=> false,   //允许普通用户在讨论版发表讨论
    "problem_show_tag_collection"=> true,   //题目页面是否向已解决该问题的用户收集题目标签（题目涉及知识点）
    "problem_show_involved_contests"=> true,//从题库进入题目，是否展示涉及到的竞赛

	"rank_show_school"	        => false,   //榜单显示学校
	"rank_show_class"			=> false,   //榜单显示班级
	"rank_show_nick"	        => true,    //榜单显示昵称（姓名）

	"penalty_acm"	            => 1200,    //竞赛acm模式错误一次的罚时，1200秒=20分钟
	"compile_error_submit_interval"	=> 60,  //编译错误后，在此时间内不能再次提交，60秒
	"submit_interval"	        => 10,      //同一用户两次提交最小间隔，10秒
];
