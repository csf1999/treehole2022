<?php
namespace app\controller;

//use Think\Controller;
class Message {
    //发布新树洞
    public function publish_new_message(){
        //校验参数是否存在
        if(!$_POST['user_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['username']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:username';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['face_url']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:face_url';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['content']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:content';
            $this->ajaxReturn($return_data);
        }

        $Message = M('Message');
        //设置要插入的数据
        $data = array();
        $data['user_id'] = $_POST['user_id'];
        $data['username'] = $_POST['username'];
        $data['face_url'] = $_POST['face_url'];
        $data['content'] = $_POST['content'];
        $data['total_likes'] = 0;
        $data['send_timestamp'] = time();

        $result = $Message->add($data);

        if($result){
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据添加成功';
            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '数据添加失败';
            $this->ajaxReturn($return_data);
        }

        dump($_POST);
    }

    //得到所有人的树洞
    public function get_all_messages(){
        $Message = M('Message');
        $all_messages = $Message->order('id desc')->select();
        //dump($all_messages);
        foreach($all_messages as $key => $message){
            $all_messages[$key]['send_timestamp'] = date('y-m-d h:i:s',$message['send_timestamp']);
        }
        $return_data = array();
        $return_data['error_code'] = 0;
        $return_data['msg'] = '数据获取成功';
        $return_data['data'] = $all_messages;
        $this->ajaxReturn($return_data);
        //dump($all_messages);
    }

    //得到指定人的树洞
    public function get_one_user_all_messages(){
        
        if(!$_POST['user_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            $this->ajaxReturn($return_data);
        }
        else{
            $Message = M('Message');
            $where = array();
            $where['user_id'] = $_POST['user_id'];
            $all_messages = $Message->where($where)->order('id desc')->select();
            // dump(all_messages);
            foreach($all_messages as $key => $message){
                $all_messages[$key]['send_timestamp'] = date('y-m-d h:i:s',$message['send_timestamp']);
            }
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据获取成功';
            $return_data['data'] = $all_messages;
            $this->ajaxReturn($return_data);
        }
    }

    //点赞
    public function do_like(){
        if(!$_POST['message_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:message_id';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['user_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            $this->ajaxReturn($return_data);
        }
        //dump($_POST);
        $Message = M('Message');
        $where = array();
        $where['id'] = $_POST['message_id'];
        $message = $Message->where($where)->find();

        if(!$message){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';
            $this->ajaxReturn($return_data);
        }
        //dump($message);
        
        $data = array();
        $data['total_likes'] = $message['total_likes'] + 1;
        
        // $where = array();
        // $where['id'] = $_POST['message_id'];
        $result = $Message->where($where)->save($data);

        if($result){
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据保存成功';
            $return_data['data']['message_id'] = $_POST['message_id'];
            $return_data['data']['total_likes'] = $data['total_likes'];
            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '数据保存失败';
            $this->ajaxReturn($return_data);
        }
    }

    //删除指定树洞
    public function delete_message(){
        if(!$_POST['message_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:message_id';
            $this->ajaxReturn($return_data);
        }
        if(!$_POST['user_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            $this->ajaxReturn($return_data);
        }

        $Message = M('Message');
        $where = array();
        $where['id'] = $_POST['message_id'];
        $message = $Message->where($where)->delete();

        if(!$message){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';
            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据删除成功';
            $return_data['data']['message_id'] = $_POST['message_id'];
            $this->ajaxReturn($return_data);
        }
    }
}

