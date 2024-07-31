<div tabindex="0" id="tabindex0" style="display:none;opacity: 0.7; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; overflow: hidden; -webkit-user-select: none; z-index: 1024; background: rgb(0, 0, 0);" class="ui-popup-backdrop"></div>
<div tabindex="-1" id="alertID" style="display:none;position: fixed; outline: 0px;left:50%; top: 311px; z-index: 1025;" aria-labelledby="title:1509007581604" aria-describedby="content:1509007581604" class="ui-popup ui-popup-modal ui-popup-show ui-popup-focus" role="alertdialog">
	<div i="dialog" class="ui-dialog">
		<div class="ui-dialog-arrow-a"></div>
		<div class="ui-dialog-arrow-b"></div>
		<table class="ui-dialog-grid">
			<tbody>
				<tr>
					<td i="header" class="ui-dialog-header" style="display: none;">
						<button i="close" class="ui-dialog-close" title="cancel">×</button>
						<div i="title" class="ui-dialog-title" id="title:1509007581604"></div>
					</td>
				</tr>
				<tr>
					<td i="body" class="ui-dialog-body">
						<div i="content" class="ui-dialog-content" id="content:1509007581604">
							<div class="dialog-msg" style="display: block;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td i="footer" class="ui-dialog-footer">
						<div i="statusbar" class="ui-dialog-statusbar" style="display: none;"></div>
						<div i="button" class="ui-dialog-button">
							<button type="button" id="closeid" ></button>
							<button type="button" id="okid" ></button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script>
var width=$("#alertID").width()/2;
$("#alertID").css("margin-left",'-'+width+'px');

</script>
<style>
#icon-qq:hover #qq{display:block;}
#icon-wechat:hover #wechat{display:block;}
</style>
<div class="footer mt-30" style="display: block;">
	<div class="contact-us">
		<a href="#" class="icon-cu icon-qq" id="icon-qq"><img src="/image/qq.jpg" id="qq" class="wechat-qrcode qq-group-qrcode" alt=""></a>
		<a href="#" class="icon-cu icon-wechat" id="icon-wechat"><img src="/image/wechat.jpg" id="wechat" class="wechat-qrcode" alt=""></a>
	</div>
	
	<span class="fr other-link">
		<div class="language-select">
			<div class="lang-current"><span class="lang"><?php echo $language;?></span> <span class="arrow-right"></span></div>
			<div class="more-language">
				<p class="language-item" id="cn" >简体中文</p>
				<p class="language-item" id="hk" >繁體中文</p>
				<p class="language-item" id="en" >English</p>
			</div>
		</div>
	</span>
</div>
</div>
</div>

<script src="/js/language.js"></script>
<?php mysql_close($link); ?>