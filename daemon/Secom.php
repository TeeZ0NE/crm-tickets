<?

class Secom
{
    private $url = '';
    private $identifier = '';
    private $secret = '';

    private $arr = [];

    function __construct()
    {
        $this->url = config('curl-connection.secom.url');
        $this->identifier = config('curl-connection.secom.identifier');
        $this->secret = config('curl-connection.secom.secret');
    }

    public function getTiket($id)
    {
        $tiketfields = array(
            'username' => $this->identifier,
            'password' => $this->secret,
            'action' => 'GetTicket',
            'responsetype' => 'json',
            'ticketid' => $id,
        );

        return json_decode($this->getWhmcsData($tiketfields, $this->url), true);
    }

    public function getListTikets()
    {
        $tiketsfields = array(
            'username' => $this->identifier,
            'password' => $this->secret,
            'status' => 'Awaiting Reply',
            'action' => 'GetTickets',
            'responsetype' => 'json',
            'limitnum' => 999
        );

        return json_decode($this->getWhmcsData($tiketsfields, $this->url), true);
    }

    public function getFullListTikets()
    {
        $tiketfields = array(
            'username' => $this->identifier,
            'password' => $this->secret,
            'action' => 'GetTicket',
            'responsetype' => 'json',
        );

        $tikets = $this->getListTikets();

        foreach ($tikets['tickets'] as $item) {
            foreach ($item as $val) {
                $tiketfields['ticketid'] = $val['id'];
                $this->arr[$val['id']] = json_decode($this->getWhmcsData($tiketfields, $this->url), true);
            }
        }

        return json_encode($this->arr, false);
    }

    private function getWhmcsData($fields, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }
}