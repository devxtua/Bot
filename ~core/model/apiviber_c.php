<?php
class Viber
{
    private $url_api = "https://chatapi.viber.com/pa/";

    private $token = "4bda234b30e7d099-feed57e7f18fe60-9a3990190915abd4";

    public function message_post (
						        $from,          // ID администратора Public Account.
						        array $sender,  // Данные отправителя.
						        $text           // Текст.
						    )
    {
        $data['from']   = $from;
        $data['sender'] = $sender;
        $data['type']   = 'text';
        $data['text']   = $text;
        return $this->call_api('post', $data);
    }

    private function call_api($method, $data)
    {
        $url = $this->url_api.$method;

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\nX-Viber-Auth-Token: ".$this->token."\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response);
    }



    function send_message($receiverID,$TextMessage){

		$curl = curl_init();
		$json_data = '{
				"receiver":"'.$receiverID.'",
				"min_api_version":1,
				"sender":{"name":"bucks",
							"avatar":"avatar.example.com"},
				"tracking_data":"tracking data",
				"type":"text",
				"text":"'.$TextMessage.'"
				}';
		$data = json_decode($json_data); // Преобразовываем в json код

		$array_curl = array(
			CURLOPT_URL => "https://chatapi.viber.com/pa/send_message",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data) , // отправка кода
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/JSON",
				"X-Viber-Auth-Token: ".$this->token.'"'
				),
			);

		curl_setopt_array($curl, $array_curl);

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			// echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}







}