<?php

namespace App\Actions\Bitcoin;

class BitcoinActions extends BaseAction
{
    public function createTransaction(string $fromAddress, string $toAddress, float $amount)
    {
        return $this->post('txs/new', [
            'inputs' => [['addresses' => [$fromAddress]]],
            'outputs' => [['addresses' => [$toAddress], 'value' => (int)($amount * 100000000)]],
            'preference' => 'medium'
        ]);
    }

    public function fundAddress(string $address, int $amount)
    {
        return $this->post('faucet', [
            'address' => $address,
            'amount' => $amount
        ]);
    }

    public function generateAddress()
    {
        $response = $this->post('addrs', [
            'wallet_name' => 'my_hd_wallet',
            'subchain_index' => 0,
            'extended_public_key' => $this->zpub
        ]);
        return $response['address'] ?? null;
    }

    public function getAddressBalance(string $address)
    {
        $response = $this->get("addrs/{$address}/balance");
        return isset($response['balance']) ? $response['balance'] / 100000000 : null;
    }

    public function sendTransaction(array $signedTx)
    {
        return $this->post('txs/send', $signedTx);
    }

    public function registerWebhook(string $url, string $address)
    {
        return $this->post('hooks', [
            'event' => 'tx-confirmation',
            'url' => $url,
            'address' => $address,
            'confirmations' => 3
        ]);
    }

    public function deleteWebhook(string $webhookId): array
    {
        return $this->request('DELETE', "hooks/{$webhookId}");
    }
}
