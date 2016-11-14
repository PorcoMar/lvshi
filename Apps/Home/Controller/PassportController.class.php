<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html; charset=utf-8");

class PassportController extends BaseController
{
    /*
     * 用书登入页
     * */
    public function index()
    {
        $this->display('login');
    }

    /*
     * 找回密码页
     * */
    public function reset()
    {
        $this->display('reset');
    }

    /*
     * 用户注册页
     * */
    public function enroll()
    {
        if (I('code')) {
            $this->assign("code", I('code'));
        }
        $this->display('register');
    }

    /*
     * 登入验证
     * @param  [json]  enter   [登入接口]
     * */
    public function login()
    {
        if (IS_AJAX) {
            if (!$this->check_verify(I('vcode'))) {
                $data['status'] = 2;
                $data['msg'] = '验证码错误！';
                $this->ajaxReturn($data);
                exit;
            }
            $data['mobile'] = I('mobile');
            $data['code'] = I('code');
            $enter = get_curl_info(LOGIN, $data);
            if ($enter && $enter['code'] == 1000) {
                session('enter', $enter);
                session('userInfo', $enter['result']);
                $data['status'] = 1;
                $this->ajaxReturn($data);
                exit;
            } else {
                $data['status'] = 0;
                $data['msg'] = '用户名或手机验证码错误!';
                $this->ajaxReturn($data);
                exit;
            }
        }
    }

    public function loginP()
    {
        if (IS_AJAX) {
            $data['mobile'] = I('mobile');
            $data['password'] = md5(I('pass'));
            $enter = get_curl_info(LOGIN, $data);
            if ($enter && $enter['code'] == 1000) {
                session('enter', $enter);
                session('userInfo', $enter['result']);
                $data['status'] = 1;
                $this->ajaxReturn($data);
                exit;
            } else {
                $data['status'] = 0;
                $data['msg'] = '用户名或密码错误!';
                $this->ajaxReturn($data);
                exit;
            }
        }
    }

    /*
     * 手机验证码
     * @param   [json]  verify  [获取验证码接口]
     * */
    public function code()
    {
        $mobile['mobile'] = I('post.mobile');
        $verify = get_curl_info(SENDSMS, $mobile);
        if ($verify['code'] == '1000') {
            $data['status'] = 1;
            $data['msg'] = '短信已发送！';
            $this->ajaxReturn($data);
            exit;
        } else {
            $data['status'] = 0;
            $data['msg'] = '请不要重复发送验证码!';
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
    * 注册
    * @param    [json]    enroll    [注册接口]
    */
    public function register()
    {
        if (IS_AJAX) {
            if (!$this->check_verify(I('vcode'))) {
                $data['status'] = 2;
                $data['msg'] = '验证码错误！';
                $this->ajaxReturn($data);
                exit;
            }
            $register = array('mobile' => I('mobile'), 'password' => md5(I('password')), 'code' => I('code'));
            $enroll = get_curl_info(REGISTER, $register);
            if ($enroll['code'] == '1000') {
                $data['status'] = 1;
				$data['msg'] = '恭喜您,注册成功!';
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 0;
                $data['msg'] = '注册失败!';
                $this->ajaxReturn($data);
            }
        }
    }

    /*
   * 密码找回
   * @param [json]    forget    [密码找回接口]
   */
    public function resetPassword()
    {
        $data = array('mobile' => I('mobile'), 'password' => md5(I('password')), 'code' => I('code'));
        $forget = get_curl_info(FORGET, $data);
        if ($forget['code'] == '1000') {
            $data['status'] = 1;
            $this->ajaxReturn($data);
            exit;
        } else {
            $data['status'] = 2;
            $data['msg'] = '验证码不正确！';
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
   * 退出登入
   * */
    public function quit()
    {
        $token = $_SESSION['enter']['result']['token'];
        $data = array('token' => $token);
        $logout = get_curl_info(LOGOUT, $data);
        if ($logout['code'] == '1000') {
            $_SESSION['enter'] = array();
            $data['status'] = 1;
            $this->redirect('Index/index','登出成功');
        }
    }

    //验证码检测
    function check_verify($data, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($data, $id);
    }

    //验证码
    public function image()
    {
        $config = array(
            'fontSize' => 16,    // 验证码字体大小
            'length' => 4,     // 验证码位数
            'codeSet' => '0987654321',//验证码字符
            //'imageW'      =>    115,//验证码宽度
            //'imageH'      =>    40,//验证码高度
            'useNoise' => false, // 关闭验证码杂点
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    //退出登录
    function logout()
    {

        $weibo = new \Org\Util\WeiboConnect;
        $back = $weibo->tuichu();
        //print_r($back);die;
        session(null);
        $this->redirect('Index/index');
    }
}





