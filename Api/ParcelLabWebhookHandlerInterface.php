<?php

namespace MageSuite\OrderImport\Api;

/**
 * OrderImport - updating orders by ParcelLab Webhook
 * @api
 */
interface ParcelLabWebhookHandlerInterface
{
    /**
     * Handle webhook from ParcelLab
     *
     * @api
     * @param string $event
     * @param string[] $references
     * @param string[] $data
     * @return boolean
     */
    public function handle($event, $references, $data);
}