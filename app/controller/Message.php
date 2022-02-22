<?php
namespace app\controller;
use think\facade\Db;
use think\facade\Request;

class Message {
    public function test(){
        var_dump(456);
    }

    //发布新树洞
    public function publish_new_message(){
        //校验参数是否存在
        if(!Request::post('user_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            return json($return_data);
        }
        if(!Request::post('username')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:username';
            return json($return_data);
        }
        if(!Request::post('face_url')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:face_url';
            return json($return_data);
        }
        if(!Request::post('content')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:content';
            return json($return_data);
        }

        $Message = Db::name('Message');
        //设置要插入的数据
        $data = array();
        $data['user_id'] = Request::post('user_id');
        $data['username'] = Request::post('username');
        $data['face_url'] = Request::post('face_url');
        $data['content'] = Request::post('content');
        $data['total_likes'] = 0;
        $data['send_timestamp'] = time();

        $result = $Message->insert($data);

        if($result){
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据添加成功';
            return json($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '数据添加失败';
            return json($return_data);
        }
    }

    //得到所有人的树洞
    public function get_all_messages(){
        $Message = Db::name('Message');
        $all_messages = $Message->order('id desc')->select()->toArray();
        //dump($all_messages);
        foreach($all_messages as $key => $message){
            $all_messages[$key]['send_timestamp'] = date('y-m-d h:i:s',$message['send_timestamp']);
        }
        $return_data = array();
        $return_data['error_code'] = 0;
        $return_data['msg'] = '数据获取成功';
        $return_data['data'] = $all_messages;
        return json($return_data);
        //dump($all_messages);
    }

    //得到指定人的树洞
    public function get_one_user_all_messages(){
        
        if(!Request::post('user_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            return json($return_data);
        }
        else{
            $Message = Db::name('Message');
            $where = array();
            $where['user_id'] = Request::post('user_id');
            $all_messages = $Message->where($where)->order('id desc')->select()->toArray();
            // dump(all_messages);
            foreach($all_messages as $key => $message){
                $all_messages[$key]['send_timestamp'] = date('y-m-d h:i:s',$message['send_timestamp']);
            }
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据获取成功';
            $return_data['data'] = $all_messages;
            return json($return_data);
        }
    }

    //点赞
    public function do_like(){
        if(!Request::post('message_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:message_id';
            return json($return_data);
        }
        if(!Request::post('user_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            return json($return_data);
        }
        //dump($_POST);
        $Message = Db::name('Message');
        $where = array();
        $where['id'] = Request::post('message_id');
        $message = $Message->where($where)->find();

        if(!$message){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';
            return json($return_data);
        }
        //dump($message);
        
        $data = array();
        $data['total_likes'] = $message['total_likes'] + 1;
        
        $where = array();
        $where['id'] = Request::post('message_id');
        $result = $Message->where($where)->save($data);

        if($result){
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据保存成功';
            $return_data['data']['message_id'] = Request::post('message_id');
            $return_data['data']['total_likes'] = $data['total_likes'];
            return json($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '数据保存失败';
            return json($return_data);
        }
    }

    //删除指定树洞
    public function delete_message(){
        if(!Request::post('message_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:message_id';
            return json($return_data);
        }
        if(!Request::post('user_id')){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足:user_id';
            return json($return_data);
        }

        $Message = Db::name('Message');
        $where = array();
        $where['id'] = Request::post('message_id');
        $message = $Message->where($where)->delete();

        if(!$message){
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';
            return json($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据删除成功';
            $return_data['data']['message_id'] = Request::post('message_id');
            return json($return_data);
        }
    }
}

