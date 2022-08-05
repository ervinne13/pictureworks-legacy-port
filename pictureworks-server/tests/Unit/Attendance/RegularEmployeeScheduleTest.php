<?php

namespace Tests\Unit\Attendance;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;



class RegularEmployeeScheduleTest extends TestCase
{
    /**
     * A basic unit test example.
     * 
     * @return void
     */
    public function test_absense_payroll_item_generation()
    {
        $period = new PayrollPeriod('PAY-0001', PayrollPeriod::BI_MONTHLY, '2022-07-01', '2022-07-15');
        $empCode = '123';

        $generator = $this->app->make(AbsencePayrollItemGenerator::class);
        $payrollItem = $generator->getAbsences($empCode, $period->from, $period->to);

        // TODO: manually generate 2 absences for this period

        $this->assertTrue(2 == $payrollItem->qty);

        // TODO: check for computed amount
        // TODO: everything else

    }
}
