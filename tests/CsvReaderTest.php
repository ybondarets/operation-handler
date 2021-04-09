<?php

namespace App\Tests;

use App\Reader\CsvReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

/**
 * Class CsvReaderTest
 *
 * @package App\Tests
 */
class CsvReaderTest extends TestCase
{
    public function testReadSingleLine(): void
    {
        $reader = $this->createReader();

        $data = $reader->readString('2014-12-31,4,private,withdraw,1200.00,EUR');

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertCount(6, $data[0]);
    }

    public function testReadMultipleLines(): void
    {
        $reader = $this->createReader();

        $data = $reader->readString('2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY
');

        $this->assertIsArray($data);
        $this->assertCount(13, $data);
        $this->assertCount(6, $data[0]);
    }

    /**
     * @param string $delimiter
     *
     * @return CsvReader
     */
    private function createReader(string $delimiter = ','): CsvReader
    {
        return new CsvReader(new CsvEncoder([]));
    }
}
