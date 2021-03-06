<?php
/**
 * Transfer.php
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

namespace MasApi\Wechat\Messages;

use MasApi\Wechat\Exception;

/**
 * 转发多客服消息
 *
 * @property string $to
 * @property string $account
 */
class Transfer extends BaseMessage
{

    /**
     * 属性
     *
     * @var array
     */
    protected $properties = array(
                             'account',
                             'to',
                            );

    /**
     * 生成主动消息数组
     */
    public function toStaff()
    {
        throw new Exception('转发类型不允许主动发送');
    }

    /**
     * 生成回复消息数组
     */
    public function toReply()
    {
        $response = array('MsgType' => 'transfer_customer_service');

        // 指定客服
        if (!empty($this->account) || !empty($this->to)) {
            $response['TransInfo'] = array(
                                      'KfAccount' => $this->account ?: $this->to,
                                     );
        }

        return $response;
    }
}
