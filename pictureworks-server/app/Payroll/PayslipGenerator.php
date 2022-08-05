<?php

namespace App\Payroll;

class PayslipGenerator
{
    /** @var PayrollItemRepository */
    protected $payrollItemsRepo;

    public function __construct(PayrollItemRepository $payrollItemsRepo)
    {
        $this->payrollItemsRepo = $payrollItemsRepo;
    }

    public function demoImplemSkip()
    {
        return $this->payrollItemsRepo->find(1);
    }
}
