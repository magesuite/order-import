<?php

namespace MageSuite\OrderImport\Services\Import;

interface ImporterType
{
    /**
     * @param string $type
     * @param string $remoteDirectory
     */
    public function run($type, $remoteDirectory);
}