<?php

namespace App\Reader;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Webmozart\Assert\Assert;

/**
 * Class CsvReader
 *
 * @package App\Reader
 */
class CsvReader implements ReaderInterface
{
    /** @var CsvEncoder */
    private CsvEncoder $encoder;

    /**
     * @param CsvEncoder $encoder
     */
    public function __construct(CsvEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param string $inputFile
     * @param array  $options
     *
     * @return mixed
     */
    public function readFile(string $inputFile, array $options = [])
    {
        return $this->readString(
            $this->loadFile($inputFile),
            $options
        );
    }

    /**
     * @param string $inputString
     * @param array  $options
     *
     * @return array|mixed
     */
    public function readString(string $inputString, array $options = [])
    {
        return $this->encoder->decode(
            $inputString,
            CsvEncoder::FORMAT,
            array_merge([
                CsvEncoder::ESCAPE_FORMULAS_KEY => true,
                CsvEncoder::NO_HEADERS_KEY => true,
            ], $options)
        );
    }

    /**
     * @param string $inputFile
     *
     * @return string
     */
    public function loadFile(string $inputFile): string
    {
        Assert::fileExists($inputFile);
        $content = file_get_contents($inputFile);

        return $content !== false ? $content : '';
    }
}
