<?php

namespace Maatwebsite\Excel\Tests\Columns;

use Maatwebsite\Excel\Columns\Decimal;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DecimalColumnTest extends BaseColumnTest
{
    /**
     * @param  mixed  $given
     *
     * @test
     * @dataProvider exportValues
     */
    public function can_write_column_values_explicitly($given, $expected)
    {
        $this->write(Decimal::make('Number Column'), [
            'number_column' => $given,
        ]);

        $this->assertCellValue($expected);
        $this->assertCellDataType(DataType::TYPE_NUMERIC);
        $this->assertNumberFormat(NumberFormat::FORMAT_NUMBER_00);
    }

    public function exportValues(): array
    {
        return [
            [null, 0.0],
            [10, 10.0],
            ['10', 10.0],
            ['10.50', 10.5],
        ];
    }

    /**
     * @param  mixed  $given
     *
     * @test
     * @dataProvider importValues
     */
    public function can_read_column_values_explicitly($given, string $givenDataType, string $numberFormat, float $expected)
    {
        $this->givenCellValue($given, $givenDataType, $numberFormat);

        $value = $this->readCellValue(
            Decimal::make('Text Column')
        );

        $this->assertSame($expected, $value);
    }

    public function importValues(): array
    {
        return [
            [null, DataType::TYPE_NULL, NumberFormat::FORMAT_GENERAL, 0.00],
            [10, DataType::TYPE_NUMERIC, NumberFormat::FORMAT_TEXT, 10.00],
            ['10', DataType::TYPE_STRING, NumberFormat::FORMAT_NUMBER, 10.00],
            ['10.50', DataType::TYPE_STRING, NumberFormat::FORMAT_TEXT, 10.50],
            [10.50, DataType::TYPE_NUMERIC, NumberFormat::FORMAT_NUMBER_00, 10.50],
        ];
    }
}
