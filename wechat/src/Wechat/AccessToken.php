<?php
/**
 * AccessToken.php
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
 * 全局通用 AccessToken
 */
class AccessToken
{

    /**
     * 应用ID
     *
     * @var string
     */
    protected $appId;

    /**
     * 应用secret
     *
     * @var string
     */
    protected $masAccessToken;

    /**
     * 缓存类
     *
     * @var Cache
     */
    protected $cache;

    /**
     * token
     *
     * @var string
     */
    protected $token;

    /**
     * 缓存前缀
     *
     * @var string
     */
    protected $cacheKey = 'overtrue.wechat.access_token';

    // API
    const API_TOKEN_GET = 'https://prism-dev.masengine.com/app/index.php/Api/getWxAccessToken';
    const MAS_TOKEN_GET = 'https://prism-dev.masengine.com/app/index.php/oauth/AuthCode_Controller/authorize';

    /**
     * constructor
     *
     * @param string $appId
     * @param string $masAccessToken
     */
    public function __construct($appId, $clientId, $uuid)
    {
        $this->appId     = $appId;
        $this->clientId = $clientId;
        $this->uuid = $uuid;
        $this->cache     = new Cache($appId);
    }

    /**
     * 缓存 setter
     *
     * @param Cache $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * 获取Token
     *
     * @return string
     */
    public function getToken()
    {
        if ($this->token) {
            return $this->token;
        }

        // for php 5.3
        $appId       = $this->appId;
        $clientId   = $this->clientId;
        $uuid   = $this->uuid;
        $cache       = $this->cache;
        $cacheKey    = $this->cacheKey;
        $apiTokenGet = self::API_TOKEN_GET;
        $masTokenGet = self::MAS_TOKEN_GET;

        return $this->token = $this->cache->get(
            $cacheKey,
            function ($cacheKey) use ($appId, $clientId, $uuid, $cache, $apiTokenGet, $masTokenGet) {
                //获取平台token
                $masParams = array(
                    'client_id' => $clientId,
                    'uuid'      => $uuid,
                );
                $http = new Http();

                $masToken = $http->get($masTokenGet, $masParams);

                //用平台token换取微信token
                $params = array(
                           'appid'          => $appId,
                           'access_token'   => $masToken['access_token'],
                          );

                $token = $http->get($apiTokenGet, $params);

                $cache->set($cacheKey, $token['access_token'], $token['expires_in']);

                return $token['access_token'];
            }
        );
    }
}
