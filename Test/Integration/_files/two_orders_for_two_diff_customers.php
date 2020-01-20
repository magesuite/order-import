<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

include __DIR__ . '/../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order.php';
include __DIR__ . '/../../../../../../dev/tests/integration/testsuite/Magento/Customer/_files/two_customers.php';

$customerIdFromFixture = 1;

$order->setCustomerId($customerIdFromFixture)->setCustomerIsGuest(false)->save();

/** @var \Magento\Sales\Model\Order\Item $orderItem */
$orderItem = $objectManager->create(\Magento\Sales\Model\Order\Item::class);
$orderItem->setProductId(1)
    ->setQtyOrdered(2)
    ->setBasePrice($product->getPrice())
    ->setPrice($product->getPrice())
    ->setRowTotal($product->getPrice())
    ->setProductType('simple')
    ->setName($product->getName());

$payment2 = $objectManager->create(\Magento\Sales\Model\Order\Payment::class);
$payment2->setMethod('checkmo');

/** @var \Magento\Sales\Model\Order $order */
$order2 = $objectManager->create(\Magento\Sales\Model\Order::class);
$order2->setIncrementId('100000002')
    ->setState(
        \Magento\Sales\Model\Order::STATE_PROCESSING
    )->setStatus(
        $order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PROCESSING)
    )->setSubtotal(
        100
    )->setBaseSubtotal(
        100
    )->setBaseGrandTotal(
        100
    )->setCustomerIsGuest(
        true
    )->setCustomerEmail(
        'customer2@null.com'
    )->setBillingAddress(
        $billingAddress
    )->setShippingAddress(
        $shippingAddress
    )->setStoreId(
        $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getId()
    )->addItem(
        $orderItem
    )->setPayment(
        $payment2
    );

$order2->save();

$customerIdFromFixture = 2;
$order2->setCustomerId($customerIdFromFixture)->setCustomerIsGuest(false)->save();
