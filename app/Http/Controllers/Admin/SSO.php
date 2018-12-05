<?php
/**
 * Created by Payne.
 * User: Payne
 * Date: 2015/3/29 0029
 * Time: 23:57
 * Improved: Ckend
 */
namespace App\Http\Controllers\Admin;

class SSO {

    private $token, $config, $code;
    private $static_config = array(
        'SERVER_ADDR' => '',
        'AUTHORIZATION_CODE' => 'authorization_code'
    );

    private $kinds = array(
        1 => '本科生',
        2 => '研究生',
        3 => '在编教职工',
        4 => '专科生',
        5 => '成教生',
        6 => '留学生',
        7 => '离退休人员',
        8 => '非编教职工',
        9 => '职工家属',
        79 => '校友',
        90 => '其他用户',
        91 => '外单位用户',
    );

    /**
     * @param $client_id
     * @param $client_secret
     */
    public function __construct($SERVER_ADDR, $client_id, $client_secret)
    {
        $this->static_config['SERVER_ADDR'] = $SERVER_ADDR;
        $this->config = array(
            'CLIENT_ID' => $client_id,
            'CLIENT_SECRET' => $client_secret,
        );
    }

    /**
     * 获取授权登陆页面的地址
     * @param $redirect_url
     * @return string
     */
    public function getAuthorizeURL($redirect_url){
        return $this->static_config['SERVER_ADDR'].'/AccountService/openapi/auth?client_id='.urlencode($this->config['CLIENT_ID']).'&response_type=code&redirect_url='.urlencode($redirect_url);
    }


    /**
     * 获取AccessTOKEN
     * @param $code
     * @return array
     */
    public function getAccessToken($code){
        $this->code = $code;
        $data = array (
            'client_id='.urlencode($this->config['CLIENT_ID']),
            'client_secret='.urlencode($this->config['CLIENT_SECRET']),
            'code='.urlencode($code),
            'grant_type='.urlencode($this->static_config['AUTHORIZATION_CODE']));

        $url = $this->static_config['SERVER_ADDR'] . '/AccountService/openapi/token';

        $thing = $this->curl_post($url, $data);

        $arr = json_decode($thing, true);
        if($arr && count($arr)>0){
            if(strlen($arr['access_token'])>1){
                $this->token = $arr['access_token'];
                return array('result'=>true, 'access_token' => $arr['access_token']);
            }else{
                return array('result'=>false, 'msg'=>$arr['message']);
            }
        }else {
            return array('result'=>false, 'msg'=>'未知错误');
        }
    }

    /**
     * 获取用户信息
     * @param $access_token string
     * @return mixed
     */
    public function getUserInfo($access_token = '')
    {
        $url = $this->static_config['SERVER_ADDR'] . '/AccountService/openapi/userinfo';
        $data = array ('access_token='.urlencode(empty($access_token)?$access_token:$this->token));
        $thing = $this->curl_post($url, $data);

        $arr = json_decode($thing, true);
        return $arr;
    }

    /**
     * 获取学生类型名称
     * @param $kind
     * @return string
     */
    public function getKindName($kind){
        if(!empty($this->kinds[$kind])) return $this->kinds[$kind];

        return '未知用户身份';
    }

    private function curl_post($url, $form){
        $form = implode('&', $form);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
        ob_start();
        curl_exec($ch);
        $result = ob_get_contents() ;
        ob_end_clean();
        return $result;
    }

    // 发送短消息
    public function sendMessage($title, $content, $to_user, $from_user)
    {
        $url = $this->static_config['SERVER_ADDR'].'/AccountService/message/appmsg.html';

        $data = array(
            'title=' . urlencode($title),
            'content=' . urlencode($content),
            'touser=' . urlencode($to_user),
            'fromuser=' . urlencode($from_user),
            'client_id=' . urlencode($this->config['CLIENT_ID']),
            'timestamp=' . urlencode(round(microtime(true) * 1000)),
            'sign=' . urlencode(md5($this->config['CLIENT_ID'] . $content . $from_user . round(microtime(true) * 1000) . $title . $to_user . $this->config['CLIENT_SECRET']))
        );
        $thing = $this->curl_post($url, $data);
        return $thing;
    }
}