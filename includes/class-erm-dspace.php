<?php
class ERM_DSpace {
    private $csrf = null;
    private $bearer = null;
    private $cookies = [];
    
    private $username;
    private $password;
    private $collection_id;

    public function __construct() {
        $this->username = "giancarlo_olivares@edupan.com";
        $this->password = "G@LD8(KrW9kEJZwF";
        $this->collection_id = "4470bb28-256f-4ed1-bde9-f29ef135d22c";
    }

    private function getCsrfAndBearer($responseHeaders) {
        $cookies = [];
        foreach ($responseHeaders as $header) {
            if (stripos($header, 'Authorization:') === 0) {
                $this->bearer = substr($header, 15);
            }
            if (stripos($header, 'Set-Cookie:') === 0) {
                $cookie = explode(';', substr($header, 12))[0];
                $cookies[] = $cookie;
                if (strpos($cookie, 'DSPACE-XSRF-COOKIE=') !== false) {
                    $this->csrf = str_replace('DSPACE-XSRF-COOKIE=', '', $cookie);
                }
            }
        }
        $this->cookies = $cookies;
    }

    private function makeRequestWithCookies($url, $method = "GET", $body = "", $contenttype = "application/json") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        
        $headers = [];
        if ($this->csrf) {
            $headers[] = "X-XSRF-TOKEN: " . $this->csrf;
        }
        if ($this->bearer) {
            $headers[] = "Authorization: " . $this->bearer;
        }
        $headers[] = "Cookie: " . implode("; ", $this->cookies);
        $headers[] = "Content-Type: $contenttype";
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headersResponse = explode("\r\n", substr($response, 0, $headerSize));
        $body = substr($response, $headerSize);
        
        curl_close($ch);
        $this->getCsrfAndBearer($headersResponse);
        
        if ($httpcode > 299) {
            throw new Exception("Error en la solicitud a DSpace");
        }
        
        return json_decode($body, true);
    }

    private function getLoginStatus() {
        return $this->makeRequestWithCookies("https://backdspace.edupan.dev/server/api/authn/status");
    }

    private function iniciarSesion() {
        $this->getLoginStatus();
        
        $this->makeRequestWithCookies(
            "https://backdspace.edupan.dev/server/api/authn/login?user={$this->username}&password={$this->password}",
            "POST",
            json_encode([
                "user" => $this->username,
                "password" => $this->password
            ])
        );
        
        $this->getLoginStatus();
    }

    public function uploadItem($filename, $metadata) {
        try {
            $this->iniciarSesion();

            // Crear workspace item
            $data = json_encode(["type" => "workspaceitem"]);
            $responseWorkspaceitem = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems?owningCollection={$this->collection_id}",
                "POST",
                $data
            );

            $this->getLoginStatus();
            $workspaceid = $responseWorkspaceitem['id'];

            // Actualizar metadata
            $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid",
                "PATCH",
                json_encode($metadata)
            );

            $this->getLoginStatus();

            // Subir archivo
            $file = new CURLFile($filename);
            $filedata = ["file" => $file];
            $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid",
                "POST",
                $filedata,
                "multipart/form-data"
            );

            $this->getLoginStatus();

            // Finalizar workflow
            $workspaceitemsUrl = "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid";
            $response = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/workflow/workflowitems",
                "POST",
                $workspaceitemsUrl,
                "text/uri-list"
            );

            return true;
        } catch (Exception $e) {
            error_log('Error en DSpace upload: ' . $e->getMessage());
            throw $e;
        }
    }
}