<?php

namespace Spatie\SslCertificate;

class Downloader
{
    public static function downloadCertificateFromUrl(string $url, int $timeout = 30): array
    {
        $hostName = (new Url($url))->getHostName();

        $streamContext = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
            ],
        ]);

        $client = stream_socket_client(
            "ssl://{$hostName}:443",
            $errorNumber,
            $errorDescription,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $streamContext);

        $response = stream_context_get_params($client);

        return openssl_x509_parse($response['options']['ssl']['peer_certificate']);
    }
}
