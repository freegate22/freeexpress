/*!
 * Ublue jQuery Tabs
 * Version 1.2
 * Copyright (c) 2011, 梦幻神化 
 * http://www.bluesdream.com
 *
 * Date: 2011.10.25
 * Update:2012.09.04
 * Update:2013.07.24 代码重构
 * 
 * 请保留此信息，如果您有修改或意见可通过网站给我留言，也可以通过邮件形式联系本人。
 * Mail: hello@bluesdream.com
 */

$.fn.UblueTabs=function(opts){
	var $this = $(this);
	var opts = $.extend({
		tabsTit:".tabsTit",
		tabsTab:".tabsTab",
		tabsCon:".tabsCon",
		tabsList:".tabsList",
		tabsHover:"tabsHover",
		eventType:"hover"
	}, opts);

	$this.find(opts.tabsList).eq(0).siblings().hide();
	var $eventType;
	opts.eventType=="hover"?$eventType = "mouseenter":$eventType = "click";

	$(this).find(opts.tabsTab).bind($eventType,function(){
		var i =$(this).index();
		$(this).addClass(opts.tabsHover).siblings().removeClass(opts.tabsHover);
		$(this).parents(opts.tabsTit).siblings(opts.tabsCon).find(opts.tabsList).eq(i).show().siblings().hide();
	});

};