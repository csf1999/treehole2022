<?php
namespace app\Controller;

//use Think\Controller;
class User {

    public function sign(){
        if(!$_POST['username']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:username';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['phone']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:phone';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['password']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['password_again']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password_again';
            $this->ajaxReturn($return_data);
        }

        if($_POST['password_again'] != $_POST['password']){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '两次密码不一致';
            $this->ajaxReturn($return_data);
        }
        
        $User = M('User');
        $where = array();
        $where['phone'] = $_POST['phone'];        
        $user = $User->where($where)->find();
        if($user){
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '该手机号已经被注册';
            $this->ajaxReturn($return_data);
        }
        else{
            $data = array();
            $data['username'] = $_POST['username'];
            $data['phone'] = $_POST['phone'];
            $data['password'] = md5($_POST['password']);
            $data['face_url'] = $_POST['face_url'];

            $result = $User->add($data);

            if($result){
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '注册成功';
                $return_data['data']['user_id'] = $result;
                $return_data['data']['username'] = $_POST['username'];
                $return_data['data']['phone'] = $_POST['phone'];
                $return_data['data']['password'] = $_POST['password'];
                $return_data['data']['face_url'] = $_POST['face_url'];
                $this->ajaxReturn($return_data);
            }
            else{
                $return_data = array();
                $return_data['error_code'] = 4;
                $return_data['msg'] = '注册失败';
                $this->ajaxReturn($return_data);
            }
        }
    }

    public function login(){
        if(!$_POST['phone']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:phone';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['password']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:password';
            $this->ajaxReturn($return_data);
        }

        $User = M('User');
        $where = array();
        $where['phone'] = $_POST['phone'];
        $user = $User->where($where)->find();

        if($user){
            if(md5($_POST['password']) != $user['password']){
                $return_data = array();
                $return_data['error_code'] = 3;
                $return_data['msg'] = '密码不正确，请重新输入';
                $this->ajaxReturn($return_data);
            }
            else{
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '登录成功';
                $return_data['data']['user_id'] = $user['id'];
                $return_data['data']['username'] = $user['username'];
                $return_data['data']['phone'] = $user['phone'];
                $return_data['data']['face_url'] = $user['face_url'];
                $this->ajaxReturn($return_data);

            }
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '不存在该手机号用户，请注册';
            $this->ajaxReturn($return_data);
        }

        
        dump($_POST);
    }
}

