<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use Illuminate\Support\Str;

class WhatsappHelper
{
    protected $apiKey;
    protected $logChannel = 'whatsapp';

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        if (!$this->apiKey) {
            $this->logError('Token API Fonnte tidak ditemukan di konfigurasi', [
                'config_path' => 'services.fonnte.api_key',
                'env_key' => env('FONNTE_API_KEY')
            ]);
            throw new \Exception('Token API Fonnte tidak ditemukan');
        }
        $this->logInfo('Token API Fonnte berhasil dimuat', [
            'token_partial' => $this->getMaskedToken()
        ]);
    }

    public function sendMessage($to, $message, $options = [])
    {
        $logContext = [
            'target' => $to,
            'message' => Str::limit($message, 100),
            'options' => $options,
            'token' => $this->getMaskedToken()
        ];

        // Phone number validation
        if (!Customer::validatePhone($to)) {
            $this->logWarning('Nomor telepon tidak valid', $logContext);
            return $this->formatResponse(false, 'Nomor telepon tidak valid');
        }

        $data = $this->prepareRequestData($to, $message, $options);
        
        try {
            $startTime = microtime(true);
            
            $response = Http::asForm()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => $this->apiKey,
                ])->post('https://api.fonnte.com/send', $data);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $responseData = $response->json();

            // Enhanced logging
            $logContext = array_merge($logContext, [
                'response' => $responseData,
                'status_code' => $response->status(),
                'response_time_ms' => $responseTime,
                'request_data' => $this->sanitizeData($data)
            ]);

            if ($response->successful() && ($responseData['status'] ?? false)) {
                $this->logSuccess('Pesan WhatsApp berhasil dikirim', $logContext);
                return $this->formatResponse(true, 'Pesan berhasil dikirim', $responseData);
            }

            $this->logError('Gagal mengirim pesan WhatsApp', $logContext);
            return $this->handleFailedResponse($responseData);

        } catch (\Exception $e) {
            $this->logCritical('Exception saat mengirim pesan WhatsApp', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'context' => $logContext
            ]);
            return $this->formatResponse(false, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function prepareRequestData($to, $message, $options)
    {
        $data = [
            'target' => $to,
            'message' => $message,
            'countryCode' => $options['countryCode'] ?? '62',
        ];

        // List of supported options
        $supportedOptions = [
            'typing', 'url', 'filename', 'schedule', 'delay', 'location',
            'followup', 'choices', 'select', 'pollname', 'file', 'connectOnly',
            'sequence', 'preview'
        ];

        foreach ($supportedOptions as $option) {
            if (isset($options[$option])) {
                $data[$option] = $options[$option];
            }
        }

        return $data;
    }

    protected function handleFailedResponse($responseData)
    {
        $reason = $responseData['reason'] ?? 'Gagal mengirim pesan';
        
        if (($responseData['reason'] ?? '') === 'invalid token') {
            $this->logAlert('Token API Fonnte tidak valid');
            return $this->formatResponse(false, 'Token API Fonnte tidak valid');
        }

        return $this->formatResponse(false, $reason);
    }

    protected function formatResponse($success, $message = '', $data = [])
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toDateTimeString()
        ];
    }

    protected function getMaskedToken()
    {
        return substr($this->apiKey, 0, 6) . '...' . substr($this->apiKey, -4);
    }

    protected function sanitizeData($data)
    {
        $sanitized = $data;
        if (isset($sanitized['message'])) {
            $sanitized['message'] = Str::limit($sanitized['message'], 50);
        }
        return $sanitized;
    }

    // Enhanced logging methods
    protected function logInfo($message, $context = [])
    {
        Log::channel($this->logChannel)->info($message, $context);
    }

    protected function logSuccess($message, $context = [])
    {
        Log::channel($this->logChannel)->notice('SUCCESS: ' . $message, $context);
    }

    protected function logWarning($message, $context = [])
    {
        Log::channel($this->logChannel)->warning('WARNING: ' . $message, $context);
    }

    protected function logError($message, $context = [])
    {
        Log::channel($this->logChannel)->error('ERROR: ' . $message, $context);
    }

    protected function logCritical($message, $context = [])
    {
        Log::channel($this->logChannel)->critical('CRITICAL: ' . $message, $context);
    }

    protected function logAlert($message, $context = [])
    {
        Log::channel($this->logChannel)->alert('ALERT: ' . $message, $context);
    }
}