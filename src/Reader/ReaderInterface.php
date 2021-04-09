<?php

namespace App\Reader;

/**
 * Interface ReaderInterface
 *
 * @package App\Reader
 */
interface ReaderInterface
{
    /**
     * @param string $inputFile
     * @param array  $options
     *
     * @return mixed
     */
    public function readFile(string $inputFile, array $options = []);

    /**
     * @param string $inputString
     * @param array  $options
     *
     * @return mixed
     */
    public function readString(string $inputString, array $options = []);
}
