<?php
/**
 * Created by PhpStorm.
 * User: emelyne
 * Date: 06.06.17
 * Time: 15:20
 */

namespace ProjectPHP;
use Mailgun\Mailgun;

class callMailGun
{

    protected $apikey ='key-6a71a5811d4eb343264a0e40b4d7aca8';

    public function callMail($board,$table,$fromdate,$todate){
        $config = include 'conf.local.php';
        # Instantiate the client.
        $mgClient = new Mailgun($this->apikey);
        $domain = "sandbox3dfd3fc5cd324019a41b576cc47bc7e8.mailgun.org";
        # Make the call to the client.
        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'Dashboard <mailgun@sandbox3dfd3fc5cd324019a41b576cc47bc7e8.mailgun.org>',
            'to'      => 'Emelyne <'.$config['MAIL'].'>',
            'subject' => 'Report data from '.$fromdate.' to '.$todate.' for board n° '.$board,
            'text'    => $table
        ));
        return $result;
    }

}