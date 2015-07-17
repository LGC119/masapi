<?php
/**
 * Semantic.php
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

use MasApi\Wechat\Utils\Bag;

/**
 * 语义理解
 */
class Semantic
{

    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;

    /**
     * 应用ID
     *
     * @var string
     */
    protected $appId;

    const API_SEARCH = 'https://api.weixin.qq.com/semantic/semproxy/search';

    /**
     * constructor
     *
     * @param string $appId
     * @param string $masAccessToken
     */
    public function __construct($appId, $clientId, $uuid)
    {
        $this->appId = $appId;
        $this->http = new Http(new AccessToken($appId, $clientId, $uuid));
    }

    /**
     * 语义理解
     *
     * @param string         $keyword
     * @param array | string $categories
     * @param array          $other
     *
     * @return Bag
     */
    public function query($keyword, $categories, array $other = array())
    {
        $params = array(
                   'query'    => $keyword,
                   'category' => implode(',', (array) $categories),
                   'appid'    => $this->appId,
                  );

        return new Bag($this->http->jsonPost(self::API_CREATE, array_merge($params, $other)));
    }
}
