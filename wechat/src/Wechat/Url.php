<?php
/**
 * Url.php
 *
 * Part of MasApi\Wechat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */

namespace MasApi\Wechat;

/**
 * 链接
 */
class Url
{

    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;

    const API_SHORT_URL = 'https://api.weixin.qq.com/cgi-bin/shorturl';

    /**
     * constructor
     *
     * @param string $appId
     * @param string $masAccessToken
     */
    public function __construct($appId, $clientId, $uuid)
    {
        $this->http = new Http(new AccessToken($appId, $clientId, $uuid));
    }

    /**
     * 转短链接
     *
     * @param string $url
     *
     * @return string
     */
    public function short($url)
    {
        $params = array(
                   'action'   => 'long2short',
                   'long_url' => $url,
                  );

        $response = $this->http->jsonPost(self::API_SHORT_URL, $params);

        return $response['short_url'];
    }

    /**
     * 获取当前URL
     *
     * @return string
     */
    public static function current()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
                        && $_SERVER['HTTPS'] !== 'off'
                        || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }else{
            $host = $_SERVER['HTTP_HOST'];
        }
        return $protocol.$host.$_SERVER['REQUEST_URI'];
    }
}