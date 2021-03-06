<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Admin\Member_detail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Model\Admin\Member;
use App\Http\Controllers\Home\HttpController;
use Illuminate\Support\Facades\Crypt;
use DB;
use Mail;

class RegController extends Controller
{
	/**
	  * 前台注册页面
	  */ 
    public function reg()
    {
        return view('home.reg.reg',['title' => '注册']);
    }
    /**
     * 发送手机验证码
     */
    public function phone_code(Request $request)
    {
    	$phone = $request -> input('phone');
    	$res = self::send_phone($phone);
    	echo $res;

    }

    public function send_phone($phone)
    {
    	$phone_code = rand(1,9);
    	session(['phone_code'=>$phone_code]);
    	$url = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account=C29687752&password=8f3d6c8efbd3b5b73819b8f4ac9c8442&format=json&mobile='.$phone.'&content=您的验证码是：'.$phone_code.'。请不要把验证码泄露给其他人。';
    	$res = HttpController::gets($url);
    	return $res;
    }

    /**
     * 处理前台手机注册信息
     */
    public function doreg(Request $request)
    {
        $this->validate($request, [
            'email' => 'regex:/^1[34578][0-9]{9}$/',
            'password'=>'required|between:6,18'
        ],[
            'email.regex' => '请输入正确的手机号',
            'password.required'=>'必须输入密码',
            'password.between'=>'密码长度必须在6-18位之间'
        ]);
    	// 验证手机验证码
    	$phone_code = $request -> only('phone_code');
    	if($phone_code['phone_code'] != session('phone_code')){
    		session(['phone_code'=>null]);
    		return back() -> with('error','验证码不正确..');
    	}else{
    		// 接收手机号,密码
    		$data = $request -> except('_token','phone_code');
	    	$phone = $data['phone'];
            $data['password']= Crypt::encrypt($data['password']);
            $data['username'] = str_random(5);
            $data['status'] = 1;
            $data['ip'] = $request -> ip();

	    	// 检测该手机号注册过没有
	    	$res = Member::where('phone',$phone)->first();

	    	if($res){
	    		session(['phone_code'=>null]);
	    		return back() -> with('error','已经注册过了..');
	    		
	    	}else{
	    		$re = Member::insertGetId($data);
	    		if($re){
                    // 给对应的副表查数据
                    $data1['id'] = $re;
                    $data1['name'] = str_random(5,99999);
                    $data1['auth'] = 0;
                    $res1 = Member_detail::insert($data1);

	    			session(['phone_code'=>null]);
	    			return redirect('/login') -> with('success','注册成功..请登录');
	    			
	    		}else{
					session(['phone_code'=>null]);
	    			return back() -> with('error','注册失败');
	    			
	    		}
	    	}
    	}
    	
    	
    }

    /**
     * 邮箱注册
     */
    public function doemail(Request $request)
    {
    	// 接收提交的数据
    	$data = $request -> except('_token','repassword','refer','vcode');
    	$vcode = $request -> only('vcode');
    	
        $request -> flash();
        // 验证密码
        $this->validate($request, [
            'email' => 'required|email',
            'repassword'=>'required|between:6,18',
            'password'=>'required|between:6,18'
        ],[
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误',
            'repassword.required'=>'必须确认密码',
            'repassword.between'=>'确认密码长度必须在6-18位之间',
            'password.required'=>'必须输入密码',
            'password.between'=>'密码长度必须在6-18位之间'
        ]);

    	$repassword = $request -> only('repassword');
    	$data['password'] = Crypt::encrypt($data['password']);
    	$data['token'] = str_random(50);
    	$data['username'] = str_random(5);
        $data['ip'] = $request -> ip();

    	//检测验证码
    	if($vcode['vcode'] != strtolower(session('code'))){
    		return back() -> with('error','验证码错误..');
    	}
    	// 检测该邮箱注册过没有
    	$res = Member::where('email',$data['email']) -> first();
    	if($res){
    		return back() -> with('error','该邮箱已经注册过..');
    	}

    	if(Crypt::decrypt($data['password']) != $repassword['repassword']){
    		return back() -> with('error','两次密码不一致..');
    	}
        // 开启事务 添加数据
    	DB::beginTransaction();
        $id = DB::table('members') -> insertGetId($data);
        // 给对应的副表查数据
        $data1['id'] = $id;
        $data1['name'] = str_random(5);
        $data1['auth'] = 0;
        $res1 = Member_detail::insert($data1);
        //发送邮件
        self::send_email($data['email'],$id,$data['token']);
        if($id && $res1){
            DB::commit();
            return redirect('login') -> with('success','注册成功,请去邮箱激活账号..');
        } else {
            DB::rollback();
            return back() -> with('error','注册失败');
        }
    }

    public static function send_email($email,$id,$token)
    {
    	Mail::send('home.email.index', ['email'=>$email,'id' => $id,'token'=> $token], function ($m) use ($email){
            $m->to($email)->subject('注册邮件!');
        });
    }
    // 激活邮箱
    public static function jihuo(Request $request)
    {
    	// 修改status字段以区分激活没有
    	$id = $request -> only('id');
    	$token = $request -> only('token');

    	// 通过token检测是否合法路径来的
    	$token['token'] = Member::where('id',$id) -> select('token') -> first();

    	if($token['token'] != $token['token']){
    		return redirect('/reg') -> with('error','激活失败');
    	}else{
    		$res = Member::where('id',$id) -> first();
    		$res -> token = str_random(50);
    		$res -> status = 1;
    		$res -> save();
    		return redirect('login') -> with('success','激活成功');
    	}
    	
    }
}
