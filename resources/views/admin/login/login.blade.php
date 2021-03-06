<!doctype html> 
<html lang="cn">
@include('admin.layout.head')
<body data-type="login">
    <div class="am-g tpl-g">
        <div class="tpl-login">
            <div class="tpl-login-content">
                <div class="tpl-login-logo"></div>
                <form action="{{url('admin/dologin')}}" class="am-form tpl-form-line-form" method="post">
                {{csrf_field()}}
                    <div class="am-form-group">
                        <input type="text" class="tpl-form-input" required id="user-name" minlength="6" maxlength="18" placeholder="请输入账号" name="username" value="{{old('username')}}">
                    </div>
                    <div class="am-form-group">
                        <input type="password" class="tpl-form-input" required id="user-name" placeholder="请输入密码" name="password" value="123456">
                    </div>
                    <div class="am-form-group">
                        <input type="text" style='width:150px' class="tpl-form-input" required id="user-name" placeholder="请输入验证码" name="code">
                        <div>
                            <img src="{{ url('code') }}/{{rand(10000, 99999)}}.jpg" alt="验证码" style='margin-left: 200px;margin-top:-60px;'>
                        </div>
                        
                    </div>
                    <div class="am-form-group tpl-login-remember-me">
                        <input id="remember-me" type="checkbox" name="code">
                        <label for="remember-me">
                            记住密码
                        </label>
                    </div>
                    <div class="am-form-group">
                        <button type="submit" class="am-btn am-btn-primary  am-btn-block tpl-btn-bg-color-success  tpl-login-btn">提交</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@include('admin.layout.theme')
@include('admin.layout.script')
    <script>
        $('img[alt]').click(function () {
            this.src = '{{url('code')}}/' + Math.random().toString().replace('.', '') + '.jpg';
        });
    </script>
    @if(session('error'))
        <script>
            layer.alert('{{session('error')}}');
        </script>
    @endif
    @if (count($errors) > 0)
        <script>
            layer.alert('@foreach ($errors->all() as $error){{ $error }} @endforeach') ;
        </script>
    @endif
</body>
</html>