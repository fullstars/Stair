<?php
	header('Content-type:text/html; charset=utf-8');
	// 开启Session
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="renderer" content="webkit"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="https://cdn.staticfile.org/mdui/0.4.1/css/mdui.min.css">
  <link rel="stylesheet" href="./static/css/style.css">
  <title></title>
</head>
<body class="mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-accent-pink mdui-loaded">
    <!-- 头部 -->
    <header class="mdui-appbar mdui-appbar-fixed mdui-color-theme" id="content-header">
        <div class="mdui-toolbar mdui-color-theme">
            <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-drawer="{target: '#sidebar', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
            <div class="mdui-toolbar-spacer"></div>
            <a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#dialog-docs-theme'}"><i class="mdui-icon material-icons">color_lens</i></a>
            <a class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#dialog-docs-upload'}"><i class="mdui-icon material-icons">file_upload</i></a>
            <a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></a>
        </div>
    </header>

    <!-- 侧边栏 -->
    <div id="sidebar" class="mdui-drawer" >
        <div class="mdui-list">
            <a href="/" class="mdui-list-item">
              <i class="mdui-list-item-icon mdui-icon material-icons">folder</i>
              <div class="mdui-list-item-content">我的文件</div>
            </a>
            <a href="/" class="mdui-list-item">
              <i class="mdui-list-item-icon mdui-icon material-icons">create_new_folder</i>
              <div class="mdui-list-item-content">新建文件夹</div>
            </a>
            <a href="/" class="mdui-list-item">
              <i class="mdui-list-item-icon mdui-icon material-icons">fiber_new</i>
            <div class="mdui-list-item-content">新建文件</div>
            </a>
            <p class="mdui-subheader">管理</p>
            <a href="/" class="mdui-list-item">
              <i class="mdui-list-item-icon mdui-icon material-icons">settings_applications</i>
              <div class="mdui-list-item-content" onclick="mdui.snackbar('开发中...' );">设置</div>
            </a>
            <a href="./logout.php" class="mdui-list-item">
              <i class="mdui-list-item-icon mdui-icon material-icons">power_settings_new</i>
              <div class="mdui-list-item-content">注销</div>
            </a>  
          </div>
        </div>
    </div>

    <!-- 登陆界面 -->
    <div class="mc-login mdui-dialog" id="dialog">  
          <div class="mdui-dialog-title mdui-color-indigo">登录</div>  
          <form action="login.php" method="post">
            <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-has-bottom mdui-textfield-invalid-html5">
              <label class="mdui-textfield-label">用户名或邮箱</label>
              <input class="mdui-textfield-input" name="username" type="text">
              <div class="mdui-textfield-error">账号不能为空</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-has-bottom">
              <label class="mdui-textfield-label">密码</label>
              <input class="mdui-textfield-input" name="password" type="password"> 
              <div class="mdui-textfield-error">密码不能为空</div>   
            </div>
                <label class="mdui-checkbox">
                <input type="checkbox"name="remember" value="yes" checked/>
                <i class="mdui-checkbox-icon"></i>记住登陆7天
                </label>
            <input type="submit" class="mdui-btn mdui-btn-raised mdui-color-theme-accent mdui-float-right" name="login" value="登录"/>
          </form>
    </div>

    <!-- 上传 -->
    <div class="mdui-dialog" id="dialog-docs-upload">
      <div class="mdui-dialog-title">上传文件</div>
      <div class="mdui-dialog-content">
        <script type="text/javascript">
            const BYTES_PER_CHUNK = 1024 * 1024; // 每个文件切片大小定为1MB .
            var slices;
            var totalSlices;

            //发送请求
            function sendRequest() {

                var blob = document.getElementById('file').files[0];

                var start = 0;
                var end;
                var index = 0;

                // 计算文件切片总数
                slices = Math.ceil(blob.size / BYTES_PER_CHUNK);
                totalSlices= slices;

                while(start < blob.size) {
                    end = start + BYTES_PER_CHUNK;
                    if(end > blob.size) {
                        end = blob.size;
                    }

                    uploadFile(blob, index, start, end);

                    start = end;
                    index++;
                }
            }

            //上传文件
            function uploadFile(blob, index, start, end) {
                var xhr;
                var fd;
                var chunk;

                xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if(xhr.readyState == 4) {
                        if(xhr.responseText) {
                            alert(xhr.responseText);
                        }

                        slices--;

                        // 如果所有文件切片都成功发送，发送文件合并请求。
                        if(slices == 0) {
                            mergeFile(blob);
                            alert('文件上传完毕');
                        }
                    }
                };

                chunk =blob.slice(start,end);//切割文件

                //构造form数据
                fd = new FormData();
                fd.append("file", chunk);
                fd.append("name", blob.name);
                fd.append("index", index);

                xhr.open("POST", "upload.php", true);

                //设置二进制文边界件头
                xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
                xhr.send(fd);
            }

            function mergeFile(blob) {
                var xhr;
                var fd;

                xhr = new XMLHttpRequest();

                fd = new FormData();
                fd.append("name", blob.name);
                fd.append("index", totalSlices);

                xhr.open("POST", "merge.php", true);
                xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
                xhr.send(fd);
            }

        </script>
        <input type="file" id="file" class="mdui-btn mdui-ripple mdui-float-left"/>
        <button  onclick="sendRequest()" class="mdui-btn mdui-btn-raised mdui-color-theme-accent mdui-float-right">上传</button>
      </div> 
    </div>

    <!-- 主题 -->
    <div class="mdui-dialog" id="dialog-docs-theme">
        <div class="mdui-dialog-title">设置文档主题</div>
        <div class="mdui-dialog-content">
    
          <p class="mdui-typo-title">主题色</p>
          <div class="mdui-row-xs-1 mdui-row-sm-2 mdui-row-md-3">
            <div class="mdui-col">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-layout" value="" checked/>
                <i class="mdui-radio-icon"></i>
                Light
              </label>
            </div>
            <div class="mdui-col">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-layout" value="dark" />
                <i class="mdui-radio-icon"></i>
                Dark
              </label>
            </div>
          </div>
    
          <p class="mdui-typo-title mdui-text-color-theme">主色</p>
            <form class="mdui-row-xs-1 mdui-row-sm-2 mdui-row-md-3">
              <div class="mdui-col mdui-text-color-amber">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="amber" />
                  <i class="mdui-radio-icon"></i>
                  Amber
                </label>
              </div>
              <div class="mdui-col mdui-text-color-blue">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="blue" />
                  <i class="mdui-radio-icon"></i>
                  Blue
                </label>
              </div>
              <div class="mdui-col mdui-text-color-blue-grey">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="blue-grey" />
                  <i class="mdui-radio-icon"></i>
                  Blue Grey
                </label>
              </div>
              <div class="mdui-col mdui-text-color-brown">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="brown" />
                  <i class="mdui-radio-icon"></i>
                  Brown
                </label>
              </div>
              <div class="mdui-col mdui-text-color-cyan">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="cyan" />
                  <i class="mdui-radio-icon"></i>
                  Cyan
                </label>
              </div>
              <div class="mdui-col mdui-text-color-deep-orange">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="deep-orange" />
                  <i class="mdui-radio-icon"></i>
                  Deep Orange
                </label>
              </div>
              <div class="mdui-col mdui-text-color-deep-purple">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="deep-purple" />
                  <i class="mdui-radio-icon"></i>
                  Deep Purple
                </label>
              </div>
              <div class="mdui-col mdui-text-color-green">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="green" />
                  <i class="mdui-radio-icon"></i>
                  Green
                </label>
              </div>
              <div class="mdui-col mdui-text-color-grey">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="grey" />
                  <i class="mdui-radio-icon"></i>
                  Grey
                </label>
              </div>
              <div class="mdui-col mdui-text-color-indigo">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="indigo" checked/>
                  <i class="mdui-radio-icon"></i>
                  Indigo
                </label>
              </div>
              <div class="mdui-col mdui-text-color-light-blue">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="light-blue" />
                  <i class="mdui-radio-icon"></i>
                  Light Blue
                </label>
              </div>
              <div class="mdui-col mdui-text-color-light-green">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="light-green" />
                  <i class="mdui-radio-icon"></i>
                  Light Green
                </label>
              </div>
              <div class="mdui-col mdui-text-color-lime">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="lime" />
                  <i class="mdui-radio-icon"></i>
                  Lime
                </label>
              </div>
              <div class="mdui-col mdui-text-color-orange">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="orange" />
                  <i class="mdui-radio-icon"></i>
                  Orange
                </label>
              </div>
              <div class="mdui-col mdui-text-color-pink">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="pink" />
                  <i class="mdui-radio-icon"></i>
                  Pink
                </label>
              </div>
              <div class="mdui-col mdui-text-color-purple">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="purple" />
                  <i class="mdui-radio-icon"></i>
                  Purple
                </label>
              </div>
              <div class="mdui-col mdui-text-color-red">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="red" />
                  <i class="mdui-radio-icon"></i>
                  Red
                </label>
              </div>
              <div class="mdui-col mdui-text-color-teal">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="teal" />
                  <i class="mdui-radio-icon"></i>
                  Teal
                </label>
              </div>
              <div class="mdui-col mdui-text-color-yellow">
                <label class="mdui-radio mdui-m-b-1">
                  <input type="radio" name="doc-theme-primary" value="yellow" />
                  <i class="mdui-radio-icon"></i>
                  Yellow
                </label>
              </div>
            </form>
    
          <p class="mdui-typo-title mdui-text-color-theme-accent">强调色</p>
          <form class="mdui-row-xs-1 mdui-row-sm-2 mdui-row-md-3">
            <div class="mdui-col mdui-text-color-amber">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="amber" />
                <i class="mdui-radio-icon"></i>
                Amber
              </label>
            </div>
            <div class="mdui-col mdui-text-color-blue">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="blue" />
                <i class="mdui-radio-icon"></i>
                Blue
              </label>
            </div>
            <div class="mdui-col mdui-text-color-cyan">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="cyan" />
                <i class="mdui-radio-icon"></i>
                Cyan
              </label>
            </div>
            <div class="mdui-col mdui-text-color-deep-orange">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="deep-orange" />
                <i class="mdui-radio-icon"></i>
                Deep Orange
              </label>
            </div>
            <div class="mdui-col mdui-text-color-deep-purple">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="deep-purple" />
                <i class="mdui-radio-icon"></i>
                Deep Purple
              </label>
            </div>
            <div class="mdui-col mdui-text-color-green">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="green" />
                <i class="mdui-radio-icon"></i>
                Green
              </label>
            </div>
            <div class="mdui-col mdui-text-color-indigo">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="indigo" />
                <i class="mdui-radio-icon"></i>
                Indigo
              </label>
            </div>
            <div class="mdui-col mdui-text-color-light-blue">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="light-blue" />
                <i class="mdui-radio-icon"></i>
                Light Blue
              </label>
            </div>
            <div class="mdui-col mdui-text-color-light-green">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="light-green" />
                <i class="mdui-radio-icon"></i>
                Light Green
              </label>
            </div>
            <div class="mdui-col mdui-text-color-lime">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="lime" />
                <i class="mdui-radio-icon"></i>
                Lime
              </label>
            </div>
            <div class="mdui-col mdui-text-color-orange">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="orange" />
                <i class="mdui-radio-icon"></i>
                Orange
              </label>
            </div>
            <div class="mdui-col mdui-text-color-pink">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="pink" checked/>
                <i class="mdui-radio-icon"></i>
                Pink
              </label>
            </div>
            <div class="mdui-col mdui-text-color-purple">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="purple" />
                <i class="mdui-radio-icon"></i>
                Purple
              </label>
            </div>
            <div class="mdui-col mdui-text-color-red">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="red" />
                <i class="mdui-radio-icon"></i>
                Red
              </label>
            </div>
            <div class="mdui-col mdui-text-color-teal">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="teal" />
                <i class="mdui-radio-icon"></i>
                Teal
              </label>
            </div>
            <div class="mdui-col mdui-text-color-yellow">
              <label class="mdui-radio mdui-m-b-1">
                <input type="radio" name="doc-theme-accent" value="yellow" />
                <i class="mdui-radio-icon"></i>
                Yellow
              </label>
            </div>
          </form>
        </div>
    
        <div class="mdui-divider"></div>
        <div class="mdui-dialog-actions">
          <button class="mdui-btn mdui-ripple mdui-float-left" mdui-dialog-cancel>恢复默认主题</button>
          <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>ok</button>
        </div>
    </div>

    