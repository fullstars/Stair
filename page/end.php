    <!-- 内容
    <div  id="content-main">
    <P>当前位置：首页</p>
    </div>
	

    尾部
    <div class="mdui-color-theme" id="content-footer">Copyright</div>
	-->

</body>
<script src="https://cdn.staticfile.org/mdui/0.4.1/js/mdui.min.js"></script>
<script>var $$ = mdui.JQ;</script>
<script src="./static/js/theme.js"></script>
</html>

<?php
	// 首先判断Cookie是否有记住了用户信息
	if (isset($_COOKIE['username'])) {
	// 若记住了用户信息,则直接传给Session
		$_SESSION['username'] = $_COOKIE['username'];
		$_SESSION['islogin'] = 1;
	}else {
	// 若没有登录
	echo"<script src='./static/js/dialog.js'></script>";
	}
?>