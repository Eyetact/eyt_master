<?php

namespace App\Repositories;
use Webklex\IMAP\Facades\Client;
use Config;

class ImapRepository {

    public function makeAccountAndConnection($data,$type='') {
        // dd($data);
        if(isset($data->smtps) && !empty($data->smtps)){
            $config = array(
                'driver'     => 'smtp',
                'host'       => $data->smtps->mail_server,
                'port'       => $data->smtps->port,
                'username'   => $data->smtps->email,
                'password'   => $data->smtps->password,
                'encryption' => $data->smtps->encryption_mode,
                'from'       => array('address' => $data->smtps->sender_address, 'name' => $data->smtps->mailer_id),
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );

            Config::set('mail', $config);
            // dd(Config::get('mail'));
        }
        if(empty($type)){
            $data->port=993;
            $data->host='imap.gmail.com';
            $data->encryption_mode='ssl';
            $data->protocol='imap';
            $conn_array=[
                'host'          => $data->imaps->imap_mail_server,
                'port'          => $data->imaps->imap_port,
                'encryption'    => $data->imaps->imap_encryption_mode,
                'validate_cert' => true,
                'username'      => $data->imaps->email,
                'password'      => $data->imaps->password,
                'protocol'      => 'imap',
                'options' => [
                    'fetch_order' => 'desc',
                ]
            ];
            // dd($data,$conn_array);
            $oClient = Client::make($conn_array);

            $oClient->connect();
            return $oClient;
        }

    }

}
