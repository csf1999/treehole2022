<?php
namespace app\Controller;

use think\facade\Db;
use think\facade\Request;

class User {

    public function test(){
        var_dump(789);
    }
    public function sign(){
        if(!Request::post('username')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:username';
            return json($return_data);
        }
        if(!Request::post('phone')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:phone';
            return json($return_data);
        }
        if(!Request::post('password')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password';
            return json($return_data);
        }
        if(!Request::post('password_again')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password_again';
            return json($return_data);
        }

        if(Request::post('password_again') != Request::post('password')){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '两次密码不一致';
            return json($return_data);
        }
        
        $where = array();
        $where['phone'] = Request::post('phone');        
        $user = Db::name('User')->where($where)->find();
        if($user){
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '该手机号已经被注册';
            return json($return_data);
        }
        else{
            $data = array();
            $data['username'] = Request::post('username');
            $data['phone'] = Request::post('phone');
            $data['password'] = md5(Request::post('password'));
            $data['face_url'] = Request::post('face_url');

            $result = Db::name('User')->insertGetId($data);

            if($result){
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '注册成功';
                $return_data['data']['user_id'] = $result;
                $return_data['data']['username'] = Request::post('username');
                $return_data['data']['phone'] = Request::post('phone');
                $return_data['data']['password'] = Request::post('password');
                $return_data['data']['face_url'] = Request::post('face_url');
                return json($return_data);
            }
            else{
                $return_data = array();
                $return_data['error_code'] = 4;
                $return_data['msg'] = '注册失败';
                return json($return_data);
            }
        }
    }

    public function login(){
        if(!Request::post('phone')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:phone';
            return json($return_data);
        }
        if(!Request::post('password')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password';
            return json($return_data);
        }

        $where = array();
        $where['phone'] = Request::post('phone');
        $user = Db::name('User')->where($where)->find();

        if($user){
            if(md5(Request::post('password')) != $user['password']){
                $return_data = array();
                $return_data['error_code'] = 3;
                $return_data['msg'] = '密码不正确，请重新输入';
                return json($return_data);
            }
            else{
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '登录成功';
                $return_data['data']['user_id'] = $user['id'];
                $return_data['data']['username'] = $user['username'];
                $return_data['data']['phone'] = $user['phone'];
                $return_data['data']['face_url'] = $user['face_url'];
                return json($return_data);
            }
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '不存在该手机号用户，请注册';
            return json($return_data);
        }
    }
}

